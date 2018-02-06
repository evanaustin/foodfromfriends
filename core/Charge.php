<?php
 
class Charge extends Base {
    
    public
        $id,
        $subtotal,
        $fff_fee,
        $exchange_fees,
        $total,
        $stripe_charge_id,
        $authorized_on,
        $captured_on,
        $released_on;

    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'charges';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);

        if (isset($parameters['id'])) $this->configure_object($parameters['id']);
    }

    /**
     * Finishes step 2 of payment
     * Store `$this->captured_on`
     * 
     * Calls `Stripe->capture()` to capture payment for this order
     */
    public function capture() {
        // Capture payment
        $Stripe = new Stripe();
        $Stripe->capture_charge($this->stripe_charge_id, $this->total);

        $this->captured_on = \Time::now();

        // Save capture date
        $this->update([
            'captured_on' => $this->captured_on
        ]);
    }

    /**
     * Cancels step 2 of payment
     * Store `$this->released_on`
     * 
     * Calls `Stripe->refund()` to void payment for this order
     */
    public function release() {
        // Release payment
        $Stripe = new Stripe();
        $Stripe->refund($this->stripe_charge_id);

        $this->released_on = \Time::now();

        // Store release date
        $this->update([
            'released_on' => $this->released_on
        ]);
    }

}

?>