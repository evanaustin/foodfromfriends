<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'stripe_token' => 'required',
	'user_address_id' => 'required|integer', // set it here?  use default address / let them choose?
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'stripe_token' => 'trim|sanitize_string',
	'user_address_id' => 'trim|sanitize_numbers'
]);

$prepared_data = $Gump->run($validated_data);

// Perform Stripe charge, mark order as paid, initialize payout data
// ----------------------------------------------------------------------------
try {
	// Charge in Stripe
	$stripe_transaction_id = 'xxx';

	// Mark order as paid
	$Order = new Order();
	$Order = $Order->get_cart($User->id);
	$Order->mark_paid($stripe_transaction_id);
} catch (\Exception $e) {
	quit($e->getMessage());
}



echo json_encode($json);