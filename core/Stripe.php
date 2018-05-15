<?php

/**
 * A wrapper for the Stripe SDK
 */
class Stripe {
    /**
     * Initialize Stripe with our API key.
     */
    public function __construct() {
        \Stripe\Stripe::setApiKey((ENV == 'prod' ? STRIPE_SK_LIVE : STRIPE_SK_TEST));
    }

    /**
     * After retrieving the error message from the exception object, throw `\Exception`.
     *
     * @throws \Exception Exception with Stripe's error message.
     */
    private function handle_stripe_exception($e) {
        $body = $e->getJsonBody();
        $err = $body['error'];
        $msg = $err['message'];
        error_log("Stripe API request failed: {$msg}");
        throw new \Exception($e->getMessage());
    }

    /**
     * Creates a customer in Stripe.
     *
     * @param int $buyer_account_id Customer's user ID
     * @param string $name Customer's name
     * @param string $email Customer's email address
     * @return \Stripe\Customer
     */
    public function create_customer($buyer_account_id, $name, $email) {
        try {
            $customer = \Stripe\Customer::create([
                'description' => "[{$buyer_account_id}] {$name}",
                'email' => $email
            ]);
        } catch (\Stripe\Error\RateLimit $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\InvalidRequest $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\Authentication $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\ApiConnection $e) {
            $this->handle_stripe_exception($e);
        }

        return $customer;
    }

    /**
     * Retrieves a customer from their Stripe ID
     *
     * @param string $stripe_customer_id
     * @return \Stripe\Customer
     */
    public function retrieve_customer($stripe_customer_id) {
        try {
            $customer = \Stripe\Customer::retrieve($stripe_customer_id);
        } catch (\Stripe\Error\RateLimit $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\InvalidRequest $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\Authentication $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\ApiConnection $e) {
            $this->handle_stripe_exception($e);
        }

        return $customer;
    }

    /**
     * Creates a card in Stripe.
     *
     * @param string $stripe_customer_id Customer's Stripe customer ID
     * @param array $stripe_token Card token from Stripe
     * @return \Stripe\Card
     */
    public function create_card($stripe_customer_id, $stripe_token) {
        try {
            $customer = \Stripe\Customer::retrieve($stripe_customer_id);

            // Create card and retrieve card object
            $card = $customer->sources->create([
                'source' => $stripe_token
            ]);
        } catch (\Stripe\Error\RateLimit $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\InvalidRequest $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\Authentication $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\ApiConnection $e) {
            $this->handle_stripe_exception($e);
        }

        return $card;
    }

    /**
     * Charges a card and returns the Stripe charge object.
     * 
     * @param string $stripe_customer_id Customer's Stripe customer ID
     * @param string $stripe_card_id ID of the card being used
     * @param int $amount Amount to charge (in cents)
     * @param array|null $metadata Extra data to store with the charge
     * @return \Stripe\Charge
     */
    public function charge($stripe_customer_id, $stripe_card_id, $amount, $capture = false, $metadata = null) {
        try {
            $params = [
                'amount' => $amount,
                'currency' => 'usd',
                'customer' => $stripe_customer_id,
                'capture' => $capture,
                'source' => $stripe_card_id
            ];

            if (isset($metadata, $metadata['description'])) {
                $params['description'] = $metadata['description'];
            }

            $charge = \Stripe\Charge::create($params);
        } catch (\Stripe\Error\RateLimit $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\InvalidRequest $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\Authentication $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\ApiConnection $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\Base $e) {
            $this->handle_stripe_exception($e);
        }

        return $charge;
    }

    /**
     * Captures an authorized charge and returns the Stripe charge object.
     * 
     * @param string $stripe_charge_id ID of the charge being captured
     * @param int $amount Amount to charge (in cents) - can be less than authorized amount
     * @return \Stripe\Charge
     */
    public function capture_charge($stripe_charge_id, $amount = null) {
        try {
            $charge = \Stripe\Charge::retrieve($stripe_charge_id);

            if (!isset($amount)) {
                $charge->capture();
            } else {
                $charge->capture([
                    'amount' => $amount
                ]);
            }
        } catch (\Stripe\Error\RateLimit $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\InvalidRequest $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\Authentication $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\ApiConnection $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\Base $e) {
            $this->handle_stripe_exception($e);
        }

        return $charge;
    }

    /**
     * Refunds a charge and returns the Stripe charge object.
     * 
     * @param string $stripe_charge_id ID of the charge being refunded
     * @param int $amount Amount to refund (in cents)
     * @return \Stripe\Refund
     */
    public function refund($stripe_charge_id, $amount = null) {
        try {
            $params = [
                'charge' => $stripe_charge_id
            ];

            if (isset($amount)) {
                $params['amount'] = $amount;
            }

            $refund = \Stripe\Refund::create($params);

        } catch (\Stripe\Error\RateLimit $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\InvalidRequest $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\Authentication $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\ApiConnection $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\Base $e) {
            $this->handle_stripe_exception($e);
        }

        return $refund;
    }

    /**
     * Creates a subscription and returns the Stripe subscription object.  Note
     * that we can't choose which card to put a subscription on -- it automatically
     * goes to the customer's "default" card within Stripe.
     *
     * @param string $plan Stripe plan key
     * @param string $stripe_customer_id Customer to subscribe
     * @param string $coupon Stripe coupon code to apply
     * @return \Stripe\Subscription
     */
    public function create_subscription($plan, $stripe_customer_id, $coupon = null) {
        try {
            $subscription = \Stripe\Subscription::create([
                'customer' => $stripe_customer_id,
                'coupon' => $coupon,
                'items' => [
                    ['plan' => $plan]
                ]
            ]);
        } catch (\Stripe\Error\RateLimit $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\InvalidRequest $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\Authentication $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\ApiConnection $e) {
            $this->handle_stripe_exception($e);
        }

        return $subscription;
    }

    /**
     * Cancels a subscription
     *
     * @param string $stripe_subscription_id
     * @return bool `true` if successful
     */
    public function cancel_subscription($stripe_subscription_id) {
        try {
            $subscription = \Stripe\Subscription::retrieve($stripe_subscription_id);
            $result = $subscription->cancel();

            if ($result['status'] == 'canceled') {
                return true;
            }
        } catch (\Stripe\Error\RateLimit $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\InvalidRequest $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\Authentication $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\ApiConnection $e) {
            $this->handle_stripe_exception($e);
        }

        return false;
    }

    /**
     * Retrieves a subscription from its Stripe ID
     *
     * @param string $stripe_subscription_id
     * @return \Stripe\Customer
     */
    public function retrieve_subscription($stripe_subscription_id) {
        try {
            $subscription = \Stripe\Subscription::retrieve($stripe_subscription_id);
        } catch (\Stripe\Error\RateLimit $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\InvalidRequest $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\Authentication $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\ApiConnection $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\Base $e) {
            $this->handle_stripe_exception($e);
        }

        return $subscription;
    }

    /**
     * Retrieves a customer's cards (ten at a time - a Stripe limit)
     * They do have paging options. See https://stripe.com/docs/api#list_cards).
     *
     * @param string $stripe_customer_id
     * @return array Array of \Stripe\Card objects
     */
    public function retrieve_cards($stripe_customer_id) {
        try {
            $cards = \Stripe\Customer::retrieve($stripe_customer_id)->sources->all([
                'limit' => 10,
                'object' => 'card'
            ]);
        } catch (\Stripe\Error\RateLimit $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\InvalidRequest $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\Authentication $e) {
            $this->handle_stripe_exception($e);
        } catch (\Stripe\Error\ApiConnection $e) {
            $this->handle_stripe_exception($e);
        }

        return $cards;
    }
}