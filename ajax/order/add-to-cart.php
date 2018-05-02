<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'food-listing-id'	=> 'required|integer',
    'suborder-id'       => 'integer',
    'quantity'			=> 'required|integer',
    'exchange-option'	=> 'required|alpha'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'food-listing-id'	=> 'trim|sanitize_numbers',
	'suborder-id'	    => 'trim|sanitize_numbers',
    'quantity'			=> 'trim|sanitize_numbers',
    'exchange-option'	=> 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

if (!empty($suborder_id)) {
    $SubOrder = new OrderGrower([
        'DB' => $DB,
        'id' => $suborder_id
    ]);

    if ($SubOrder->buyer_account_id != $User->BuyerAccount->id) quit('You cannot edit this suborder');
}

try {
	$Order = new Order([
		'DB' => $DB
	]);

	$Order = $Order->get_cart($User->BuyerAccount->id);

	$Item = new FoodListing([
		'DB' => $DB,
		'id' => $food_listing_id
	]);

	if (isset($Order->Growers[$Item->grower_operation_id]->FoodListings[$Item->id])) {
		quit('This item is already in your basket');
	}

	$Seller = new GrowerOperation([
		'DB' => $DB,
		'id' => $Item->grower_operation_id
	],[
		'details' => true,
		'exchange' => true
	]);

    $proceed = true;

    if ($exchange_option  == 'delivery') {
        if (!isset($User->delivery_latitude) || !isset($User->delivery_longitude)) {
            quit('Please set your address <a href="' . PUBLIC_ROOT . 'dashboard/account/edit-profile/delivery-address">here</a>');
        }

        $geocode = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=' . $User->delivery_latitude . ',' . $User->delivery_longitude . '&destinations=' . $Seller->latitude . ',' . $Seller->longitude . '&key=' . GOOGLE_MAPS_KEY);
        $output = json_decode($geocode);
        $distance = explode(' ', $output->rows[0]->elements[0]->distance->text);
        
        $distance = round((($distance[1] == 'ft') ? $distance[0] / 5280 : $distance[0]), 4);
    
        if ($distance > $Seller->Delivery->distance) {
            quit("This seller delivers up to {$Seller->Delivery->distance} miles, but you are {$distance} miles away");
        }
    } else {
        $distance = 0;
    }
    
    $Order->add_to_cart($Seller, $exchange_option, $Item, $quantity);

	$OrderGrower = $Order->Growers[$Seller->id];

	$json['ordergrower'] = [
		'id'		=> $OrderGrower->id,
		'grower_id'	=> $OrderGrower->grower_operation_id,
		'name'		=> $Seller->name,
		'subtotal'	=> '$' . number_format($OrderGrower->total / 100, 2),
		'exchange'	=> ucfirst($OrderGrower->Exchange->type),
		'ex_fee'	=> (($OrderGrower->Exchange->fee > 0) ? '$' . number_format($OrderGrower->Exchange->fee / 100, 2) : 'Free')
	];

	$json['listing'] = [
        'id'		=> $Item->id,
        'link'      => $Seller->link . '/' . $Item->link,
		'name'		=> $Item->title,
		'quantity'	=> $Item->quantity,
		'filename'	=> $Item->filename,
		'ext'		=> $Item->ext
	];

	$Item = $OrderGrower->FoodListings[$Item->id];

	$json['item'] = [
		'id'		=> $Item->id,
		'quantity'	=> $Item->quantity,
		'subtotal'	=> '$' . number_format($Item->total / 100, 2)
	];

	$json['order'] = [
		'subtotal'	=> '$' . number_format($Order->subtotal / 100, 2),
		'ex_fee'    => '$' . number_format($Order->exchange_fees / 100, 2),
		'fff_fee'   => '$' . number_format($Order->fff_fee / 100, 2),
		'total'		=> '$' . number_format($Order->total / 100, 2)
	];
} catch (\Exception $e) {
	quit($e->getMessage());
}

echo json_encode($json);