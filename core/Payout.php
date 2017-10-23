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
            $this->calculate_totals();
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
     *
     * @param int $order_growers_id Which OrderGrower record this line item corresponds to
     * @param int $total Total price paid for goods from that OrderGrower (in cents)
     */
    public function add_line_item($order_growers_id, $total) {
        $PayoutLineItem = new PayoutLineItem();
        $PayoutLineItem->add([
            'payout_id' => $this->id,
            'order_growers_id' => $order_growers_id,
            'total' => $total
        ]);
    }

    /**
     * For making an adjustment prior to payout.  Adds a new line item not tied to any order of
     * an arbitrary (positive or negative) amount.
     *
     * @param int $amount Amount to adjust the payout total by (in cents)
     */
    public function make_adjustment($amount) {
        return $this->add_line_item(0, $amount);
    }

    /**
     * Returns an array of `Payout` objects.
     *
     * By default this method returns all payouts.  To return only unpaid payouts, pass in a 
     * `$subset` value of `unpaid`.  To return only paid payouts, pass in `paid`.
     *
     * @param string|null $subset Either `all`, `paid`, or `unpaid`.  Defaults to `all`.
     * @return array Array of `Order` objects
     */
    public function get_all($subset = 'all') {
        if ($subset == 'unpaid') {
            $where = 'WHERE processed_on IS NULL';
        } else if ($subset == 'paid') {
            $where = 'WHERE processed_on IS NOT NULL';
        } else {
            $where = '';
        }

        $results = $this->DB->run('
            SELECT id
            FROM payouts
            ' . $where . '
        ');

        $Payouts = [];

        foreach ($results as $result) {
            $Payouts []= new self(['id' => $result['id']]);
        }

        return $Payouts;
    }

    /**
     * Figures out the amounts owed to the grower and FFF based on the latest line items that've
     * been saved to this payout.  Important that we call this when the object is instantiated since
     * new line items may have been added since this result was last calculated.  Alternatively,
     * could calculate this every time a line item is added or removed. (should probably do that instead)
     */
    public function calculate_totals() {
        $amount_gross = 0;
        $fff_fee = 0;
        $amount_grower = 0;
        $amount_fff = 0;

        foreach ($this->LineItems as $LineItem) {
            $amount_gross += $LineItem->total;
        }

        $fff_fee = bcmul($amount_gross, 0.05);
        $amount_grower = $amount_gross - $fff_fee;
        $amount_fff = $amount_gross - $amount_grower;

        // Save totals to DB
        $this->DB->run('
            UPDATE payouts 
            SET 
                amount_gross = :amount_gross, 
                fff_fee = :fff_fee, 
                amount_grower = :fff_fee, 
                amount_fff = :total
            WHERE id = :id
            LIMIT 1
        ', [
            'amount_gross' => $amount_gross,
            'fff_fee' => $fff_fee,
            'amount_grower' => $amount_grower,
            'amount_fff' => $amount_fff,
            'id' => $this->id
        ]);

        // Update class properties
        $this->amount_gross = $amount_gross;
        $this->fff_fee = $fff_fee;
        $this->amount_grower = $amount_grower;
        $this->amount_fff = $amount_fff;
    }

    /**
     * Marks this payout as having been processed.
     */
    public function mark_processed() {
        $now = \Time::now();

        $this->DB->run('
            UPDATE payouts 
            SET 
                processed_on = :processed_on
            WHERE id = :id
            LIMIT 1
        ', [
            'processed_on' => $now,
            'id' => $this->id
        ]);

        $this->processed_on = $now;
    }

    /**
     * Transfers money as appropriate then marks the payout as having been processed
     *
     * @todo Write this
     */
    public function pay() {
        // 1. transfer amount_grower to grower's account
        
        // 2. transfer amount_fff to FFF operations account

        // 3. Mark as processed
        $this->mark_processed();

        // 4. Send email
    }
}