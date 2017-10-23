<?php
 
class OrderGrower extends Base {

    public 
        $FoodListings;
    
    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'order_growers';

        $this->class_dependencies = [
            'DB'
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);
            $this->load_food_listings();
            $this->load_exchange_method();
        }
    }

    /**
     * Creates an array of every `order_grower` record for a given order.
     *
     * @param int $order_id
     * @return array Growers keyed by `grower_operation_id`!
     */
    public function load_for_order($order_id) {
        $results = $this->DB->run('
            SELECT id, grower_operation_id 
            FROM order_growers 
            WHERE order_id = :order_id
        ', [
            'order_id' => $order_id
        ]);

        $growers = [];

        if (isset($results[0]['id'])) {
            foreach ($results as $result) {
                $growers[$result['grower_operation_id']] = new OrderGrower(['id' => $result['id']]);
            }
        }

        return $growers[];
    }

    /**
     * Finds all the food listings for this grower in the current order and stores them in 
     * `$this->FoodListings`.
     */
    public function load_food_listings() {
        $OrderFoodListing = new OrderFoodListing();
        $this->FoodListings = $OrderFoodListing->load_for_grower($this->id);
    }

    /**
     * Adds a food listing to this OrderGrower and refreshes `$this->FoodListings`.  Don't worry
     * about `unit_price` and `amount` here; they're handled by the `Order->update_cart()` method.
     */
    private function add_food_listing(FoodListing $FoodListing, $quantity) {
        $this->DB->insert('order_food_listings', [
            'order_id' => $this->order_id,
            'order_grower_id' => $this->id,
            'food_listing_id' => $FoodListing->id,
            'quantity' => $quantity
        ]);

        $this->load_food_listings();
    }

    /**
     * Called when the cart is loaded or modified to make sure we have the seller's latest prices.
     */
    public function sync_food_listing_prices() {
        foreach ($this->FoodListings as $FoodListing) {
            $FoodListing->sync_prices();
        }
    }

    /**
     * When a buyer sets the exchange method (delivery, pickup, meetup) they want to use for this grower
     * in their order, we set some fundamental values here.
     *
     * Call this via `Order->set_exchange_method()` so the cart is updated appropriately.
     *
     * @param string $type Either `delivery`, `pickup`, or `meetup`
     * @param int|null $delivery_settings_id Which delivery setting is being used, if applicable
     * @param int|null $user_address_id Buyer's shipping address if opting for delivery
     * @param int|null $meetup_settings_id Which meetup setting is being used, if applicable
     * @throws \Exception If delivery is out of range or addresses couldn't be found
     */
    public function set_exchange_method($type, $delivery_settings_id = null, $user_address_id = null, $meetup_settings_id = null) {
        $type = strtolower($type);

        if ($type == 'delivery') {
            $results = $this->DB->run('
                SELECT latitude AS lat1, longitude AS lon1 
                FROM user_addresses 
                WHERE id = :user_address_id

                UNION

                SELECT latitude AS lat2, longitude AS lon2 
                FROM grower_operation_addresses 
                WHERE grower_operation_id = :grower_operation_id
            ');

            if (!isset($results[0]['lat1']) || !isset($results[0]['lon1']) || !isset($results[0]['lat2']) || !isset($results[0]['lon2'])) {
                throw new \Exception('Could not find addresses.');
            }

            $distance = getDistance(
                ['lat' => $results[0]['lat1'], 'lon' = $results[0]['lon1']], 
                ['lat' => $results[0]['lat2'], 'lon' = $results[0]['lon2']]
            );

            // Validate distance
            $delivery_results = $this->DB->run('SELECT * FROM delivery_settings WHERE id = :id LIMIT 1', [
                'id' => $delivery_settings_id
            ]);

            // I'm assuming this is the max distance radius?
            if ($distance > $delivery_results[0]['distance']) {
                throw new \Exception('The grower does not deliver this far away.');
            }
        } else {
            $distance = 0;
            $delivery_settings_id = 0;
        }

        if ($type != 'meetup') {
            $meetup_settings_id = 0;
        }

        $this->DB->run('
            UPDATE order_growers 
            SET 
                exchange_type = :type,
                delivery_settings_id = :delivery_settings_id,
                distance = :distance
            WHERE id = :id
            LIMIT 1
        ', [
            'type' => $type,
            'delivery_settings_id' => $delivery_settings_id,
            'distance' => $distance,
            'meetup_settings_id' => $meetup_settings_id,
            'id' => $this->id
        ]);

        // Update class properties
        $this->exchange_type = $type;
        $this->delivery_settings_id = $delivery_settings_id;
        $this->distance = $distance;
        $this->meetup_settings_id = $meetup_settings_id;
    }

    /**
     * Given the exchange method selected for this grower in this order, calculate and save the 
     * exchange fee.
     */
    public function calculate_exchange_fee() {
        if (isset($this->exchange_type) && $this->exchange_type == 'delivery') {
            $results = $this->DB->run('SELECT * FROM delivery_settings WHERE id = :id LIMIT 1', [
                'id' => $this->delivery_settings_id
            ]);

            if ($distance > $results[0]['free_distance']) {
                $exchange_fee = $results[0]['fee'] * $distance;
            } else {
                $exchange_fee = 0;
            }            
        } else {
            $exchange_fee = 0;
        }
        
        $this->DB->run('
            UPDATE order_growers 
            SET 
                exchange_fee = :exchange_fee
            WHERE id = :id
            LIMIT 1
        ', [
            'exchange_fee' => $exchange_fee
            'id' => $this->id
        ]);

        // Update class properties
        $this->exchange_fee = $exchange_fee;
    }

    /**
     * Calculates the total price of all items in this order sold by this grower.  Call after calling
     * `calculate_exchange_fee()` and `sync_food_listing_prices()`.
     */
    public function calculate_total() {
        $subtotal = 0;

        foreach ($this->FoodListings as $FoodListing) {
            $subtotal += $FoodListing->total;
        }

        $total = $subtotal + $this->exchange_fee;

        $this->DB->run('
            UPDATE order_growers 
            SET 
                subtotal = :subtotal,
                total = :total
            WHERE id = :id
            LIMIT 1
        ', [
            'subtotal' => $subtotal,
            'total' => $total,
            'id' => $this->id
        ]);

        // Update class properties
        $this->subtotal = $subtotal;
        $this->total = $total;
    }
}