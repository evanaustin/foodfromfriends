<?php
// namespace fff; // Stripe's own class is \Stripe, so this has to be namespaced.

/**
 * A wrapper for the Stripe SDK
 */
class StripeAPI {
    /**
     * Initialize Stripe with our API key.
     *
     * @todo Figure out how to set/load environment variables in FFF?
     */
    public function __construct() {
        \Stripe\Stripe::setApiKey(STRIPE_PK_TEST);
    }

    /**
     * Stripe exceptions are weird.  After retrieving the error message from the exception object, we throw a
     * simple `\Exception`.
     *
     * @throws \Exception Exception with Stripe's error message.
     */
    private function handle_stripe_exception($e) {
        $body = $e->getJsonBody();
        $err = $body['error'];
        $msg = $err['message'];
        error_log("Stripe API request failed: {$msg}");
        throw new \Exception($e->getMessage());//msg);
    }

    /**
     * Creates a customer in Stripe.
     *
     * @param int $user_id Customer's JRRRNL user ID
     * @param string $name Customer's name
     * @param string $email Customer's email address
     * @return \Stripe\Customer
     */
    public function create_customer($user_id, $name, $email) {
        try {
            $customer = \Stripe\Customer::create([
                'description' => "[{$user_id}] {$name}",
                'email' => $email
            ]);
            error_log('create customer');
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
    public function charge($stripe_customer_id, $stripe_card_id, $amount, $metadata = null) {
        try {
            $params = [
                'amount' => $amount,
                'currency' => 'usd',
                'customer' => $stripe_customer_id,
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
     * Retrieves all of a customer's cards.  Well, retrieves the first ten (Stripe-imposed
     * limit; they do have paging options.  See https://stripe.com/docs/api#list_cards).
     *
     * @param string $stripe_customer_id
     * @return array Array of \Stripe\Card objects
     */
    public function retrieve_cards($stripe_customer_id) {
        try {
            $cards = \Stripe\Customer::retrieve($stripe_customer_id)->sources->all([
  'limit' => 10, 'object' => 'card']);
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