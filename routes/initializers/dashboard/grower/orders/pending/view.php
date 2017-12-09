<?php

$settings = [
    'title' => 'Pending order | Food From Friends'
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

    $time_elapsed   = $Time->elapsed($OrderGrower->confirmed_on);
    $time_until     = $Time->until($OrderGrower->confirmed_on, '24 hours');

    foreach($OrderGrower->FoodListings as $OrderListing) {
        $items_sold += $OrderListing->quantity;
        $unique_items++;
    }
}

?>