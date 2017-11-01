<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'user_id'			=> 'required|integer',
	'food_listing_id'	=> 'required|integer',
    'quantity'			=> 'required|integer',
    'exchange_option'	=> 'required|alpha'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'user_id'			=> 'trim|sanitize_numbers',
	'food_listing_id'	=> 'trim|sanitize_numbers',
    'quantity'			=> 'trim|sanitize_numbers',
    'exchange_option'	=> 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

// Add to cart
// ----------------------------------------------------------------------------
try {
	$Order = new Order([
		'DB' => $DB
	]);

	$Order = $Order->get_cart($user_id);

	$FoodListing = new FoodListing([
		'DB' => $DB,
		'id' => $food_listing_id
	]);

	$GrowerOperation = new GrowerOperation([
		'DB' => $DB,
		'id' => $FoodListing->grower_operation_id
	]);

	$Order->add_to_cart($GrowerOperation, $FoodListing, $quantity);
} catch (\Exception $e) {
	quit($e->getMessage());
}

echo json_encode($json);