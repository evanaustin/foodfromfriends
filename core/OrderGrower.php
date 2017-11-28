<?php
 
class OrderGrower extends Base {

    public
        $id,
        $order_id,
        $grower_operation_id,
        $exchange_option,
        $distance,
        $subtotal,
        $exchange_fee,
        $total,
        $confirmed_on,
        $fulfilled_on,
        $rejected_on,
        $expired_on;

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

        $Growers = [];

        if (isset($results[0]['id'])) {
            foreach ($results as $result) {
                $Growers[$result['grower_operation_id']] = new OrderGrower([
                    'DB' => $this->DB,
                    'id' => $result['id']
                ]);
            }
        }

        return $Growers;
    }

    /**
     * Finds all the food listings for this grower in the current order and stores them in 
     * `$this->FoodListings`.
     */
    public function load_food_listings() {
        $OrderFoodListing = new OrderFoodListing([
            'DB' => $this->DB
        ]);

        $this->FoodListings = $OrderFoodListing->load_for_grower($this->id);
    }

    /**
     * Adds a food listing to this OrderGrower and refreshes `$this->FoodListings`.  Don't worry
     * about `unit_price` and `amount` here; they're handled by the `Order->update_cart()` method.
     */
    public function add_food_listing(FoodListing $FoodListing, $quantity) {
        $this->add([
            'order_id' => $this->order_id,
            'order_grower_id' => $this->id,
            'food_listing_id' => $FoodListing->id,
            'quantity' => $quantity
        ], 'order_food_listings');

        $this->load_food_listings();
    }

    /**
     * Called when the cart is loaded or modified to make sure we have the seller's latest prices and weights.
     */
    public function sync_food_listing() {
        foreach ($this->FoodListings as $FoodListing) {
            $FoodListing->sync();
        }
    }

    /**
     * When a buyer sets the exchange method (delivery, pickup, meetup) they want to use for this grower
     * in their order, we set some fundamental values here.
     *
     * Call this via `Order->set_exchange_method()` so the cart is updated appropriately.
     *
     * @param string $exchange_option The exchange method being used
     * @param \User $Buyer The buyer
     * @param \GrowerOperation $GrowerOperation The seller
     * @throws \Exception If delivery is out of range or addresses couldn't be found
     */
    public function set_exchange_method($exchange_option, User $Buyer, GrowerOperation $GrowerOperation) {
        if ($exchange_option == 'delivery') {
            $geocode = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=' . $Buyer->latitude . ',' . $Buyer->longitude . '&destinations=' . $GrowerOperation->details['lat'] . ',' . $GrowerOperation->details['lng'] . '&key=' . GOOGLE_MAPS_KEY);
            $output = json_decode($geocode);
            $distance = explode(' ', $output->rows[0]->elements[0]->distance->text);
            $distance = round((($distance[1] == 'ft') ? $distance[0] / 5280 : $distance[0]), 4);
        } else {
            $distance = 0;
        }

        if ($distance > $GrowerOperation->Delivery->distance) {
            throw new \Exception('The grower does not deliver this far away');
        }
        
        $this->update([
            'exchange_option' => $exchange_option,
            'distance' => $distance
        ]);

        $this->exchange_option = $exchange_option;
        $this->distance = $distance;

        $this->calculate_exchange_fee();
    }

    /**
     * Given the exchange method selected for this grower in this order, calculate and save the 
     * exchange fee.
     */
    public function calculate_exchange_fee() {
        // Only delivery incurs a fee. For everything else, charge $0
        if ($this->exchange_option == 'delivery') {
            $GrowerOperation = new GrowerOperation([
                'DB' => $this->DB,
                'id' => $this->grower_operation_id
            ],[
                'exchange' => true
            ]);

            if ($this->distance > $GrowerOperation->Delivery->free_distance) {
                if ($GrowerOperation->Delivery->pricing_rate == 'per-mile') {
                    $exchange_fee = $GrowerOperation->Delivery->fee * ($this->distance - $GrowerOperation->Delivery->free_distance);
                } else {
                    $exchange_fee = $GrowerOperation->Delivery->fee;
                }
            } else {
                $exchange_fee = 0;
            }            
        } else {
            $exchange_fee = 0;
        }
        
        // Save the fee for this grower
        $this->update([
            'exchange_fee' => $exchange_fee
        ]);

        // Update class properties
        $this->exchange_fee = $exchange_fee;
    }

    /**
     * Calculates the total price of all items in this order sold by this grower.  Call after calling
     * `calculate_exchange_fee()` and `sync_food_listing()`.
     */
    public function calculate_total() {
        $subtotal = 0;

        foreach ($this->FoodListings as $FoodListing) {
            $subtotal += $FoodListing->total;
        }

        $total = $subtotal + $this->exchange_fee;

        // ? use Base function
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

    /**
     * Marks the suborder as confirmed.
     */
    public function confirm() {
        $now = \Time::now();

        $this->update([
            'confirmed_on' => $now
        ]);

        $this->confirmed_on = $now;
    }
    
    /**
     * Marks the suborder as rejected.
     */
    public function reject() {
        $now = \Time::now();

        $this->update([
            'rejected_on' => $now
        ]);

        $this->rejected_on = $now;
    }

    /**
     * Marks the items sold by this grower has having been fulfilled (given to the buyer).
     */
    public function mark_fulfilled() {
        $now = \Time::now();

        $this->update([
            'fulfilled_on' => $now
        ]);

        $this->fulfilled_on = $now;

        // ! if this is the last suborder of an order to be fulfilled, the order is now complete
    }

    /** 
     * Get all the new orders. An order is new if it has not been confirmed and not yet expired.
     * 
     * @param int $grower_operation_id The seller ID
     */
    public function get_new($grower_operation_id) {
        $results = $this->DB->run('
            SELECT 
                og.id,
                og.total,
                og.exchange_option,
                o.user_id,
                o.placed_on

            FROM order_growers og

            JOIN orders o
                on o.id = og.order_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND o.placed_on     IS NOT NULL
                AND og.confirmed_on IS NULL
                AND og.fulfilled_on IS NULL
                AND og.rejected_on  IS NULL
                AND og.expired_on   IS NULL
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }

    /** 
     * Get all the pending orders. An order is pending if it has been confirmed but not yet fulfilled.
     * 
     * @param int $grower_operation_id The seller ID
     */
    public function get_pending($grower_operation_id) {
        $results = $this->DB->run('
            SELECT 
                og.id,
                og.total,
                og.exchange_option,
                og.confirmed_on,
                o.user_id

            FROM order_growers og

            JOIN orders o
                on o.id = og.order_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND o.placed_on     IS NOT NULL
                AND og.confirmed_on IS NOT NULL
                AND og.fulfilled_on IS NULL
                AND og.rejected_on  IS NULL
                AND og.expired_on   IS NULL

            ORDER BY og.confirmed_on desc
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }

    /** 
     * Get all the completed orders. An order is complete if it has been confirmed and fulfilled.
     * 
     * @param int $grower_operation_id The seller ID
     */
    public function get_completed($grower_operation_id) {
        $results = $this->DB->run('
            SELECT 
                og.id,
                og.total,
                og.exchange_option,
                og.distance,
                og.fulfilled_on,
                o.user_id,
                o.placed_on

            FROM order_growers og

            JOIN orders o
                on o.id = og.order_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND o.placed_on IS NOT NULL
                AND og.confirmed_on IS NOT NULL
                AND og.fulfilled_on IS NOT NULL
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }
}