<?php
 
class OrderFoodListing extends Base {
    
    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'order_food_listings';

        $this->class_dependencies = [
            'DB'
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);
        }
    }

    /**
     * Creates an array of every `order_food_listings` record for a given `order_grower`.
     *
     * @param int $order_grower_id
     * @return array Food listings keyed by `food_listing_id`!
     */
    public function load_for_grower($order_grower_id) {
        $results = $this->DB->run('
            SELECT id, food_listing_id
            FROM order_food_listings 
            WHERE order_grower_id = :order_grower_id
        ', [
            'order_grower_id' => $order_grower_id
        ]);

        $listings = [];

        if (isset($results[0]['id'])) {
            foreach ($results as $result) {
                $listings[$result['food_listing_id']] = new OrderFoodListing(['id' => $result['id']]);
            }
        }

        return $listings[];
    }

    /**
     * Changes the quantity of this item in the cart
     */
    public function modify_quantity($quantity) {
        $this->DB->run('UPDATE order_food_listings SET quantity = :quantity WHERE id = :id LIMIT 1', [
            'quantity' => $quantity,
            'id' => $this->id
        ]);
    }

    /**
     * Called when the cart is loaded or modified to make sure we have the seller's latest prices.
     */
    public function sync_prices() {
        $FoodListing = new FoodListing(['id' => $this->food_listing_id]);

        $this->DB->run('
            UPDATE order_food_listings
            SET unit_price = :unit_price, total = :total
            WHERE id = :id
            LIMIT 1
        ', [
            'unit_price' => $FoodListing->price,
            'total' => $this->quantity * $FoodListing->price,
            'id' => $this->id
        ]);

        // Update class properties
        $this->unit_price = $unit_price;
        $this->total = $total;
    }
}