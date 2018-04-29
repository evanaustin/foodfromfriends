<?php
 
class Order extends Base {

    public
        $id,
        $buyer_account_id,
        $subtotal,
        $fff_fee,
        $exchange_fees,
        $total,
        $charge_id;

    public 
        $Growers,
        $Charge;
    
    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'orders';

        $this->class_dependencies = [
            'DB'
        ];

        parent::__construct($parameters);
    
        /**
         * Instantiating this object with an ID will result in a full loadout of the order/cart's
         * growers and items. `Order->Growers` is an array of `OrderGrower` classes. Each
         * `Order->Growers` array element has a `FoodListings` property, which is an array of all food
         * listings added to this order that are sold by that grower. Both are keyed by their 
         * base `grower_operation_id` and `food_listing_id` value, respectively.
         */
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);

            // Ensure we have the latest prices. As a side effect, this loads the growers in this
            // order to `$this->OrderGrowers`.
            if ($this->is_cart() === true) {
                $this->update_cart();
            } else {
                $this->load_growers();
                $this->load_charge();
            }
        }
    }

    /**
     * Finds this user's cart and returns it (a cart is an `order` record that hasn't been processed).
     * If the buyer doesn't have a cart, this method will create an empty one for them.
     *
     * @param int $buyer_account_id
     * @return self
     */
    public function get_cart($buyer_account_id) {
        $results = $this->DB->run('
            SELECT id
            FROM orders
            WHERE buyer_account_id  =:buyer_account_id 
                AND charge_id       =:charge_id'
        , [
            'buyer_account_id'  => $buyer_account_id,
            'charge_id'         => 0
        ]);

        if (!isset($results[0]['id'])) {
            $result = $this->add([
                'buyer_account_id' => $buyer_account_id
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

        $this->Growers = $OrderGrower->load_for_order($this->id);
    }

    /**
     * Finds the associated charge for this order and assigns it to `$this->Charge`.
     */
    public function load_charge() {
        $this->Charge = new Charge([
            'DB' => $this->DB,
            'id' => $this->charge_id
        ]);
    }

    /**
     * Tells us whether this order has been placed, or if it's still in the Shopping Cart status.
     *
     * @return bool
     */
    public function is_cart() {
        return (empty($this->charge_id));
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
            'buyer_account_id'      => $this->buyer_account_id,
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
        
        // If this was the only listing for this grower, remove the OrderGrower & OrderGrower->Exchange entirely
        if (count($this->Growers[$FoodListing->grower_operation_id]->FoodListings) == 1) {
            $this->Growers[$FoodListing->grower_operation_id]->Exchange->delete();
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
     * Updates the quantity of the provided item.
     */
    public function modify_exchange(FoodListing $FoodListing, $quantity) {
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
     */
    private function calculate_total_and_fees() {
        $this->subtotal = 0;
        $this->exchange_fees = 0;

        foreach ($this->Growers as $OrderGrower) {
            $this->subtotal += $OrderGrower->subtotal;
            $this->exchange_fees += $OrderGrower->Exchange->fee;
        }

        // Set rate gradiations between 5% ($100+) and 7.5% ($75-)
        if ($this->subtotal >= 10000) {
            $rate = 0.05;
        } else if ($this->subtotal < 10000  && $this->subtotal >= 9500) {
            $rate = 0.055;
        } else if ($this->subtotal < 9500   && $this->subtotal >= 9000) {
            $rate = 0.06;
        } else if ($this->subtotal < 9000   && $this->subtotal >= 8500) {
            $rate = 0.0625;
        } else if ($this->subtotal < 8500   && $this->subtotal >= 8000) {
            $rate = 0.065;
        } else if ($this->subtotal < 8000   && $this->subtotal >= 7500) {
            $rate = 0.07;
        } else if ($this->subtotal < 7500) {
            $rate = 0.075;
        }
        
        $fff_fee = round($this->subtotal * $rate);
        
        // Charge minimum of $0.50
        $this->fff_fee = ($fff_fee < 50) ? 50 : $fff_fee;

        $this->total = $this->subtotal + $this->exchange_fees + $this->fff_fee;

        $this->update([
            'subtotal'      => $this->subtotal,
            'exchange_fees' => $this->exchange_fees,
            'fff_fee'       => $this->fff_fee,
            'total'         => $this->total
        ]);
    }

    /**
     * Converts the "cart" to an "order"
     * Create `Charge` record and tie to `Order`
     * Update item stocks
     * Create `Status` records and tie to `$this->OrderGrowers`
     *
     * @param string $stripe_charge_id Stripe's charge ID (e.g. ch_r934249302829)
     */
    public function submit_payment($stripe_charge_id) {
        $authorized_on = \Time::now();

        // Create `Charge` record
        $charge = $this->add([
            'subtotal'          => $this->subtotal,
            'fff_fee'           => $this->fff_fee,
            'exchange_fees'     => $this->exchange_fees,
            'total'             => $this->total,
            'stripe_charge_id'  => $stripe_charge_id,
            'authorized_on'     => $authorized_on
        ], 'charges');

        // Tie `Charge` to `$this`
        $this->charge_id = $charge['last_insert_id'];

        $this->update([
            'charge_id' => $this->charge_id
        ]);

        $this->load_charge();

        // Run through suborders
        foreach ($this->Growers as $OrderGrower) {
            // Update item stocks
            foreach($OrderGrower->FoodListings as $key => $OrderFoodListing) {
                $FoodListing = new FoodListing([
                    'DB' => $this->DB,
                    'id' => $key
                ]);

                $remaining = $FoodListing->quantity - $OrderFoodListing->quantity;
                
                if ($remaining > 0) {
                    $FoodListing->update([
                        'quantity'      => $remaining
                    ]);
                } else {
                    $FoodListing->update([
                        'quantity'      => 0,
                        'is_available'  => 0
                    ]);
                }
            }

            // Create `Status` record
            $status = $this->add([
                'placed_on' => $authorized_on
            ], 'order_statuses');

            // Tie `Status` to `$this->OrderGrower`
            $OrderGrower->update([
                'order_status_id' => $status['last_insert_id']
            ]);
        }
    }

    public function void_suborder() {
        $this->Charge->update([
            'subtotal'      => $this->subtotal - $OrderGrower->subtotal,
            // 'fff_fee'       => $this->fff_fee - $OrderGrower->fff_fee,
            'exchange_fees' => $this->exchange_fees - $OrderGrower->Exchange->fee,
            'total'         => $this->total - $OrderGrower->total
        ]);
    }

    /** 
     * Get all the placed orders in batches of 10
     * 
     * @param int $buyer_account_id The buyer ID
     * @param int $start The Order ID each selection of 10 begins from
     */
    public function get_placed($buyer_account_id, $start = null) {
        $results = $this->DB->run('
            SELECT *
            FROM orders
            WHERE buyer_account_id=:buyer_account_id 
                AND charge_id > 0
            ORDER BY charge_id desc
            LIMIT 10
        ', [
            'buyer_account_id' => $buyer_account_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }

}
