<?php
 
class OrderItem extends Base {
    
    protected
        $class_dependencies,
        $DB;

    public
        $id,
        $order_id,
        $order_grower_id,
        $buyer_account_id,
        $item_id,
        $unit_price,
        $unit_weight,
        $weight_units,
        $quantity,
        $total,
        $item_rating_id;
        
    function __construct($parameters) {
        $this->table = 'order_items';

        $this->class_dependencies = [
            'DB'
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);
        }
    }

    /**
     * Creates an array of every `order_items` record for a given `order_grower`.
     *
     * @param int $order_grower_id
     * @return array Items keyed by `item_id`
     */
    public function load_for_grower($order_grower_id) {
        $results = $this->DB->run('
            SELECT id, item_id
            FROM order_items 
            WHERE order_grower_id = :order_grower_id
        ', [
            'order_grower_id' => $order_grower_id
        ]);

        $OrderItems = [];

        if (isset($results[0]['id'])) {
            foreach ($results as $result) {
                $OrderItems[$result['item_id']] = new OrderItem([
                    'DB' => $this->DB,
                    'id' => $result['id']
                ]);
            }
        }

        return $OrderItems;
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
        $Item = new Item([
            'DB' => $this->DB,
            'id' => $this->item_id
        ]);
        
        if (!$Item->is_available) {
            $OrderGrower = new OrderGrower([
                'DB' => $this->DB,
                'id' => $this->order_grower_id
            ]);

            $this->add([
                'buyer_account_id'  => $OrderGrower->buyer_account_id,
                'item_id'   => $this->item_id,
            ], 'saved_items');

            $this->delete();
        } else {
            $this->unit_price   = ($this->is_wholesale) ? $Item->wholesale_price : $Item->price;
            $this->unit_weight  = ($this->is_wholesale) ? $Item->wholesale_weight : $Item->weight;
            $this->weight_units = ($this->is_wholesale) ? $Item->wholesale_units : $Item->units;
            $this->total        = $this->quantity * ($this->is_wholesale ? $Item->wholesale_price : $Item->price);
    
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
     * Store rating ID in order_item record
     * 
     * @param int $buyer_account_id The buyer account's ID
     * @param int $score The buyer account's numerical score for the item
     * @param text $review The buyer account's written review of the item
     */
    public function rate($buyer_account_id, $score, $review) {
        $item_rating = $this->add([
            'item_id'   => $this->item_id,
            'buyer_account_id'  => $buyer_account_id,
            'score'             => $score,
            'review'            => $review
        ], 'item_ratings');

        $this->update([
            'item_rating_id' => $item_rating['last_insert_id']
        ]);

        $this->item_rating_id = $item_rating['last_insert_id'];

        $results = $this->DB->run('
            SELECT AVG(score) AS average
            FROM item_ratings
            WHERE item_id=:item_id
        ',[
            'item_id' => $this->item_id
        ]);

        $this->update([
            'average_rating' => $results[0]['average']
        ], 'id', $this->item_id, 'items');
    }
}