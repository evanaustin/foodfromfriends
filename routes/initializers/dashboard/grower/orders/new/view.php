<?php

$settings = [
    'title' => 'New order | Food From Friends'
];

$order_grower_id = $_GET['id'];

if ($Num->is_id($order_grower_id)) {
    $OrderGrower = new OrderGrower([
        'DB' => $DB,
        'id' => $order_grower_id,
        'buyer_id' => $Order->user_id,
        'seller_id' => $GrowerOperation->id
    ]);

    $Order = new Order([
        'DB' => $DB,
        'id' => $OrderGrower->order_id
    ]);
    
    $Buyer = new User([
        'DB' => $DB,
        'id' => $Order->user_id
    ]);

    $now            = new DateTime(\Time::now());
    $placed_on      = new DateTime($Order->placed_on);
    $day_placed     = ($placed_on->format('d') == $now->format('d')) ? 'Today' : 'Yesterday';
    $time_placed    = $placed_on->format('g:i A'); 

    $time_elapsed   = \Time::elapsed($Order->placed_on);
    $time_until     = \Time::until($Order->placed_on, '24 hours');

    foreach($OrderGrower->FoodListings as $OrderListing) {
        $items_sold += $OrderListing->quantity;
        $unique_items++;
    }
}

?>