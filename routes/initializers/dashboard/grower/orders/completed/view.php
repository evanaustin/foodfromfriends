<?php

$settings = [
    'title' => 'Completed order | Food From Friends'
];

$order_grower_id = $_GET['id'];

if ($Num->is_id($order_grower_id)) {
    $OrderGrower = new OrderGrower([
        'DB' => $DB,
        'id' => $order_grower_id
    ]);

    $Order = new Order([
        'DB' => $DB,
        'id' => $OrderGrower->order_id
    ]);
    
    $Buyer = new User([
        'DB' => $DB,
        'id' => $Order->user_id
    ]);

    $placed_on      = new DateTime($Order->placed_on);
    $date_placed    = $placed_on->format('F d, Y \a\t g:i A'); 
    
    $fulfilled_on      = new DateTime($OrderGrower->fulfilled_on);
    $date_fulfilled    = $fulfilled_on->format('F d, Y'); 

    foreach($OrderGrower->FoodListings as $OrderListing) {
        $items_sold += $OrderListing->quantity;
        $unique_items++;
    }
}

?>