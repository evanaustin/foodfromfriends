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
                $this->expire();
            } else {
                $this->status = 'not yet confirmed';
            }
            
        } else if (isset($this->expired_on)) {
            $this->status = 'expired';
            
        } else if (isset($this->rejected_on)) {
            $this->status = 'rejected';
            
        } else if (isset($this->confirmed_on) && !isset($this->fulfilled_on)) {
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
     * @condition[AND] Not rejected
     * @condition[AND] Not confirmed
     * 
     * @todo Full refund is issued
     * @todo Fees and payouts are recalculated
     * @todo Penalty levied on seller
     */
    public function expire() {
        if (!isset($this->rejected_on) && !isset($this->confirmed_on)) {
            $time_elapsed = \Time::elapsed($this->placed_on);
            error_log(json_encode($time_elapsed));
            
            if ($time_elapsed['diff']->days >= 1) {
                $this->expired_on = \Time::now();
                error_log(json_encode($this->expired_on));
                
                $this->update([
                    'expired_on' => $this->expired_on
                ]);

                $this->status = 'expired';
            }
        }
    }
    
    /**
     * Seller rejects an OrderGrower
     * No penalties levied on either buyer or seller
     * 
     * @condition[AND] Not expired
     * @condition[AND] Not confirmed
     * 
     * @todo Full refund is issued
     * @todo Fees and payouts are recalculated
     */
    public function reject() {
        if (!isset($this->expired_on) && !isset($this->confirmed_on)) {
            $this->rejected_on = \Time::now();
            
            $this->update([
                'rejected_on' => $this->rejected_on
            ]);
        }
    }
    
    /**
     * Seller confirms an suborder
     * Fulfillment process begins
     * 
     * @condition[AND] Not expired
     * @condition[AND] Not rejected
     */
    public function confirm() {
        if (!isset($this->expired_on) && !isset($this->rejected_on)) {
            $this->confirmed_on = \Time::now();
            
            $this->update([
                'confirmed_on' => $this->confirmed_on
            ]);
        }
    }

    /**
     * Buyer cancels a suborder
     * Penalties vary depending on confirmation status and exchange cancellation policy.
     * 
     * @todo full refund if suborder unconfirmed
     * @todo partial/full refunds determined by exchange cancellation policy
     * 
     * @todo penalize buyer for cancellation
     */
    public function buyer_cancel() {
        $this->buyer_cancelled_on = \Time::now();
        
        $this->update([
            'buyer_cancelled_on' => $this->buyer_cancelled_on
        ]);
    }
    
    /**
     * Seller cancels a suborder
     * 
     * @condition Confirmed
     * 
     * @todo Full refund is issued
     * @todo Fees and payouts are recalculated
     * @todo Penalty levied on seller
     */
    public function seller_cancel() {
        if (isset($this->confirmed_on)) {
            $this->seller_cancelled_on = \Time::now();
            
            $this->update([
                'seller_cancelled_on' => $this->seller_cancelled_on
            ]);
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
     * @note this will probably go in a separate class/table
     */
    public function refund() {
        $this->refunded_on = \Time::now();
        
        $this->update([
            'refunded_on' => $this->refunded_on
        ]);
    }
}