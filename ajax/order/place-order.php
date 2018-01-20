<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

use Treffynnon\At\Wrapper as At;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'stripe_token' => 'max_len,50',
    'stripe_card_id' => 'max_len,50'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'stripe_token' => 'trim|sanitize_string',
	'stripe_card_id' => 'trim|sanitize_string'
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

// Perform Stripe charge, mark order as paid, initialize payout data
// ----------------------------------------------------------------------------
try {
	// Load order
	$Order = new Order([
        'DB' => $DB
    ]);

	$Order = $Order->get_cart($User->id);
    
    // Hit Stripe API if we're not in dev
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

	// Mark order as paid
    $Order->mark_paid($charge_id);

    // Schedule system job for payment capture
    if (ENV != 'dev') {
        $job = 'wget -O - ' . PUBLIC_ROOT . 'cron/capture.php?order=' . $Order->id;
        $time = 'now + 6 days';
        At::cmd($job, $time);
    }
    
    foreach ($Order->Growers as $OrderGrower) {
        // Schedule system job for suborder expiration
        if (ENV != 'dev') {
            $job = 'wget -O - ' . PUBLIC_ROOT . 'cron/expire.php?order=' . $OrderGrower->id;
            $time = 'now + 1 day';
            At::cmd($job, $time);
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
                'toName'   => $Member->name,
                'toEmail'   => $Member->email
            ]);
            
            $Mail->new_order_notification($Member, $Seller, $OrderGrower, $User);
        }
    }
} catch (\Exception $e) {
	quit($e->getMessage());
}

echo json_encode($json);