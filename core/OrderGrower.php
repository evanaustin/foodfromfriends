<?php
 
class OrderGrower extends Base {

    public
        $id,
        $order_id,
        $buyer_account_id,
        $grower_operation_id,
        $order_exchange_id,
        $order_status_id,
        $distance,
        $subtotal,
        $exchange_fee,
        $total,
        $grower_operation_rating_id;

    public
        $Exchange,
        $FoodListings,
        $Status;
    
    protected
        $class_dependencies,
        $DB;
        
    function __construct($parameters) {
        $this->table = 'order_growers';

        $this->class_dependencies = [
            'DB'
        ];

        parent::__construct($parameters);
    
        if (isset($parameters['id'])) {
            $this->configure_object($parameters['id']);
            $this->load_exchange();
            $this->load_food_listings();
            
            // only placed orders get their status loaded
            if (isset($this->order_status_id)) {
                $this->load_status();
            }
        }
    }

    /**
     * Creates an array of every OrderGrower:OrderExchange pair for a given order
     *
     * @param int $order_id
     * @return array Growers keyed by `grower_operation_id`
     */
    public function load_for_order($order_id) {
        $results = $this->DB->run('
            SELECT id, grower_operation_id 
            FROM order_growers 
            WHERE order_id =:order_id
        ', [
            'order_id' => $order_id
        ]);

        $Growers = [];

        if (isset($results[0]['id'])) {
            foreach ($results as $result) {
                $Growers[$result['grower_operation_id']] = new OrderGrower([
                    'DB' => $this->DB,
                    'id' => $result['id']
                ]);
            }
        }

        return $Growers;
    }

    /**
     * Finds the exchange for this grower in the current suborder and stores it in `$this->Exchange`
     */
    public function load_exchange() {
        $this->Exchange = new OrderExchange([
            'DB'                => $this->DB,
            'id'                => $this->order_exchange_id,
            'buyer_account_id'  => $this->buyer_account_id,
            'seller_id'         => $this->grower_operation_id
        ]);
    }

    /**
     * Finds all the OrderItems for this OrderGrower and stores them in this:FoodListings
     */
    public function load_food_listings() {
        $OrderFoodListing = new OrderFoodListing([
            'DB' => $this->DB
        ]);

        $this->FoodListings = $OrderFoodListing->load_for_grower($this->id);
    }

    /**
     * Finds the status for this grower in the current suborder and stores it in `$this->Status`
     */
    public function load_status() {
        $this->Status = new OrderStatus([
            'DB' => $this->DB,
            'id' => $this->order_status_id,
        ]);

        $this->Status->ordergrower_id = $this->id;
    }

    /**
     * Adds a food listing to this OrderGrower and refreshes `$this->FoodListings`
     * Don't worry about `unit_price` and `amount` here; they're handled by `Order->update_cart()`
     * @param FoodListing $FoodListing the Item being added to the cart
     * @param int @quantity the amount of the Item being added
     * @param int $buyer_account_id the ID of the BuyerAccount who's adding the Item ? Why do we need to store this ?
     */
    public function add_food_listing(FoodListing $FoodListing, $quantity, $buyer_account_id, $is_wholesale) {
        $this->add([
            'order_id'          => $this->order_id,
            'order_grower_id'   => $this->id,
            'buyer_account_id'  => $buyer_account_id,
            'food_listing_id'   => $FoodListing->id,
            'quantity'          => $quantity,
            'is_wholesale'      => $is_wholesale,
        ], 'order_food_listings');

        $this->load_food_listings();
    }

    /**
     * Called when the cart is loaded or modified to make sure we have the seller's latest prices and weights
     */
    public function sync_food_listing() {
        foreach ($this->FoodListings as $FoodListing) {
            $FoodListing->sync();
        }
    }

    /**
     * Calculates the total price of all items in this suborder sold by this grower
     * Call after calling `sync_exchange_order()` and `sync_food_listing()`
     */
    public function calculate_total() {
        $this->subtotal = 0;

        foreach ($this->FoodListings as $FoodListing) {
            $this->subtotal += $FoodListing->total;
        }

        $this->total = $this->subtotal + $this->Exchange->fee;

        $this->update([
            'subtotal'  => $this->subtotal,
            'total'     => $this->total,
        ]);
    }

    /**
     * Seller confirms a suborder
     * 
     * Calls `OrderGrower->Status->confirm()` to mark order as confirmed
     * Calls `Mail->confirmed_order_notification()` to send trans email to buyer
     */
    public function confirm() {
        if ($this->Status->current == 'not yet confirmed') {
            // Mark as confirmed
            $this->Status->confirm();

            // Send email notification
            $BuyerAccount = new BuyerAccount([
                'DB' => $this->DB,
                'id' => $this->buyer_account_id
            ], [
                'team' => true
            ]);
            
            $Seller = new GrowerOperation([
                'DB' => $this->DB,
                'id' => $this->grower_operation_id
            ]);
        
            foreach ($BuyerAccount->TeamMembers as $Member) {
                $Mail = new Mail([
                    'fromName'  => 'Food From Friends',
                    'fromEmail' => 'foodfromfriendsco@gmail.com',
                    'toName'    => $Member->name,
                    'toEmail'   => $Member->email
                ]);

                $Mail->confirmed_order_notification($Member, $BuyerAccount, $this, $Seller);
            }
        } else {
            throw new \Exception('Oops! You cannot confirm this order');
        }
    }

    /**
     * Seller rejects a suborder
     * 
     * Calls `OrderGrower->Status->reject()` to mark suborder as rejected
     * Calls `OrderGrower->void()` to void suborder
     * Calls `Mail->rejected_order_notification()` to send trans email to buyer
     */
    public function reject() {
        if ($this->Status->current == 'not yet confirmed') {
            // Mark as rejected
            $this->Status->reject();
            
            // Proceed to void
            $this->void();

            // Send email notification
            $BuyerAccount = new BuyerAccount([
                'DB' => $this->DB,
                'id' => $this->buyer_account_id
            ], [
                'team' => true
            ]);
            
            $Seller = new GrowerOperation([
                'DB' => $this->DB,
                'id' => $this->grower_operation_id
            ]);

            foreach ($BuyerAccount->TeamMembers as $Member) {
                $Mail = new Mail([
                    'fromName'  => 'Food From Friends',
                    'fromEmail' => 'foodfromfriendsco@gmail.com',
                    'toName'    => $BuyerAccount->name,
                    'toEmail'   => $BuyerAccount->email
                ]);
                
                $Mail->rejected_order_notification($Member, $BuyerAccount, $this, $Seller);
            }
        } else {
            throw new \Exception('Oops! You cannot reject this order');
        }
    }
    
    /**
     * Suborder expires 24 hours after order was placed if not responded to
     * 
     * Calls `OrderGrower->Status->expire()` to mark order as expired
     * Calls `OrderGrower->void()` to void payment
     * Calls `OrderGrower->penalize()` to penalize seller
     * Calls `Mail->expired_order_notification()` to send trans email to buyer
     */
    public function expire() {
        if ($this->Status->current == 'not yet confirmed') {
            // Mark as expired
            $this->Status->expire();

            // Proceed to void
            $this->void();
            
            // Proceed to penalize seller
            $this->penalize();

            // Send email notification
            $BuyerAccount = new BuyerAccount([
                'DB' => $this->DB,
                'id' => $this->buyer_account_id
            ], [
                'team' => true
            ]);
            
            $Seller = new GrowerOperation([
                'DB' => $this->DB,
                'id' => $this->grower_operation_id
            ]);
        
            foreach ($BuyerAccount->TeamMembers as $Member) {
                $Mail = new Mail([
                    'fromName'  => 'Food From Friends',
                    'fromEmail' => 'foodfromfriendsco@gmail.com',
                    'toName'    => $Member->name,
                    'toEmail'   => $Member->email
                ]);
                
                $Mail->expired_order_notification($Member, $BuyerAccount, $this, $Seller);
            }
        } else {
            throw new \Exception('This order cannot be expired');
        }
    }

    /**
     * Seller cancels a suborder
     * 
     * Calls `OrderGrower->Status->buyer_cancel()` to mark suborder as cancelled by seller
     * Calls `OrderGrower->void()` to void suborder
     * Calls `OrderGrower->penalize()` to penalize seller
     * Calls `Mail->seller_cancelled_order_notification()` to send trans email to buyer
     */
    public function seller_cancel() {
        if ($this->Status->current == 'pending fulfillment') {
            // Mark as cancelled by seller
            $this->Status->seller_cancel();
            
            // Proceed to void
            $this->void();

            // Proceed to penalize seller
            $this->penalize();

            // Send email notification
            $BuyerAccount = new BuyerAccount([
                'DB' => $this->DB,
                'id' => $this->buyer_account_id
            ], [
                'team' => true
            ]);
            
            $Seller = new GrowerOperation([
                'DB' => $this->DB,
                'id' => $this->grower_operation_id
            ]);
        
            foreach ($BuyerAccount->TeamMembers as $Member) {
                $Mail = new Mail([
                    'fromName'  => 'Food From Friends',
                    'fromEmail' => 'foodfromfriendsco@gmail.com',
                    'toName'    => $BuyerAccount->name,
                    'toEmail'   => $BuyerAccount->email
                ]);
                
                $Mail->seller_cancelled_order_notification($BuyerAccount, $this, $Seller);
            }
        } else {
            throw new \Exception('Oops! You cannot cancel this order');
        }
    }

    /**
     * Buyer cancels a suborder
     * 
     * Calls `OrderGrower->Status->buyer_cancel()` to mark suborder as cancelled by buyer
     * Calls `OrderGrower->void()` to void suborder
     * @todo Calls `OrderGrower->penalize()` to penalize seller
     * Calls `Mail->buyer_cancelled_order_notification()` to send trans email to seller
     */
    public function buyer_cancel() {
        if ($this->Status->current == 'not yet confirmed' || $this->Status->current == 'pending fulfillment') {
            // Mark as cancelled by buyer
            $this->Status->buyer_cancel();
            
            // Proceed to void
            $this->void();

            // Proceed to penalize buyer
            // $this->penalize();

            // Send email notification
            $BuyerAccount = new BuyerAccount([
                'DB' => $this->DB,
                'id' => $this->buyer_account_id
            ]);

            $Seller = new GrowerOperation([
                'DB' => $this->DB,
                'id' => $this->grower_operation_id
            ],[
                'team' => true
            ]);
    
            foreach ($Seller->TeamMembers as $Member) {
                $Mail = new Mail([
                    'fromName'  => 'Food From Friends',
                    'fromEmail' => 'foodfromfriendsco@gmail.com',
                    'toName'    => $Member->name,
                    'toEmail'   => $Member->email
                ]);
                
                $Mail->buyer_cancelled_order_notification($Member, $Seller, $this, $BuyerAccount);
            }
        } else {
            throw new \Exception('Oops! You cannot cancel this order');
        }
    }

    /**
     * Seller fulfills an order
     * 
     * Calls `OrderGrower->Status->fulfill()` to mark order as fulfilled
     * Calls `Mail->fulfilled_order_notification()` to send trans email to buyer
     */
    public function fulfill() {
        if ($this->Status->current == 'pending fulfillment') {
            // Mark as fulfilled
            $this->Status->fulfill();

            // Send email notification
            $BuyerAccount = new BuyerAccount([
                'DB' => $this->DB,
                'id' => $this->buyer_account_id
            ],[
                'team' => true
            ]);
            
            $Seller = new GrowerOperation([
                'DB' => $this->DB,
                'id' => $this->grower_operation_id
            ]);
        
            foreach ($BuyerAccount->TeamMembers as $Member) {
                $Mail = new Mail([
                    'fromName'  => 'Food From Friends',
                    'fromEmail' => 'foodfromfriendsco@gmail.com',
                    'toName'    => $Member->name,
                    'toEmail'   => $Member->email
                ]);
                
                $Mail->fulfilled_order_notification($Member, $BuyerAccount, $this, $Seller);
            }
        } else {
            throw new \Exception('Oops! You cannot mark this order as fulfilled');
        }
    }

    /**
     * Buyer reviews seller & items
     * 
     * Calls `OrderGrower->rate()` to rate the seller
     * Calls `OrderGrower->FoodListings->rate()` to rate each item
     * Calls `OrderGrower->Status->review()` to mark the order as reviewed
     * Calls `OrderGrower->clear()` to clear order
     * Calls `Mail->reviewed_order_notification()` to send trans email to seller
     * 
     * @param array $data The full data from the buyer's review
     */
    public function review($data) {
        if ($this->Status->current == 'open for review') {
            // Rate the seller
            $this->rate($data['seller-score'], $data['seller-review']);

            // Rate each item
            foreach ($data['items'] as $food_listing_id => $rating) {
                $this->FoodListings[$food_listing_id]->rate($this->buyer_account_id, $rating['score'], $rating['review']);
            }

            // Mark as reviewed
            $this->Status->review();

            // Proceed to clear
            $this->clear();

            // Send email notifications
            $BuyerAccount = new BuyerAccount([
                'DB' => $this->DB,
                'id' => $this->buyer_account_id
            ]);

            $Seller = new GrowerOperation([
                'DB' => $this->DB,
                'id' => $this->grower_operation_id
            ],[
                'team' => true
            ]);
        
            foreach ($Seller->TeamMembers as $Member) {
                $Mail = new Mail([
                    'fromName'  => 'Food From Friends',
                    'fromEmail' => 'foodfromfriendsco@gmail.com',
                    'toName'    => $Member->name,
                    'toEmail'   => $Member->email
                ]);
                
                $Mail->reviewed_order_notification($Member, $Seller, $this, $BuyerAccount);
            }
        } else {
            throw new \Exception('Oops! You cannot review this order');
        }
    }
    
    /**
     * Buyer reports issue with seller
     * 
     * Create `OrderIssue` record and tie to `OrderGrower`
     * Calls `OrderGrower->Status->report()` to mark the order as reported
     * Calls `Mail->reported_order_notification()` to send trans email to seller
     * 
     * @param array $data The full data from the buyer's report
     */
    public function report($data) {
        if ($this->Status->current == 'open for review') {
            // Create `OrderIssue` record
            $issue = $this->add([
                'message' => $data['message']
            ], 'order_issues');
            
            // Tie `OrderIssue` to `$this`
            $this->order_issue_id = $issue['last_insert_id'];

            $this->update([
                'order_issue_id' => $this->order_issue_id
            ]);

            // Mark as reported
            $this->Status->report();

            $BuyerAccount = new BuyerAccount([
                'DB' => $this->DB,
                'id' => $this->buyer_account_id
            ]);

            $Seller = new GrowerOperation([
                'DB' => $this->DB,
                'id' => $this->grower_operation_id
            ],[
                'team' => true
            ]);

            // Send admin email notification
            $Mail = new Mail([
                'fromName'  => 'Food From Friends',
                'fromEmail' => 'foodfromfriendsco@gmail.com',
                'toName'    => 'Evan Grinde',
                'toEmail'   => 'evan@foodfromfriends.co'
            ]);
            
            $Mail->reported_order_admin_notification($BuyerAccount, $Seller, $this, $data['message']);

            // Send seller email notifications
            foreach ($Seller->TeamMembers as $Member) {
                $Mail = new Mail([
                    'fromName'  => 'Food From Friends',
                    'fromEmail' => 'foodfromfriendsco@gmail.com',
                    'toName'    => $Member->name,
                    'toEmail'   => $Member->email
                ]);
                
                $Mail->reported_order_seller_notification($Member, $Seller, $this, $BuyerAccount);
            }
        } else {
            throw new \Exception('Oops! You cannot report this order');
        }
    }

    /**
     * Suborder is cleared for payout & review period closes
     * 
     * Calls `OrderGrower->Status->clear()` to mark order as voided
     */
    public function clear() {
        // Mark as cleared
        $this->Status->clear();

        // Initialize payout
        $Payout = new Payout([
            'DB' => $this->DB
        ]);

        $Payout->save_order($this);
    }

    /**
     * Void suborder
     * 
     * Update `$Order->Charge` with re-calculated amounts
     * Update `$this->FoodListings->quantity` to add back listing stock
     * Calls `OrderGrower->Status->void()` to mark order as voided
     */
    public function void() {
        $Order = new Order([
            'DB' => $this->DB,
            'id' => $this->order_id
        ]);

        // Re-calculate subtotal and exchange fee
        $subtotal       = $Order->Charge->subtotal - $this->subtotal;
        $exchange_fees  = $Order->Charge->exchange_fees - $this->Exchange->fee;

        // Re-calculate FFF fee
        $rate           = 0.1;
        $fff_fee        = round($subtotal * $rate);
        
        // Charge the greater of 10% and $0.50 if not $0
        $fff_fee        = (($fff_fee > 0) && ($fff_fee < 50)) ? 50 : $fff_fee;

        // Re-calculate total
        $total          = $subtotal + $exchange_fees + $fff_fee;

        // Update `$Order->Charge`
        $Order->Charge->update([
            'subtotal'      => $subtotal,
            'exchange_fees' => $exchange_fees,
            'fff_fee'       => $fff_fee,
            'total'         => $total
        ]);

        // Add back item stock
        foreach($this->FoodListings as $key => $OrderFoodListing) {
            $FoodListing = new FoodListing([
                'DB' => $this->DB,
                'id' => $key
            ]);

            $FoodListing->update([
                'quantity' => $FoodListing->quantity + $OrderFoodListing->quantity
            ]);
        }

        // Mark as void
        $this->Status->void();
    }

    /**
     * Penalize seller
     * Store rating ID in order_grower record
     * Recalculate & record seller's average rating
     */
    public function penalize() {
        $grower_rating = $this->add([
            'grower_operation_id'   => $this->grower_operation_id,
            'buyer_account_id'      => 0,
            'score'                 => 1,
        ], 'grower_operation_ratings');

        $this->update([
            'grower_operation_rating_id' => $grower_rating['last_insert_id']
        ]);

        $this->grower_operation_rating_id = $grower_rating['last_insert_id'];

        $results = $this->DB->run('
            SELECT AVG(score) AS average
            FROM grower_operation_ratings
            WHERE grower_operation_id=:grower_operation_id
        ',[
            'grower_operation_id' => $this->grower_operation_id
        ]);

        $this->update([
            'average_rating' => $results[0]['average']
        ], 'id', $this->grower_operation_id, 'grower_operations');
    }

    /**
     * Record the seller's rating
     * Store rating ID in order_grower record
     * Recalculate & record seller's average rating
     * 
     * @param int $score The buyer's numerical score for the seller
     * @param text $review The buyer's written review of the seller
     */
    public function rate($score, $review) {
        $grower_rating = $this->add([
            'grower_operation_id'   => $this->grower_operation_id,
            'buyer_account_id'      => $this->buyer_account_id,
            'score'                 => $score,
            'review'                => $review
        ], 'grower_operation_ratings');

        $this->update([
            'grower_operation_rating_id' => $grower_rating['last_insert_id']
        ]);

        $this->grower_operation_rating_id = $grower_rating['last_insert_id'];

        $results = $this->DB->run('
            SELECT AVG(score) AS average
            FROM grower_operation_ratings
            WHERE grower_operation_id=:grower_operation_id
        ',[
            'grower_operation_id' => $this->grower_operation_id
        ]);

        $this->update([
            'average_rating' => $results[0]['average']
        ], 'id', $this->grower_operation_id, 'grower_operations');
    }
    
    /** 
     * Get all the new orders
     * An order is new if it has not been confirmed and not yet expired.
     * 
     * @param int $grower_operation_id The seller ID
     */
    public function get_new($grower_operation_id) {
        $results = $this->DB->run('
            SELECT 
                og.id,
                og.total,
                og.order_exchange_id,
                o.buyer_account_id,
                os.placed_on

            FROM order_growers og

            JOIN orders o
                on o.id = og.order_id

            JOIN order_statuses os
                on os.id = og.order_status_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND os.placed_on    IS NOT NULL
                AND os.confirmed_on IS NULL
                AND os.rejected_on  IS NULL
                AND os.expired_on   IS NULL
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }

    /** 
     * Get all the pending orders
     * An order is pending if it has been confirmed but not yet fulfilled
     * 
     * @param int $grower_operation_id The seller ID
     */
    public function get_pending($grower_operation_id) {
        $results = $this->DB->run('
            SELECT 
                og.id,
                og.total,
                o.buyer_account_id,
                os.confirmed_on

            FROM order_growers og

            JOIN orders o
                on o.id = og.order_id
            
            JOIN order_statuses os
                on os.id = og.order_status_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND os.placed_on    IS NOT NULL
                AND os.expired_on   IS NULL
                AND os.rejected_on  IS NULL
                AND os.confirmed_on IS NOT NULL
                AND os.seller_cancelled_on IS NULL
                AND os.buyer_cancelled_on IS NULL
                AND os.fulfilled_on IS NULL

            ORDER BY os.confirmed_on desc
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }

    /** 
     * Get all the orders under review
     * An order is under review if it has not yet cleared 
     * 
     * @param int $grower_operation_id The seller ID
     */
    public function get_under_review($grower_operation_id) {
        $results = $this->DB->run('
            SELECT 
                og.id,
                og.total,
                o.buyer_account_id,
                os.placed_on,
                os.fulfilled_on

            FROM order_growers og

            JOIN orders o
                on o.id = og.order_id

            JOIN order_statuses os
                on os.id = og.order_status_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND os.fulfilled_on IS NOT NULL
                AND os.cleared_on   IS NULL
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }

    /** 
     * Get all the completed orders
     * An order is complete if it has been cleared
     * 
     * @param int $grower_operation_id The seller ID
     */
    public function get_completed($grower_operation_id) {
        $results = $this->DB->run('
            SELECT 
                og.id,
                og.total,
                o.buyer_account_id,
                os.placed_on,
                os.fulfilled_on

            FROM order_growers og

            JOIN orders o
                on o.id = og.order_id

            JOIN order_statuses os
                on os.id = og.order_status_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND os.cleared_on IS NOT NULL
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }
    
    /** 
     * Get all the voided orders
     * An order is void if it has expired, been rejected, or been cancelled
     * 
     * @param int $grower_operation_id The seller ID
     */
    public function get_failed($grower_operation_id) {
        $results = $this->DB->run('
            SELECT 
                og.id,
                og.total,
                o.buyer_account_id,
                os.placed_on,
                os.voided_on

            FROM order_growers og

            JOIN orders o
                on o.id = og.order_id

            JOIN order_statuses os
                on os.id = og.order_status_id

            WHERE og.grower_operation_id=:grower_operation_id 
                AND os.voided_on IS NOT NULL
        ', [
            'grower_operation_id' => $grower_operation_id
        ]);

        if (!isset($results[0])) {
            return false;
        }

        return $results;
    }
}