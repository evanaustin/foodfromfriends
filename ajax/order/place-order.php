<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

use Treffynnon\At\Wrapper as At;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'card_name'         => 'required|alpha_space',
    'address_line_1'    => 'required|alpha_numeric_space|max_len,35',
    'address_line_2'    => 'alpha_numeric_space|max_len,25',
    'city'              => 'required|alpha_space|max_len,35',
    'state'             => 'required|regex,/^[A-Z]{2}$/',
    'zipcode'           => 'required|regex,/^[0-9]{5}$/',
    'stripe_token'      => 'max_len,50',
    'stripe_card_id'    => 'max_len,50'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
    'card_name'         => 'trim|sanitize_string',
	'address_line_1'    => 'trim|sanitize_string',
	'address_line_2'    => 'trim|sanitize_string',
	'city'              => 'trim|sanitize_string',
	'state'             => 'trim|sanitize_string',
	'zipcode'           => 'trim|whole_number',
    'stripe_token'      => 'trim|sanitize_string',
	'stripe_card_id'    => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

// Require token OR card id
$new_card = true;

if (!isset($stripe_token) || empty($stripe_token)) {
	$new_card = false;

	if (!isset($stripe_card_id) || empty($stripe_card_id)) {
		quit('No payment method specified.');
	}
}

if ($User->exists('user_id', $User->id, 'user_billing_info')) {
    $updated = $User->update([
        'card_name'         => $card_name,
        'address_line_1'    => $address_line_1,
        'address_line_2'    => (isset($address_line_2) ? $address_line_2 : ''),
        'city'              => $city,
        'state'             => $state,
        'zipcode'           => $zipcode
    ], 'user_id', $User->id, 'user_billing_info');
    
    if (!$updated) quit('We could not use this billing information');
} else {
    $added = $User->add([
        'card_name'         => $card_name,
        'user_id'           => $User->id,
        'address_line_1'    => $address_line_1,
        'address_line_2'    => $address_line_2,
        'city'              => $city,
        'state'             => $state,
        'zipcode'           => $zipcode
    ], 'user_billing_info');
    
    if (!$added) quit('We could not add your billing information');
}

// Perform Stripe charge, mark order as paid, initialize payout data
// ----------------------------------------------------------------------------
try {
	// Load order
	$Order = new Order([
        'DB' => $DB
    ]);

	$Order = $Order->get_cart($User->id);
    
    // Authorize charge on Stripe
    if (ENV != 'dev') {
        $Stripe = new Stripe();
    
        // Create Stripe customer if user doesn't already have one
        if (!isset($User->stripe_customer_id) || empty($User->stripe_customer_id)) {
            $customer = $Stripe->create_customer($User->id, $User->first_name .' '. $User->last_name, $User->email);
            
            $User->update([
                'stripe_customer_id' => $customer->id
            ]);
        } else {
            $customer = $Stripe->retrieve_customer($User->stripe_customer_id);
        }
    
        // Create card if it's a new one; otherwise load the card the customer requested
        if ($new_card === true) {
            $card = $Stripe->create_card($customer->id, $stripe_token);
            $card_id = $card->id;
        } else {
            $card_id = $stripe_card_id;
        }
        
        // Authorize the charge for the customer
        $charge = $Stripe->charge($customer->id, $card_id, $Order->total);

        $charge_id = $charge->id;
    } else {
        $charge_id = 0;
    }

	// Change "cart" to "order"
    $Order->submit_payment($charge_id);

    // Schedule system job for payment capture
    if (ENV != 'dev') {
        $job    = 'wget -O - ' . PUBLIC_ROOT . 'scheduled/attempt-capture.php?order=' . $Order->id;
        $time   = 'now + 6 days';
        $queue  = 'a';
        At::cmd($job, $time, $queue);
    }
    
    foreach ($Order->Growers as $OrderGrower) {
        // Schedule system job for suborder expiration
        if (ENV != 'dev') {
            $job    = 'wget -O - ' . PUBLIC_ROOT . 'scheduled/expire.php?suborder=' . $OrderGrower->id;
            $time   = 'now + 1 day';
            $queue  = 'b';
            At::cmd($job, $time, $queue);
        }
        
        // Send new order notification emails to each team member of each seller
        $Seller = new GrowerOperation([
            'DB' => $DB,
            'id' => $OrderGrower->grower_operation_id
        ],[
            'details' => true,
            'team' => true
        ]);

        foreach ($Seller->TeamMembers as $Member) {
            $Mail = new Mail([
                'fromName'  => 'Food From Friends',
                'fromEmail' => 'foodfromfriendsco@gmail.com',
                'toName'    => $Member->name,
                'toEmail'   => $Member->email
            ]);
            
            $Mail->new_order_notification($Member, $Seller, $OrderGrower, $User);
        }
    }
} catch (\Exception $e) {
	quit($e->getMessage());
}

echo json_encode($json);