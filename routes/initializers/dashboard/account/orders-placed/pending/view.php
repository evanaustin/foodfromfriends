<?php

$order_id = $_GET['id'];

if ($Num->is_id($order_id)) {
    $Order = new Order([
        'DB' => $DB,
        'id' => $order_id
    ]);

    $Buyer = new User([
        'DB' => $DB,
        'id' => $Order->user_id
    ]);

    /* switch($OrderGrower->exchange_option) {
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
    } */
}

?>