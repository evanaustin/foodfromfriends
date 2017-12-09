<?php
 
class OrderGrower extends Base {

    public
        $id,
        $order_id,
        $user_id,
        $grower_operation_id,
        $order_exchange_id,
        $distance,
        $subtotal,
        $exchange_fee,
        $total,
        $confirmed_on,
        $fulfilled_on,
        $rejected_on,
        $expired_on;

    public
        $Exchange,    
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
            $this->load_exchange();
            $this->load_food_listings();
        }
    }

    /**
     * Creates an array of every OrderGrower:OrderExchange pair for a given order.
     *
     * @param int $order_id
     * @return array Growers keyed by `grower_operation_id`!
     */
    public function load_for_order($order_id) {
        $results = $this->DB->run('
            SELECT id, grower_operation_id 
            FROM order_growers 
            WHERE order_id =:order_id
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
     * Finds the exchange for this grower in the current order and stores it in 
     * `$this->Exchange`.
     */
    public function load_exchange() {
        /* $Buyer = new User([
            'DB' => $this->DB,
            'id' => $this->user_id
        ]);

        $Seller = new GrowerOperation([
            'DB' => $this->DB,
            'id' => $this->grower_operation_id
        ]); */

        $this->Exchange = new OrderExchange([
            'DB' => $this->DB,
            'id' => $this->order_exchange_id,
            'buyer_id'  => $this->user_id,
            'seller_id' => $this->grower_operation_id
        ]);
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
            'order_id'          => $this->order_id,
            'order_grower_id'   => $this->id,
            'food_listing_id'   => $FoodListing->id,
            'quantity'          => $quantity
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
     * Calculates the total price of all items in this order sold by this grower. Call after calling
     * `sync_exchange_order()` and `sync_food_listing()`.
     */
    public function calculate_total() {
        $this->subtotal = 0;

        foreach ($this->FoodListings as $FoodListing) {
            $this->subtotal += $FoodListing->total;
        }

        $this->total = $this->subtotal + $this->Exchange->fee;

        // ? use Base function
        /* $this->DB->run('
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
        ]); */

        $this->update([
            'subtotal'  => $this->subtotal,
            'total'     => $this->total,
        ]);
    }

    /**
     * Marks the suborder as confirmed.
     */
    public function confirm() {
        $confirmed_on = \Time::now();

        $this->update([
            'confirmed_on' => $confirmed_on
        ]);

        $this->confirmed_on = $confirmed_on;
    }
    
    /**
     * Marks the suborder as rejected.
     */
    public function reject() {
        $rejected_on = \Time::now();

        $this->update([
            'rejected_on' => $rejected_on
        ]);

        $this->rejected_on = $rejected_on;
    }

    /**
     * Marks the items sold by this grower has having been fulfilled (given to the buyer).
     * 
     * @todo If this is the last suborder of an order to be fulfilled, mark the order as complete
     */
    public function mark_fulfilled() {
        $fulfilled_on = \Time::now();

        $this->update([
            'fulfilled_on' => $fulfilled_on
        ]);

        $this->fulfilled_on = $fulfilled_on;
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
                og.order_exchange_id,
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