<?php
 
class OrderFoodListing extends Base {
    
    protected
        $class_dependencies,
        $DB;

    public
        $id,
        $order_id,
        $order_grower_id,
        $user_id,
        $food_listing_id,
        $unit_price,
        $unit_weight,
        $weight_units,
        $quantity,
        $total,
        $food_listing_rating_id;
        
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
     * @return array Food listings keyed by `food_listing_id`
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
                $listings[$result['food_listing_id']] = new OrderFoodListing([
                    'DB' => $this->DB,
                    'id' => $result['id']
                ]);
            }
        }

        return $listings;
    }

    /**
     * Changes the quantity of this item in the cart
     */
    public function modify_quantity($quantity) {
        $this->update([
            'quantity' => $quantity 
        ]);
    }

    /**
     * Called when the cart is loaded or modified to make sure we have the seller's latest prices and weights.
     */
    public function sync() {
        $FoodListing = new FoodListing([
            'DB' => $this->DB,
            'id' => $this->food_listing_id
        ]);
        
        if (!$FoodListing->is_available) {
            $this->add([
                'user_id'           => $this->user_id,
                'food_listing_id'   => $this->food_listing_id,
            ], 'saved_items');

            $this->delete();
        } else {
            $this->unit_price   = $FoodListing->price;
            $this->unit_weight  = $FoodListing->weight;
            $this->weight_units = $FoodListing->units;
            $this->total        = $this->quantity * $FoodListing->price;
    
            $this->update([
                'unit_price'    => $this->unit_price,
                'unit_weight'   => $this->unit_weight,
                'weight_units'  => $this->weight_units,
                'total'         => $this->total,
            ]);
        }
    }

    /**
     * Record the item's rating
     * Store rating ID in order_food_listing record
     * 
     * @param int $buyer_id The buyer's user ID
     * @param int $score The buyer's numerical score for the item
     * @param text $review The buyer's written review of the item
     */
    public function rate($buyer_id, $score, $review) {
        $item_rating = $this->add([
            'food_listing_id' => $this->food_listing_id,
            'user_id'   => $buyer_id,
            'score'     => $score,
            'review'    => $review
        ], 'food_listing_ratings');

        $this->update([
            'food_listing_rating_id' => $item_rating['last_insert_id']
        ]);

        $this->food_listing_rating_id = $item_rating['last_insert_id'];

        $results = $this->DB->run('
            SELECT AVG(score) AS average
            FROM food_listing_ratings
            WHERE food_listing_id=:food_listing_id
        ',[
            'food_listing_id' => $this->food_listing_id
        ]);

        $this->update([
            'average_rating' => $results[0]['average']
        ], 'id', $this->food_listing_id, 'food_listings');
    }
}