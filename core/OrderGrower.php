<?php
 
class Order extends Base {

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
     * Given the exchange method selected for this grower in this order, calculate the exchange fee.
     *
     * @todo Write this
     */
    public function calculate_exchange_fee() {

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