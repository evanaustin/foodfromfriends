<?php
 
class Order extends Base {

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
     *
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
     * Finds all the growers (and through them, food listings) in this order and assigns them to 
     * `$this->Growers`.
     */
    public function load_growers() {
        $OrderGrower = new OrderGrower([
            'DB' => $this->DB
        ]);

        $this->Growers = $OrderGrower->load_for_order($this->id);
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
     * For telling whether a shopping cart is empty.
     *
     * @return bool
     */
    public function is_empty() {
        return (!isset($this->Growers) || empty($this->Growers));
    }

    /**
     * Adds an item to the shopping cart. Adds an OrderGrower record too if one doesn't already exist for 
     * this grower.
     *
     * @todo When do we add the exchange method? Could be done here or later.
     */
    public function add_to_cart(GrowerOperation $GrowerOperation, $exchange_option, FoodListing $FoodListing, $quantity) {
        if ($this->is_cart() !== true) {
            throw new \Exception('Cannot add items to this order.');
        }

        // If this grower doesn't have any items in the cart yet, we need to add the grower to the cart
        if (!isset($this->Growers[$GrowerOperation->id])) {
            $this->add_grower($GrowerOperation, $exchange_option);
        }

        $this->Growers[$GrowerOperation->id]->add_food_listing($FoodListing, $quantity);

        // Refresh the cart
        $this->update_cart();
    }

    /**
     * Adds a grower to this order and refreshes `$this->Growers`.
     */
    private function add_grower(GrowerOperation $GrowerOperation, $exchange_option) {
        $this->add([
            'order_id'              => $this->id,
            'grower_operation_id'   => $GrowerOperation->id,
            'exchange_option'       => $exchange_option
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
     * Sets the exchange method (delivery, pickup, meetup) the buyer want to use for the provided grower
     * in this order.
     *
     * @param \GrowerOperation $GrowerOperation The seller
     * @param int|null $delivery_settings_id Which delivery setting is being used, if applicable
     * @param int|null $meetup_settings_id Which meetup setting is being used, if applicable
     */
    // ? need case for pickup
    public function set_exchange_method(GrowerOperation $GrowerOperation, $delivery_settings_id = null, $meetup_settings_id = null) {
        if ($this->is_cart() !== true) {
            throw new \Exception('Cannot add items to this order.');
        }

        $this->Growers[$GrowerOperation->id]->set_exchange_method($type, $this->user_id, $delivery_settings_id, $meetup_settings_id);

        // Refresh the cart
        $this->update_cart();
    }

    /**
     * This method should be called every time the cart is modified or instantiated. It updates prices
     * and totals in the database and loads those properties into the object, ensuring everything is 
     * up-to-date.
     */
    private function update_cart() {
        // Orders once paid for are set in stone!
        if ($this->is_cart() !== true) {
            throw new \Exception('This order is not a cart!');
        }

        // Make sure we have the latest grower info in this object
        $this->load_growers();

        // Set food listing prices and totals
        foreach ($this->Growers as $OrderGrower) {
            $OrderGrower->sync_food_listing_prices();
            $OrderGrower->calculate_exchange_fee();
            $OrderGrower->calculate_total();
        }

        // Calculate total amounts for the entire order
        $this->calculate_total_and_fees();
    }

    /**
     * Calculates the final tally of order fees, subtotals, totals, etc.
     */
    private function calculate_total_and_fees() {
        $subtotal = 0;
        $exchange_fees = 0;

        foreach ($this->Growers as $OrderGrower) {
            $subtotal += $OrderGrower->total;
            $exchange_fees += $OrderGrower->exchange_fee;
        }

        // We charge the greater of 10% or $0.50
        $fff_fee = bcmul($subtotal, 0.1);
        $fff_fee = ($fff_fee < 50 ? 50 : $fff_fee);

        $total = $subtotal + $exchange_fees + $fff_fee;

        // ? use Base class
        $this->DB->run('
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
        ]);

        // Update class properties
        $this->subtotal = $subtotal;
        $this->exchange_fees = $exchange_fees;
        $this->fff_fee = $fff_fee;
        $this->total = $total;
    }


    /**
     * After payment has been collected, this method should be called to convert the "cart" to an "order", 
     * save payment details, and set up the payout.
     *
     * @param string $stripe_charge_id Stripe's charge ID (e.g. ch_r934249302829)
     */
    public function mark_paid($stripe_charge_id) {
        $now = \Time::now();

        // ? use Base class
        $this->DB->run('
            UPDATE orders 
            SET 
                stripe_charge_id = :stripe_charge_id, 
                placed_on = :now
            WHERE id = :id
            LIMIT 1
        ', [
            'stripe_charge_id' => $stripe_charge_id,
            'now' => $now,
            'id' => $this->id
        ]);

        // Update class properties
        $this->stripe_charge_id = $stripe_charge_id;
        $this->placed_on = $now;

        // Update payout
        $Payout = new Payout([
            'DB' => $this->DB
        ]);

        $Payout->save_order($this);
    }
}