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

    public
        $package_type,
        $metric;
        
    function __construct($parameters) {
        $this->table = 'order_items';

        $this->class_dependencies = [
            'DB'
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);
            $this->populate_fully($this->id);
        }
    }

    private function populate_fully($id) {
        $results = $this->DB->run('
            SELECT 
                oi.*,
                pt.title    AS package_type,
                im.title    AS metric
            
            FROM order_items oi
            
            LEFT JOIN item_package_types pt
                ON pt.id    = oi.package_type_id
            
            LEFT JOIN item_metrics im
                ON im.id    = oi.metric_id
                
            WHERE oi.id = :id
        
            LIMIT 1
        ', [
            'id' => $id
        ]);

        if (!isset($results[0])) return false;

        foreach ($results[0] as $k => $v) $this->{$k} = $v; 
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
        
        $OrderGrower = new OrderGrower([
            'DB' => $this->DB,
            'id' => $this->order_grower_id
        ]);

        if (!$Item->quantity || isset($Item->archived_on)) {
            if (!isset($Item->archived_on)) {
                $this->add([
                    'buyer_account_id'  => $OrderGrower->buyer_account_id,
                    'item_id'           => $this->item_id,
                ], 'saved_items');
            }

            $order_items = count($OrderGrower->Items);

            $this->delete();

            if ($order_items == 1) {
                $OrderGrower->delete();
            }
        } else {
            $this->unit_price   = $Item->price;
            $this->package_type_id = $Item->package_type_id;
            $this->measurement  = $Item->measurement;
            $this->metric_id    = $Item->metric_id;
            $this->total        = $this->quantity * $Item->price;
    
            $this->update([
                'unit_price'    => $this->unit_price,
                'package_type_id' => $this->package_type_id,
                'measurement'   => $this->measurement,
                'metric_id'     => $this->metric_id,
                'total'         => $this->total
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