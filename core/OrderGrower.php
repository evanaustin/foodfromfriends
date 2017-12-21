<?php
 
class OrderGrower extends Base {

    public
        $id,
        $order_id,
        $user_id,
        $grower_operation_id,
        $order_exchange_id,
        $order_status_id,
        $distance,
        $subtotal,
        $exchange_fee,
        $total,
        $grower_operation_rating_id;

    public
        $Exchange,
        $FoodListings,
        $Status;
    
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
            
            // only placed orders get their status loaded
            if (isset($this->order_status_id)) {
                $this->load_status();
            }
        }
    }

    /**
     * Creates an array of every OrderGrower:OrderExchange pair for a given order
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
     * Finds the exchange for this grower in the current order and stores it in `$this->Exchange`.
     */
    public function load_exchange() {
        $this->Exchange = new OrderExchange([
            'DB' => $this->DB,
            'id' => $this->order_exchange_id,
            'buyer_id'  => $this->user_id,
            'seller_id' => $this->grower_operation_id
        ]);
    }

    /**
     * Finds all the food listings for this grower in the current order and stores them in `$this->FoodListings`.
     */
    public function load_food_listings() {
        $OrderFoodListing = new OrderFoodListing([
            'DB' => $this->DB
        ]);

        $this->FoodListings = $OrderFoodListing->load_for_grower($this->id);
    }

    /**
     * Finds the status for this grower in the current order and stores it in `$this->Status`.
     */
    public function load_status() {
        $this->Status = new OrderStatus([
            'DB' => $this->DB,
            'id' => $this->order_status_id,
        ]);
    }

    /**
     * Adds a food listing to this OrderGrower and refreshes `$this->FoodListings`
     * Don't worry about `unit_price` and `amount` here; they're handled by `Order->update_cart()`
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
     * Called when the cart is loaded or modified to make sure we have the seller's latest prices and weights
     */
    public function sync_food_listing() {
        foreach ($this->FoodListings as $FoodListing) {
            $FoodListing->sync();
        }
    }

    /**
     * Calculates the total price of all items in this order sold by this grower
     * Call after calling `sync_exchange_order()` and `sync_food_listing()`.
     */
    public function calculate_total() {
        $this->subtotal = 0;

        foreach ($this->FoodListings as $FoodListing) {
            $this->subtotal += $FoodListing->total;
        }

        $this->total = $this->subtotal + $this->Exchange->fee;

        $this->update([
            'subtotal'  => $this->subtotal,
            'total'     => $this->total,
        ]);
    }

    /**
     * Calls `OrderGrower->rate()` to rate the seller
     * Calls `OrderGrower->FoodListings->rate()` to rate each item
     * Calls `OrderGrower->Status->review()` to mark the order as reviewed
     * 
     * @param array $data The full data from the buyer's review
     */
    public function review($data) {
        $this->rate($data['seller-score'], $data['seller-review']);

        foreach ($data['items'] as $food_listing_id => $rating) {
            $this->FoodListings[$food_listing_id]->rate($this->user_id, $rating['score'], $rating['review']);
        }

        $this->Status->review();
    }

    /**
     * Record the seller's rating
     * Store rating ID in order_grower record
     * Re-calculate & record seller's average rating
     * 
     * @param int $score The buyer's numerical score for the seller
     * @param text $review The buyer's written review of the seller
     */
    public function rate($score, $review) {
        $grower_rating = $this->add([
            'grower_operation_id' => $this->grower_operation_id,
            'user_id'   => $this->user_id,
            'score'     => $score,
            'review'    => $review
        ], 'grower_operation_ratings');

        $this->update([
            'grower_operation_rating_id' => $grower_rating['last_insert_id']
        ]);

        $this->grower_operation_rating_id = $grower_rating['last_insert_id'];

        $results = $this->DB->run('
            SELECT AVG(score) AS average
            FROM grower_operation_ratings
            WHERE grower_operation_id=:grower_operation_id
        ',[
            'grower_operation_id' => $this->grower_operation_id
        ]);

        $this->update([
            'average_rating' => $results[0]['average']
        ], 'id', $this->grower_operation_id, 'grower_operations');
    }

    /** 
     * Get all the new orders
     * An order is new if it has not been confirmed and not yet expired.
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

            JOIN order_statuses os
                on os.id = og.order_status_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND os.placed_on    IS NOT NULL
                AND os.expired_on   IS NULL
                AND os.rejected_on  IS NULL
                AND os.confirmed_on IS NULL
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }

    /** 
     * Get all the pending orders
     * An order is pending if it has been confirmed but not yet fulfilled
     * 
     * @param int $grower_operation_id The seller ID
     */
    public function get_pending($grower_operation_id) {
        $results = $this->DB->run('
            SELECT 
                og.id,
                og.total,
                o.user_id,
                os.confirmed_on

            FROM order_growers og

            JOIN orders o
                on o.id = og.order_id
            
            JOIN order_statuses os
                on os.id = og.order_status_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND os.placed_on    IS NOT NULL
                AND os.expired_on   IS NULL
                AND os.rejected_on  IS NULL
                AND os.confirmed_on IS NOT NULL
                AND os.fulfilled_on IS NULL

            ORDER BY os.confirmed_on desc
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }

    /** 
     * Get all the orders under review
     * An order is under review if it has not yet cleared 
     * 
     * @param int $grower_operation_id The seller ID
     */
    public function get_under_review($grower_operation_id) {
        $results = $this->DB->run('
            SELECT 
                og.id,
                og.total,
                o.user_id,
                o.placed_on,
                os.fulfilled_on

            FROM order_growers og

            JOIN orders o
                on o.id = og.order_id

            JOIN order_statuses os
                on os.id = og.order_status_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND os.fulfilled_on IS NOT NULL
                AND os.cleared_on   IS NULL
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }

    /** 
     * Get all the completed orders
     * An order is complete if it has been cleared
     * 
     * @param int $grower_operation_id The seller ID
     */
    public function get_completed($grower_operation_id) {
        $results = $this->DB->run('
            SELECT 
                og.id,
                og.total,
                o.user_id,
                o.placed_on,
                os.fulfilled_on

            FROM order_growers og

            JOIN orders o
                on o.id = og.order_id

            JOIN order_statuses os
                on os.id = og.order_status_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND os.cleared_on IS NOT NULL
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }
}