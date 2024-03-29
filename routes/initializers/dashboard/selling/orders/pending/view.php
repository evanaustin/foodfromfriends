<?php

$settings = [
    'title' => 'Pending order | Food From Friends'
];

$order_grower_id = $_GET['id'];

if (\Num::is_id($order_grower_id)) {
    $OrderGrower = new OrderGrower([
        'DB' => $DB,
        'id' => $order_grower_id
    ]);

    if ($OrderGrower->Status->current == 'pending fulfillment') {
        $Order = new Order([
            'DB' => $DB,
            'id' => $OrderGrower->order_id
        ]);
        
        $BuyerAccount = new BuyerAccount([
            'DB' => $DB,
            'id' => $Order->buyer_account_id
        ]);

        $time_elapsed   = \Time::elapsed($OrderGrower->Status->confirmed_on);
        $time_until     = \Time::until($OrderGrower->Status->confirmed_on, '24 hours');

        $items_sold     = 0;
        $unique_items   = 0;

        foreach($OrderGrower->Items as $OrderItem) {
            $items_sold += $OrderItem->quantity;
            $unique_items++;
        }
    }
}

?>