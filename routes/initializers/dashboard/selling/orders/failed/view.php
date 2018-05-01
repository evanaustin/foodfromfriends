<?php

$settings = [
    'title' => 'Failed order | Food From Friends'
];

$order_grower_id = $_GET['id'];

if (\Num::is_id($order_grower_id)) {
    $OrderGrower = new OrderGrower([
        'DB' => $DB,
        'id' => $order_grower_id
    ]);

    $voided = [
        'just expired',
        'expired',
        'rejected',
        'cancelled by buyer',
        'cancelled by seller'
    ];

    if (in_array($OrderGrower->Status->current, $voided)) {
        $Order = new Order([
            'DB' => $DB,
            'id' => $OrderGrower->order_id
        ]);
        
        $Buyer = new BuyerAccount([
            'DB' => $DB,
            'id' => $Order->buyer_account_id
        ]);
    
        $placed_on      = new DateTime($OrderGrower->Status->placed_on, new DateTimeZone('UTC'));
        $placed_on->setTimezone(new DateTimeZone($User->timezone));
        $date_placed    = $placed_on->format('F d, Y \a\t g:i A'); 
        
        $voided_on = new DateTime($OrderGrower->Status->voided_on, new DateTimeZone('UTC'));
        $voided_on->setTimezone(new DateTimeZone($User->timezone));
        $date_voided = $voided_on->format('F d, Y'); 
    
        $items_sold     = 0;
        $unique_items   = 0;

        foreach($OrderGrower->FoodListings as $OrderListing) {
            $items_sold += $OrderListing->quantity;
            $unique_items++;
        }
    }
}

?>