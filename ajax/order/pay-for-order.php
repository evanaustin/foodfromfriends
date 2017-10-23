<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'stripe_token' => 'required'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'stripe_token' => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

// Perform Stripe charge, mark order as paid, initialize payout data
// ----------------------------------------------------------------------------
try {
	// Load order
	$Order = new Order();
	$Order = $Order->get_cart($User->id);

	// Charge in Stripe
	$Stripe = new \fff\Stripe();
	$customer = $Stripe->create_customer($User->id, $User->first_name.' '.$User->last_name, $User->email);
	$card = $Stripe->create_card($customer->id, $prepared_data['stripe_token']);
	$charge = $Stripe->charge($customer->id, $card->id, $Order->total);

	// Mark order as paid
	$Order->mark_paid($charge->id);
} catch (\Exception $e) {
	quit($e->getMessage());
}



echo json_encode($json);