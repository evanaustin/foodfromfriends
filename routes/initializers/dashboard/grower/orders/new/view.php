<?php

$settings = [
    'title' => 'New order | Food From Friends'
];

$order_grower_id = $_GET['id'];

if (\Num::is_id($order_grower_id)) {
    $OrderGrower = new OrderGrower([
        'DB' => $DB,
        'id' => $order_grower_id
    ]);

    if ($OrderGrower->Status->status == 'not yet confirmed') {
        $Order = new Order([
            'DB' => $DB,
            'id' => $OrderGrower->order_id
        ]);
        
        $Buyer = new User([
            'DB' => $DB,
            'id' => $Order->user_id
        ]);

        $placed_on      = new DateTime($OrderGrower->Status->placed_on, new DateTimeZone('UTC'));
        $placed_on->setTimezone(new DateTimeZone($User->timezone));
        
        $now            = \Time::now(['format' => false]);
        $day_placed     = ($placed_on->format('d') == $now->format('d')) ? 'Today' : 'Yesterday';
        $time_placed    = $placed_on->format('g:i A'); 
        
        $time_elapsed   = \Time::elapsed($OrderGrower->Status->placed_on);
        $time_until     = \Time::until($OrderGrower->Status->placed_on, '24 hours');

        $items_sold     = 0;
        $unique_items   = 0;

        foreach($OrderGrower->FoodListings as $OrderListing) {
            $items_sold += $OrderListing->quantity;
            $unique_items++;
        }
    }
}

?>