<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'food-listing-id'	=> 'required|integer',
    'quantity'			=> 'required|integer',
    'exchange-option'	=> 'required|alpha'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'food-listing-id'	=> 'trim|sanitize_numbers',
    'quantity'			=> 'trim|sanitize_numbers',
    'exchange-option'	=> 'trim|sanitize_string'
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

	if (isset($Order->Growers[$FoodListing->grower_operation_id]->FoodListings[$FoodListing->id])) {
		quit('This item is already in your basket');
	}

	$Seller = new GrowerOperation([
		'DB' => $DB,
		'id' => $FoodListing->grower_operation_id
	],[
		'details' => true,
		'exchange' => true
	]);

	$Order->add_to_cart($Seller, $exchange_option, $FoodListing, $quantity);

	$OrderGrower = $Order->Growers[$Seller->id];

	$json['ordergrower'] = [
		'id'		=> $OrderGrower->id,
		'name'		=> $Seller->details['name'],
		'subtotal'	=> '$' . number_format($OrderGrower->total / 100, 2),
		'exchange'	=> ucfirst($OrderGrower->Exchange->type),
		'ex_fee'	=> '$' . number_format($OrderGrower->Exchange->fee / 100, 2)
	];

	$json['listing'] = [
		'id'		=> $FoodListing->id,
		'name'		=> ucfirst((!empty($FoodListing->other_subcategory)) ? $FoodListing->other_subcategory : $FoodListing->subcategory_title),
		'quantity'	=> $FoodListing->quantity,
		'filename'	=> $FoodListing->filename,
		'ext'		=> $FoodListing->ext
	];

	$Item = $OrderGrower->FoodListings[$FoodListing->id];

	$json['item'] = [
		'id'		=> $Item->id,
		'quantity'	=> $Item->quantity,
		'subtotal'	=> '$' . number_format($Item->total / 100, 2)
	];

	$json['order'] = [
		'subtotal'	=> '$' . number_format($Order->subtotal / 100, 2),
		'ex_fee'		=> '$' . number_format($Order->exchange_fees / 100, 2),
		'fff_fee'		=> '$' . number_format($Order->fff_fee / 100, 2),
		'total'		=> '$' . number_format($Order->total / 100, 2)
	];
} catch (\Exception $e) {
	quit($e->getMessage());
}

echo json_encode($json);