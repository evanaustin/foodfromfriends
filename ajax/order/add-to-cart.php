<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'seller-id'         => 'required|integer',
    'suborder-id'       => 'integer',
    'item-id'	        => 'required|integer',
    'quantity'			=> 'required|integer',
    'exchange-option'   => 'required|alpha',
    'distance-miles'    => 'integer',
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'seller-id'         => 'trim|sanitize_numbers',
	'suborder-id'	    => 'trim|sanitize_numbers',
	'item-id'	        => 'trim|sanitize_numbers',
    'quantity'			=> 'trim|sanitize_numbers',
    'exchange-option'	=> 'trim|sanitize_string',
    'distance-miles'    => 'trim|sanitize_numbers',
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;


/*
 * Initialize Seller, Item, & Order
 */

$Seller = new GrowerOperation([
    'DB' => $DB,
    'id' => $seller_id
],[
    'exchange' => true
]);

$FoodListing = new FoodListing([
    'DB' => $DB,
    'id' => $item_id
]);

$Order = new Order([
    'DB' => $DB
]);


/*
 * Ensure item being added to cart belongs to Seller
 */

if ($FoodListing->grower_operation_id != $Seller->id) {
    quit('You cannot add this item to your cart');
}


/*
 * Ensure suborder belongs to User:BuyerAccount
 */

if (!empty($suborder_id)) {
    $OrderGrower = new OrderGrower([
        'DB' => $DB,
        'id' => $suborder_id
    ]);

    if ($OrderGrower->buyer_account_id != $User->BuyerAccount->id) {
        quit('You cannot edit this suborder');
    }
}

/*
 * Retrieve or create cart
 */

$Order = $Order->get_cart($User->BuyerAccount->id);


/*
 * Ensure item is not already in cart
 */

if (isset($Order->Growers[$FoodListing->grower_operation_id]->FoodListings[$FoodListing->id])) {
    quit('This item is already in your basket');
}

/*
 * Handle delivery exchange selection
 */

if ($exchange_option  == 'delivery') {

    // ensure User:BuyerAccount:Address is set
    if (!isset($User->BuyerAccount->Address->latitude) || !isset($User->BuyerAccount->Address->longitude)) {
        quit('Please set your address <a href="' . PUBLIC_ROOT . 'dashboard/buying/settings/profile">here</a>');
    }

    // ensure delivery distance is in Seller's range
    if ($distance_miles > $Seller->Delivery->distance) {
        quit("This seller delivers up to {$Seller->Delivery->distance} miles, but you are {$distance_miles} miles away");
    }

} else {
    $distance = 0;
}


/*
 * Add Item to Order
 */

$Order->add_to_cart($Seller, $exchange_option, $FoodListing, $quantity);


/*
 * Prepare JSON
 */

$OrderGrower = $Order->Growers[$Seller->id];
$Item = $OrderGrower->FoodListings[$FoodListing->id];

$json['ordergrower'] = [
    'id'		=> $OrderGrower->id,
    'grower_id'	=> $OrderGrower->grower_operation_id,
    'name'		=> $Seller->name,
    'subtotal'	=> '$' . number_format($OrderGrower->total / 100, 2),
    'exchange'	=> ucfirst($OrderGrower->Exchange->type),
    'ex_fee'	=> (($OrderGrower->Exchange->fee > 0) ? '$' . number_format($OrderGrower->Exchange->fee / 100, 2) : 'Free')
];

$json['listing'] = [
    'id'		=> $FoodListing->id,
    'link'      => $Seller->link . '/' . $FoodListing->link,
    'name'		=> $FoodListing->title,
    'quantity'	=> $FoodListing->quantity,
    'filename'	=> $FoodListing->filename,
    'ext'		=> $FoodListing->ext
];

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


echo json_encode($json);