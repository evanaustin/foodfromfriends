<?php
 
class Order extends Base {

    public
        $id,
        $user_id,
        $subtotal,
        $fff_fee,
        $exchange_fees,
        $total,
        $stripe_charge_id,
        $placed_on;

    public 
        $Growers;
    
    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'orders';

        $this->class_dependencies = [
            'DB'
        ];

        parent::__construct($parameters);
    
        // Instantiating this object with an ID will result in a full loadout of the order(/cart)'s
        // growers and items. `Order->Growers` is an array of `OrderGrower` classes. Each
        // `Order->Growers` array element has a `FoodListings` property, which is an array of all food
        // listings added to this order that are sold by that grower. Both are keyed by their 
        // base `grower_operation_id` and `food_listing_id` value, respectively.
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);

            // Ensure we have the latest prices. As a side effect, this loads the growers in this
            // order to `$this->OrderGrowers`.
            if ($this->is_cart() === true) {
                $this->update_cart();
            } else {
                $this->load_growers();
            }
        }
    }

    /**
     * Finds this user's cart and returns it (a cart is an `order` record that hasn't been processed).
     * If the user doesn't have a cart, this method will create an empty one for them.
     *
     * @param int $user_id
     * @return self
     */
    public function get_cart($user_id) {
        $results = $this->DB->run('
            SELECT id
            FROM orders
            WHERE user_id=:user_id 
                AND placed_on IS NULL'
        , [
            'user_id' => $user_id
        ]);

        if (!isset($results[0]['id'])) {
            $result = $this->add([
                'user_id' => $user_id,
            ]);

            $order_id = $result['last_insert_id'];
        } else {
            $order_id = $results[0]['id'];
        }

        return new Order([
            'DB' => $this->DB,
            'id' => $order_id
        ]);
    }

    /**
     * Finds all the growers (and through them, the exchange and food listings) in this order and assigns them to 
     * `$this->Growers`.
     */
    public function load_growers() {
        $OrderGrower = new OrderGrower([
            'DB' => $this->DB
        ]);

        $this->Growers = $OrderGrower->load_for_order($this->id, $this->user_id);
    }

    /**
     * Tells us whether this order has been placed, or if it's still in the Shopping Cart status.
     *
     * @return bool
     */
    public function is_cart() {
        return (!isset($this->placed_on));
    }

    /**
     * Determine whether a shopping cart is empty.
     *
     * @return bool
     */
    public function is_empty() {
        return (!isset($this->Growers) || empty($this->Growers));
    }

    /**
     * Adds an item to the shopping cart. Adds OrderGrower and OrderExchange records too if 
     * they don't already exist for this grower.
     * 
     * @param \GrowerOperation $Seller
     * @param string $exchange_option
     * @param \FoodListing $FoodListing
     * @param int $quantity
     */
    public function add_to_cart(GrowerOperation $Seller, $exchange_option, FoodListing $FoodListing, $quantity) {
        if ($this->is_cart() !== true) {
            throw new \Exception('Cannot add items to this order.');
        }

        // If this grower doesn't have any items in the cart yet, we need to add them to the cart
        if (!isset($this->Growers[$Seller->id])) {
            $this->add_grower($Seller, $exchange_option);
        }

        $this->Growers[$Seller->id]->add_food_listing($FoodListing, $quantity);

        // Refresh the cart
        $this->update_cart();
    }

    /**
     * Adds a grower and its exchange to this order and refreshes `$this->Growers`.
     * 
     * @param \GrowerOperation $GrowerOperation
     * @param string $exchange_option
     */
    private function add_grower(GrowerOperation $GrowerOperation, $exchange_option) {
        $exchange = $this->add([
            'type' => $exchange_option
        ], 'order_exchanges');
        
        $this->add([
            'order_id'              => $this->id,
            'user_id'               => $this->user_id,
            'grower_operation_id'   => $GrowerOperation->id,
            'order_exchange_id'     => $exchange['last_insert_id']
        ], 'order_growers');

        $this->load_growers();
    }

    /**
     * Removes an item from the cart entirely, including the grower if this was the grower's only item
     * in this cart.
     */
    public function remove_from_cart(FoodListing $FoodListing) {
        if ($this->is_cart() !== true) {
            throw new \Exception('Cannot add items to this order.');
        }
        
        $this->Growers[$FoodListing->grower_operation_id]->FoodListings[$FoodListing->id]->delete();
        
        // If this was the only listing for this grower, remove the OrderGrower entirely
        if (count($this->Growers[$FoodListing->grower_operation_id]->FoodListings) == 1) {
            $this->Growers[$FoodListing->grower_operation_id]->delete();
            // ! do this for OrderExchange too
        }

        // Refresh the cart
        $this->update_cart();
    }

    /**
     * Updates the quantity of the provided item.
     */
    public function modify_quantity(FoodListing $FoodListing, $quantity) {
        if ($this->is_cart() !== true) {
            throw new \Exception('Cannot add items to this order.');
        }

        if ($quantity == 0) {
            return $this->remove_from_cart($FoodListing);
        }

        $this->Growers[$FoodListing->grower_operation_id]->FoodListings[$FoodListing->id]->modify_quantity($quantity);

        // Refresh the cart
        $this->update_cart();
    }

    /**
     * This method should be called every time the cart is modified or instantiated. It updates prices
     * weights, and totals in the database and loads those properties into the object, ensuring everything 
     * is up-to-date.
     */
    private function update_cart() {
        // Orders once paid for are set in stone!
        if ($this->is_cart() !== true) {
            throw new \Exception('This order is not a cart!');
        }

        // Make sure we have the latest grower info in this object
        $this->load_growers();

        // Set food listing prices, weights, and totals
        foreach ($this->Growers as $OrderGrower) {
            $OrderGrower->Exchange->sync();
            $OrderGrower->sync_food_listing();
            $OrderGrower->calculate_total();
        }

        // Calculate total amounts for the entire order
        $this->calculate_total_and_fees();
    }

    /**
     * Calculates the final tally of order fees, subtotals, totals, etc.
     * 
     * @todo Introduce tiered pricing
     */
    private function calculate_total_and_fees() {
        $this->subtotal = 0;
        $this->exchange_fees = 0;

        foreach ($this->Growers as $OrderGrower) {
            $this->subtotal += $OrderGrower->subtotal;
            $this->exchange_fees += $OrderGrower->Exchange->fee;
        }

        /**
         * The rate should be variable depending on order total:
         * if ($this->subtotal < $50) then $rate = 10%
         * if ($this->subtotal > $50 && < $100) then $rate = 7.5%
         * if ($this->subtotal > $100) then $rate = 5%
         */
        $rate = 0.1;
        $fff_fee = bcmul($this->subtotal, $rate);
        
        // charge the greater of 10% and $0.50
        $this->fff_fee = ($fff_fee < 50) ? 50 : $fff_fee;

        $this->total = $this->subtotal + $this->exchange_fees + $this->fff_fee;

        // ? use Base class
        /* $this->DB->run('
            UPDATE orders 
            SET 
                subtotal = :subtotal, 
                exchange_fees = :exchange_fees, 
                fff_fee = :fff_fee, 
                total = :total
            WHERE id = :id
            LIMIT 1
        ', [
            'subtotal' => $subtotal,
            'exchange_fees' => $exchange_fees,
            'fff_fee' => $fff_fee,
            'total' => $total,
            'id' => $this->id
        ]); */

        $this->update([
            'subtotal'      => $this->subtotal,
            'exchange_fees' => $this->exchange_fees,
            'fff_fee'       => $this->fff_fee,
            'total'         => $this->total
        ]);
    }

    /**
     * After payment has been collected, this method should be called to convert the "cart" to an "order", 
     * save payment details, and set up the payout.
     *
     * @param string $stripe_charge_id Stripe's charge ID (e.g. ch_r934249302829)
     */
    public function mark_paid($stripe_charge_id) {
        $placed_on = \Time::now();

        $this->update([
            'stripe_charge_id' => $stripe_charge_id,
            'placed_on' => $placed_on
        ]);

        // Update class properties
        $this->stripe_charge_id = $stripe_charge_id;
        $this->placed_on = $placed_on;

        // Update payout
        $Payout = new Payout([
            'DB' => $this->DB
        ]);

        $Payout->save_order($this);
    }

    /** 
     * Get all the pending orders. An order is pending if any of the sub orders are incomplete.
     * 
     * @param int $user_id The buyer ID
     */
    public function get_pending($user_id) {
        $results = $this->DB->run('
            SELECT *
            FROM orders
            WHERE user_id=:user_id 
                AND placed_on IS NOT NULL
                AND completed_on IS NULL
        ', [
            'user_id' => $user_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }
    
    /** 
     * Get all the completed orders. An order is complete if all of the sub orders are complete.
     * 
     * @param int $user_id The buyer ID
     */
    public function get_completed($user_id) {
        $results = $this->DB->run('
            SELECT *
            FROM orders
            WHERE user_id=:user_id 
                AND placed_on IS NOT NULL
                AND completed_on IS NOT NULL
        ', [
            'user_id' => $user_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }
}