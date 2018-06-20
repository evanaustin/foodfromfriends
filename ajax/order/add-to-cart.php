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
    'item-id'           => 'required|integer',
    'quantity'			=> 'required|integer',
    'exchange-option'   => 'required|alpha',
    'distance-miles'    => 'numeric',
    'is-wholesale'      => 'integer'
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
    'distance-miles'    => 'trim|sanitize_string',
    'is-wholesale'      => 'trim|sanitize_string'
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

$Item = new Item([
    'DB' => $DB,
    'id' => $item_id
]);

$Order = new Order([
    'DB' => $DB
]);


/*
 * Ensure item being added to cart belongs to Seller
 */

if ($Item->grower_operation_id != $Seller->id) {
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

if (isset($Order->Growers[$Item->grower_operation_id]->Items[$Item->id])) {
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

$Order->add_to_cart($Seller, $exchange_option, $Item->id, $quantity);


/*
 * Prepare JSON
 */

$OrderGrower = $Order->Growers[$Seller->id];
$OrderItem = $OrderGrower->Items[$Item->id];

$json['ordergrower'] = [
    'id'		=> $OrderGrower->id,
    'grower_id'	=> $OrderGrower->grower_operation_id,
    'name'		=> $Seller->name,
    'subtotal'	=> '$' . number_format($OrderGrower->total / 100, 2),
    'exchange'	=> ucfirst($OrderGrower->Exchange->type),
    'ex_fee'	=> (($OrderGrower->Exchange->fee > 0) ? '$' . number_format($OrderGrower->Exchange->fee / 100, 2) : 'Free')
];

$json['item'] = [
    'id'		    => $Item->id,
    'link'          => $Seller->link . '/' . $Item->link,
    'name'		    => $Item->title,
    'package_type'  => $Item->package_type,
    'quantity'	    => $Item->quantity,
    'measurement'   => (!empty($Item->measurement)) ? $Item->measurement : '',
    'metric'	    => (!empty($Item->metric)) ? $Item->metric : '',
    'filename'	    => $Item->Image->filename,
    'ext'		    => $Item->Image->ext
];

$json['order_item'] = [
    'id'		=> $OrderItem->id,
    'quantity'	=> $OrderItem->quantity,
    'subtotal'	=> _amount($OrderItem->total)
];

$json['order'] = [
    'subtotal'	=> _amount($Order->subtotal),
    'ex_fee'    => _amount($Order->exchange_fees),
    'fff_fee'   => _amount($Order->fff_fee),
    'total'		=> _amount($Order->total)
];


echo json_encode($json);