<?php
 
class Payout extends Base {

    protected
        $class_dependencies,
        $DB;
        
    public
        $id,
        $grower_operation_id,
        $amount_gross,
        $amount_grower,
        $amount_fff,
        $fff_fee,
        $stripe_charge_id,
        $processed_on;

    public 
        $LineItems;

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

    public function get_amount_paid($grower_operation_id) {
        $results = $this->DB->run('
            SELECT SUM(amount_grower) AS amount_paid
            FROM payouts
            WHERE grower_operation_id=:grower_operation_id
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results[0]['amount_paid'];
    }

    /**
     * Finds this grower operation's current (unpaid) payout and returns it
     * If one doesn't exist, a new one will be created and returned
     *
     * @param int $grower_operation_id
     * @return self
     */
    public function get_current_payout($grower_operation_id) {
        $results = $this->DB->run('
            SELECT id 
            FROM payouts 
            WHERE 
                grower_operation_id =:grower_operation_id 
                AND processed_on IS NULL
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0]['id'])) {
            $result = $this->add([
                'grower_operation_id' => $grower_operation_id,
            ]);

            $id = $result['last_insert_id'];
        } else {
            $id = $results[0]['id'];
        }

        $this->id = $id;

        error_log('constructed: ' . $this->id);
        
        return new Payout([
            'DB' => $this->DB,
            'id' => $this->id
        ]);
    }

    /**
     * Finds all the line items to be paid out in this payout and assigns them to `$this->LineItems`.
     */
    public function load_line_items() {
        $LineItem = new PayoutLineItem([
            'DB' => $this->DB
        ]);

        $this->LineItems = $LineItem->load_for_payout($this->id);
    }

    /**
     * Given order data, adds it all to the appropriate payout records. Note that totals are not
     * calculated at this point.
     */
    public function save_order(OrderGrower $OrderGrower) {
        // Fetches _or_ creates payout for this grower
        $Payout = $this->get_current_payout($OrderGrower->grower_operation_id);
        error_log('got current payout');
        // Add line item for this `order_growers` record to the payout
        $Payout->add_line_item($OrderGrower->id, $OrderGrower->total);
    }

    /**
     * Adds a line item to this payout
     *
     * @param int $order_grower_id Which OrderGrower record this line item corresponds to
     * @param int $total Total price paid for goods from that OrderGrower (in cents)
     */
    public function add_line_item($order_grower_id, $total) {
        error_log('adding line item...');
        $PayoutLineItem = new PayoutLineItem([
            'DB' => $this->DB
        ]);

        $result = $PayoutLineItem->add([
            'payout_id'         => $this->id,
            'order_grower_id'  => $order_grower_id,
            'total'             => $total
        ]);
        
        $this->LineItems []= new PayoutLineItem([
            'DB' => $this->DB,
            'id' => $result['last_insert_id']
            ]);
            
        error_log(json_encode($this->LineItems));

        $this->calculate_totals();
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
            $Payouts []= new self([
                'DB' => $this->DB,
                'id' => $result['id']
            ]);
        }

        return $Payouts;
    }

    /**
     * Figures out the amounts owed to the grower and FFF based on the latest line items that've
     * been saved to this payout. Important that we call this when the object is instantiated since
     * new line items may have been added since this result was last calculated. Alternatively,
     * could calculate this every time a line item is added or removed. (should probably do that instead)
     */
    public function calculate_totals() {
        $this->amount_gross     = 0;
        $this->fff_fee          = 0;
        $this->amount_grower    = 0;
        $this->amount_fff       = 0;

        foreach ($this->LineItems as $LineItem) {
            $this->amount_gross += $LineItem->total;
        }

        error_log('gross: ' . $this->amount_gross);

        $this->fff_fee        = round($this->amount_gross * 0.05);
        $this->amount_grower  = $this->amount_gross - $this->fff_fee;
        $this->amount_fff     = $this->amount_gross - $this->amount_grower;

        error_log($this->id);

        $updated = $this->update([
            'amount_gross'  => $this->amount_gross,
            'fff_fee'       => $this->fff_fee,
            'amount_grower' => $this->amount_grower,
            'amount_fff'    => $this->amount_fff
        ]);

        /* $result = $this->DB->run('
            UPDATE payouts 
            SET 
                amount_gross    =:amount_gross, 
                fff_fee         =:fff_fee, 
                amount_grower   =:fff_fee, 
                amount_fff      =:total
            WHERE id =:id
            LIMIT 1
        ', [
            'amount_gross'  => $amount_gross,
            'fff_fee'       => $fff_fee,
            'amount_grower' => $amount_grower,
            'amount_fff'    => $amount_fff,
            'id'            => $this->id
        ]); */

        error_log(json_encode($updated));
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