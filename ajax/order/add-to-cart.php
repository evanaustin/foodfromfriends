<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error']          = null;
$json['success']        = true;
$json['set-exchange']   = false;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'seller-id'         => 'required|integer',
    'item-id'           => 'required|integer',
    'quantity'			=> 'required|integer',
    'exchange-option'   => 'required|alpha',
    'distance-miles'    => 'numeric'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'seller-id'         => 'trim|sanitize_numbers',
	'item-id'	        => 'trim|sanitize_numbers',
    'quantity'			=> 'trim|sanitize_numbers',
    'exchange-option'	=> 'trim|sanitize_string',
    'distance-miles'    => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;


/*
 * Initialize Seller, Item, & Order
 */

$SellerAccount = new GrowerOperation([
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

if ($Item->grower_operation_id != $SellerAccount->id) {
    quit('You cannot add this item to your cart');
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
    if ($distance_miles > $SellerAccount->Delivery->distance) {
        quit("This seller delivers up to {$SellerAccount->Delivery->distance} miles, but you are {$distance_miles} miles away");
    }

} else {
    $distance = 0;
}


/*
 * Retrieve or create cart
 */

$Order = $Order->get_cart($User->BuyerAccount->id);


/*
 * Check if OrderGrower exists; update if OrderExchange if changed
 */

if (isset($Order->Growers[$SellerAccount->id])) {
    $OrderGrower = $Order->Growers[$SellerAccount->id];
    
    if ($OrderGrower->Exchange->type != $exchange_option) {
        $OrderGrower->Exchange->set_type($exchange_option);
        $json['set_exchange'] = true;
    }
}


/*
 * Modify quantity if OrderItem exsts; add Item to cart otherwise
 */

if (isset($OrderGrower, $OrderGrower->Items[$Item->id])) {
    $OrderItem = $OrderGrower->Items[$Item->id];

    if ($OrderItem->quantity != $quantity) {
        $Order->modify_quantity($Item, $quantity);
        $json['action'] = 'modify-quantity';
    } else {
        quit('This item is already in your basket - nothing to update');
    }
} else {
    $Order->add_to_cart($SellerAccount, $exchange_option, $Item->id, $quantity);
    
    $OrderGrower = $Order->Growers[$SellerAccount->id];
    $OrderItem = $OrderGrower->Items[$Item->id];

    $json['action'] = 'add-item';
}


/*
 * Prepare JSON
 */

$json['ordergrower'] = [
    'id'		=> $OrderGrower->id,
    'grower_id'	=> $OrderGrower->grower_operation_id,
    'name'		=> $SellerAccount->name,
    'subtotal'	=> '$' . number_format($OrderGrower->total / 100, 2),
    'exchange'	=> strtolower($OrderGrower->Exchange->type),
    'ex_fee'	=> (($OrderGrower->Exchange->fee > 0) ? '$' . number_format($OrderGrower->Exchange->fee / 100, 2) : 'Free')
];

$json['item'] = [
    'id'		    => $Item->id,
    'link'          => $SellerAccount->link . '/' . $Item->link,
    'name'		    => $Item->title,
    'package_type'  => $Item->package_type,
    'quantity'	    => $Item->quantity,
    'measurement'   => (!empty($Item->measurement)) ? $Item->measurement : '',
    'metric'	    => (!empty($Item->metric)) ? $Item->metric : '',
    'filename'	    => $Item->Image->filename,
    'ext'		    => $Item->Image->ext
];

$json['orderitem'] = [
    'id'		=> $OrderItem->id,
    'quantity'	=> $quantity,
    'subtotal'	=> _amount($Item->price * $quantity)
];

$json['order'] = [
    'subtotal'	=> _amount($Order->subtotal),
    'ex_fee'    => _amount($Order->exchange_fees),
    'fff_fee'   => _amount($Order->fff_fee),
    'total'		=> _amount($Order->total)
];


echo json_encode($json);