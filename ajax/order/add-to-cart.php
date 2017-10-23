<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'food_listing_id' => 'required|integer',
    'quantity' => 'required|integer'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'food_listing_id' => 'trim|sanitize_numbers',
    'quantity' => 'trim|sanitize_numbers'
]);

$prepared_data = $Gump->run($validated_data);

// Add to cart
// ----------------------------------------------------------------------------
try {
	$Order = new Order();
	$Order = $Order->get_cart($User->id);

	$FoodListing = new FoodListing(['id' => $prepared_data['food_listing_id']]);
	$GrowerOperation = new GrowerOperation(['id' => $FoodListing->grower_operation_id]);

	$Order->add_to_cart($GrowerOperation, $FoodListing, $prepared_data['quantity']);
} catch (\Exception $e) {
	quit($e->getMessage());
}



echo json_encode($json);