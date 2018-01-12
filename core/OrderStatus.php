<?php
 
class OrderStatus extends Base {
    
    public
        $id,
        $expired_on,
        $rejected_on,
        $confirmed_on,
        $buyer_cancelled_on,
        $seller_cancelled_on,
        $fulfilled_on,
        $reported_on,
        $reviewed_on,
        $cleared_on,
        $refunded_on; // ! this will probably go in a separate class/table

    public
        $status;

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
            $time_until = \Time::until($this->placed_on, '24 hours');
            
            if (!$time_until) {
                $this->status = 'just expired';
            } else {
                $this->status = 'not yet confirmed';
            }
        } else if (isset($this->expired_on)) {
            $this->status = 'expired';
        } else if (isset($this->rejected_on)) {
            $this->status = 'rejected';
        } else if (isset($this->confirmed_on) && !isset($this->fulfilled_on) && !isset($this->buyer_cancelled_on) && !isset($this->seller_cancelled_on)) {
            $this->status = 'pending fulfillment';
        } else if (isset($this->buyer_cancelled_on)) {
            $this->status = 'cancelled by buyer';
        } else if (isset($this->seller_cancelled_on)) {
            $this->status = 'cancelled by seller';
        } else if (isset($this->fulfilled_on) && !isset($this->cleared_on)) {
            $time_until = \Time::until($this->fulfilled_on, '3 days');
            
            if (!$time_until) {
                $this->clear();
            } else {
                $this->status = 'open for review';
            }
        } else if (isset($this->cleared_on)) {
            $this->status = 'complete';
        }
    }

    /**
     * Suborder autonatically expires 24 hours has passed since the order was placed
     * 
     * @todo Recalculate fees and payouts
     */
    public function expire() {
        $time_elapsed = \Time::elapsed($this->placed_on);
        
        if ($time_elapsed['diff']->days >= 1) {
            $this->expired_on = \Time::now();
            
            $this->update([
                'expired_on' => $this->expired_on
            ]);

            $this->status = 'expired';
        }
    }
    
    /**
     * Seller rejects a suborder
     * No penalties levied on either buyer or seller
     * 
     * @todo Recalculate fees and payouts
     */
    public function reject() {
        if ($this->status == 'not yet confirmed') {
            $this->rejected_on = \Time::now();
            
            $this->update([
                'rejected_on' => $this->rejected_on
            ]);
        } else {
            return false;
        }
    }
    
    /**
     * Seller confirms a suborder
     * Fulfillment process begins
     */
    public function confirm() {
        if ($this->status == 'not yet confirmed') {
            $this->confirmed_on = \Time::now();
            
            $this->update([
                'confirmed_on' => $this->confirmed_on
            ]);
        } else {
            throw new \Exception('Oops! You cannot confirm this order');
        }
    }

    /**
     * Buyer cancels a suborder
     * Penalties vary depending on confirmation status and exchange cancellation policy.
     * 
     * @condition Not fulfilled
     * 
     * @todo partial/full refunds determined by exchange cancellation policy
     */
    public function buyer_cancel() {
        if ($this->status == 'not yet confirmed' || $this->status == 'pending fulfillment') {
            $this->buyer_cancelled_on = \Time::now();
            
            $this->update([
                'buyer_cancelled_on' => $this->buyer_cancelled_on
            ]);

            if ($this->status == 'pending fulfillment') {
                $this->refund();
            }
        } else {
            throw new \Exception('Oops! You cannot cancel this order');
        }
    }
    
    /**
     * Seller cancels a suborder
     * 
     * @condition Confirmed
     * 
     * @todo Full refund is issued
     * @todo Penalty levied on seller
     */
    public function seller_cancel() {
        if ($this->status == 'pending fulfillment') {
            $this->seller_cancelled_on = \Time::now();
            
            $this->update([
                'seller_cancelled_on' => $this->seller_cancelled_on
            ]);

            $this->refund();
        } else {
            throw new \Exception('Oops! You cannot cancel this order');
        }
    }
    
    /**
     * Seller marks a suborder as fulfilled
     * Buyer review process begins
     * 
     * @condition Confirmed
     */
    public function fulfill() {
        if (isset($this->confirmed_on)) {
            $this->fulfilled_on = \Time::now();
            
            $this->update([
                'fulfilled_on' => $this->fulfilled_on
            ]);
        }
    }
    
    /**
     * Buyer reports an issue with a suborder
     * 
     * @condition[AND] Fulfilled
     * @condition[AND] Not yet reviewed
     * @condition[AND] Within 3 days of fulfillment
     * 
     * @todo Contact seller > refund or clear
     */
    public function report() {
        if (isset($this->fulfilled_on) && !isset($this->reviewed_on)) {
            $time_elapsed = \Time::elapsed($this->fulfilled_on);

            if ($time_elapsed['diff']->days < 3) {
                $this->reported_on = \Time::now();
                
                $this->update([
                    'reported_on' => $this->reported_on
                ]);
            }
        }      
    }
    
    /**
     * Buyer reviews a suborder (seller & items)
     * Clear suborder
     */
    public function review() {
        $this->reviewed_on = \Time::now();
        
        $this->update([
            'reviewed_on' => $this->reviewed_on
        ]);

        $this->clear();
    }
    
    /**
     * Suborder is cleared for payment and the eview period closes
     * 
     * @condition[AND]  Fulfilled
     * @condition[OR]   Fulfilled 3 days ago
     * @condition[AND]  Not reported
     * @condition[OR]   Reviewed
     * 
     * @todo Issue payout
     */
    public function clear() {
        if ($this->fulfilled_on) {
            $time_elapsed = \Time::elapsed($this->fulfilled_on);

            if (($time_elapsed['diff']->days >= 3 && !isset($this->reported_on)) || isset($this->reviewed_on)) {
                $this->cleared_on = \Time::now();
                
                $this->update([
                    'cleared_on' => $this->cleared_on
                ]);

                $this->status = 'complete';
            }
        }
    }

    /**
     * Issue a refund for a failed suborder
     * 
     * @todo Stock, fees, and payouts are recalculated
     * @note this will probably go in a separate class/table
     */
    public function refund() {
        $this->refunded_on = \Time::now();
        
        $this->update([
            'refunded_on' => $this->refunded_on
        ]);
    }
}