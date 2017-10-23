<?php
 
class Payout extends Base {

    public 
        $LineItems;
    
    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'payouts';

        $this->class_dependencies = [
            'DB'
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);
            $this->load_line_items();
        }
    }

    /**
     * Finds this grower operation's current (unpaid) payout and returns it.
     *
     * If one doesn't exist, a new one will be created and returned.
     *
     * @param int $grower_operation_id
     * @return self
     */
    public function get_current_payout($grower_operation_id) {
        $results = $this->DB->run('
            SELECT id 
            FROM payouts 
            WHERE grower_operation_id = :grower_operation_id AND processed_on IS NULL
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0]['id'])) {
            $result = $this->add([
                'grower_operation_id' => $grower_operation_id,
            ]);

            $payout = $result['last_insert_id'];
        } else {
            $payout = $results[0]['id'];
        }

        return new Payout(['id' => $payout]);
    }

    /**
     * Finds all the line items to be paid out in this payout and assigns them to `$this->LineItems`.
     */
    public function load_line_items() {
        $LineItem = new PayoutLineItem();
        $this->LineItems = $LineItem->load_for_payout($this->id);
    }

    /**
     * Given order data, adds it all to the appropriate payout records.  Note that totals are not
     * calculated at this point.
     */
    public function save_order(Order $Order) {
        foreach ($Order->Growers as $OrderGrower) {
            // Fetches _or_ creates payout for this grower
            $Payout = $this->get_current_payout($OrderGrower->grower_operation_id);

            // Add line item for this `order_growers` record to the payout
            $Payout->add_line_item($OrderGrower->id, $OrderGrower->total);
        }
    }

    /**
     * Adds a line item to this payout
     */
    public function add_line_item($order_growers_id, $total) {
        $PayoutLineItem = new PayoutLineItem();
        $PayoutLineItem->add([
            'payout_id' => $this->id,
            'order_growers_id' => $order_growers_id,
            'total' => $total
        ]);
    }
}