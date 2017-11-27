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

    switch($OrderGrower->exchange_option) {
        case 'delivery':
            $address_line_1 = $Buyer->address_line_1;
            $address_line_2 = (!empty($Buyer->address_line_2)) ? $Buyer->address_line_2 : false;
            $city       = $Buyer->city;
            $state      = $Buyer->state;
            $zipcode    = $Buyer->zipcode;
            break;
            
        case 'pickup':
            $address_line_1 = $User->GrowerOperation->address_line_1;
            $address_line_2 = (!empty($User->GrowerOperation->address_line_2)) ? $User->GrowerOperation->address_line_2 : false;
            $city       = $User->GrowerOperation->city;
            $state      = $User->GrowerOperation->state;
            $zipcode    = $User->GrowerOperation->zipcode;
            break;

        case 'meetup':
            $address_line_1 = $User->GrowerOperation->Meetup->address_line_1;
            $address_line_2 = (!empty($User->GrowerOperation->Meetup->address_line_2)) ? $User->GrowerOperation->Meetup->address_line_2 : false;
            $city       = $User->GrowerOperation->Meetup->city;
            $state      = $User->GrowerOperation->Meetup->state;
            $zipcode    = $User->GrowerOperation->Meetup->zipcode;
    }

    foreach($OrderGrower->FoodListings as $OrderListing) {
        $items_sold += $OrderListing->quantity;
        $unique_items++;
    }
}

?>