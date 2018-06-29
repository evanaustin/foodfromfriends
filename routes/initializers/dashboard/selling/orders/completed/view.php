<?php

$settings = [
    'title' => 'Completed order | Food From Friends'
];

$order_grower_id = $_GET['id'];

if (\Num::is_id($order_grower_id)) {
    $OrderGrower = new OrderGrower([
        'DB' => $DB,
        'id' => $order_grower_id
    ]);

    if ($OrderGrower->Status->current == 'completed') {
        $Order = new Order([
            'DB' => $DB,
            'id' => $OrderGrower->order_id
        ]);
        
        $BuyerAccount = new BuyerAccount([
            'DB' => $DB,
            'id' => $Order->buyer_account_id
        ]);
    
        $placed_on      = new DateTime($OrderGrower->Status->placed_on, new DateTimeZone('UTC'));
        $placed_on->setTimezone(new DateTimeZone($User->timezone));
        $date_placed    = $placed_on->format('F d, Y \a\t g:i A'); 
        
        $fulfilled_on   = new DateTime($OrderGrower->Status->fulfilled_on, new DateTimeZone('UTC'));
        $fulfilled_on->setTimezone(new DateTimeZone($User->timezone));
        $date_fulfilled = $fulfilled_on->format('F d, Y'); 
    
        $items_sold     = 0;
        $unique_items   = 0;

        foreach($OrderGrower->Items as $OrderItem) {
            $items_sold += $OrderItem->quantity;
            $unique_items++;
        }
    }
}

?>