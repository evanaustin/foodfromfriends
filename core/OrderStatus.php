<?php
 
class OrderStatus extends Base {
    
    public
        $id,
        $placed_on,
        $expired_on,
        $rejected_on,
        $confirmed_on,
        $buyer_cancelled_on,
        $seller_cancelled_on,
        $fulfilled_on,
        $reported_on,
        $reviewed_on,
        $cleared_on,
        $voided_on;

    public
        $current;

    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'order_statuses';

        $this->class_dependencies = [
            'DB',
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);
            $this->classify();
        }
    }

    private function classify() {
        if (!isset($this->expired_on) && !isset($this->rejected_on) && !isset($this->confirmed_on)) {
            $this->current = 'not yet confirmed';
        } else if (isset($this->confirmed_on) && !isset($this->buyer_cancelled_on) && !isset($this->seller_cancelled_on) && !isset($this->fulfilled_on)) {
            $this->current = 'pending fulfillment';
        } else if (isset($this->rejected_on)) {
            $this->current = 'rejected';
        } else if (isset($this->expired_on)) {
            $this->current = 'expired';
        } else if (isset($this->seller_cancelled_on)) {
            $this->current = 'cancelled by seller';
        } else if (isset($this->buyer_cancelled_on)) {
            $this->current = 'cancelled by buyer';
        } else if (isset($this->fulfilled_on) && !isset($this->reviewed_on) && !isset($this->reported_on)) {
            $this->current = 'open for review';
        } else if (isset($this->reported_on) && !isset($cleared_on)) {
            $this->current = 'issue reported';
        } else if (isset($this->cleared_on)) {
            $this->current = 'completed';
        }
    }

    /**
     * Mark suborder as confirmed
     */
    public function confirm() {
        $this->confirmed_on = \Time::now();
        
        $this->update([
            'confirmed_on' => $this->confirmed_on
        ]);

        $this->current = 'pending fulfillment';
    }

    /**
     * Mark suborder as rejected
     */
    public function reject() {
        $this->rejected_on = \Time::now();
        
        $this->update([
            'rejected_on' => $this->rejected_on
        ]);

        $this->current = 'rejected';
    }

    /**
     * Mark suborder as expired
     */
    public function expire() {
        $this->expired_on = \Time::now();
        
        $this->update([
            'expired_on' => $this->expired_on
        ]);

        $this->current = 'expired';
    }

    /**
     * Mark suborder as cancelled by seller
     */
    public function seller_cancel() {
        $this->seller_cancelled_on = \Time::now();
        
        $this->update([
            'seller_cancelled_on' => $this->seller_cancelled_on
        ]);

        $this->current = 'cancelled by seller';
    }

    /**
     * Mark suborder as cancelled by buyer
     */
    public function buyer_cancel() {
        $this->buyer_cancelled_on = \Time::now();
        
        $this->update([
            'buyer_cancelled_on' => $this->buyer_cancelled_on
        ]);

        $this->current = 'cancelled by buyer';
    }
    
    /**
     * Mark suborder as fulfilled
     */
    public function fulfill() {
        $this->fulfilled_on = \Time::now();
        
        $this->update([
            'fulfilled_on' => $this->fulfilled_on
        ]);

        $this->current = 'open for review';
    }
    
    /**
     * Mark suborder as reviewed
     */
    public function review() {
        $this->reviewed_on = \Time::now();
        
        $this->update([
            'reviewed_on' => $this->reviewed_on
        ]);

        $this->current = 'completed';
    }

    /**
     * Mark suborder as reported
     */
    public function report() {
        $this->reported_on = \Time::now();
        
        $this->update([
            'reported_on' => $this->reported_on
        ]);

        $this->current = 'issue reported';
    }
    
    /**
     * Mark suborder as cleared
     */
    public function clear() {
        $this->cleared_on = \Time::now();
        
        $this->update([
            'cleared_on' => $this->cleared_on
        ]);
    }

    /**
     * Mark suborder as voided
     */
    public function void() {
        $this->voided_on = \Time::now();
        
        $this->update([
            'voided_on' => $this->voided_on
        ]);
    }
}