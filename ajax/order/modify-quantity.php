<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'food-listing-id' => 'required|integer',
    'quantity' => 'required|integer'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'food-listing-id' => 'trim|sanitize_numbers',
    'quantity' => 'trim|sanitize_numbers'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

// Add to cart
// ----------------------------------------------------------------------------
try {
	$Order = new Order([
		'DB' => $DB
	]);

	$Order = $Order->get_cart($User->id);

	$FoodListing = new FoodListing([
		'DB' => $DB,
		'id' => $food_listing_id
	]);

	if (!isset($Order->Growers[$FoodListing->grower_operation_id]->FoodListings[$FoodListing->id])) {
		quit('This item is not in your basket');
	}

	$Order->modify_quantity($FoodListing, $quantity);

	$Item = $Order->Growers[$FoodListing->grower_operation_id]->FoodListings[$FoodListing->id];

	$json['item'] = [
		'quantity'	=> $Item->quantity,
		'subtotal'	=> '$' . number_format($Item->total / 100, 2)
	];

	$json['order'] = [
		'subtotal'	=> '$' . number_format($Order->subtotal / 100, 2),
		'ex_fee'	=> '$' . number_format($Order->exchange_fees / 100, 2),
		'fff_fee'	=> '$' . number_format($Order->fff_fee / 100, 2),
		'total'		=> '$' . number_format($Order->total / 100, 2)
	];
} catch (\Exception $e) {
	quit($e->getMessage());
}



echo json_encode($json);