<?php

$settings = [
    'title' => 'Food listing | Food From Friends'
];

$FoodListing = new FoodListing([
    'DB' => $DB,
    'id' => $_GET['id']
]);

if (isset($FoodListing->id)) {
    $GrowerOperation = new GrowerOperation([
        'DB' => $DB,
        'id' => $FoodListing->grower_operation_id
    ], [
        'details'   => true,
        'exchange'  => true
    ]);

    if (isset($GrowerOperation->id)) {
        $exchange_options_available = [];

        if ($GrowerOperation->Delivery && $GrowerOperation->Delivery->is_offered) $exchange_options_available []= 'delivery';
        if ($GrowerOperation->Pickup && $GrowerOperation->Pickup->is_offered) $exchange_options_available []= 'pickup';
        if ($GrowerOperation->Meetup && $GrowerOperation->Meetup->is_offered) $exchange_options_available []= 'meetup';

        $active_ex_op = (isset($User) && $User->ActiveOrder->Growers[$GrowerOperation->id]->exchange_option) ? $User->ActiveOrder->Growers[$GrowerOperation->id]->exchange_option : null;

        if (isset($User) 
        && !empty($User->latitude) && !empty($User->longitude) 
        && !empty($GrowerOperation->details['lat']) && !empty($GrowerOperation->details['lng'])) {
            
            $length = getDistance([
                'lat' => $User->latitude,
                'lng' => $User->longitude
            ],
            [
                'lat' => $GrowerOperation->details['lat'],
                'lng' => $GrowerOperation->details['lng']
            ]);
        
            if ($length < 0.1) {
                $distance['length'] = round($length * 5280);
                $distance['units'] = 'feet';
            } else {
                $distance['length'] = round($length, 1);
                $distance['units'] = 'miles';
            }
        }

        $grower_stars  = '';
        
        // $floor  = floor($grower['rating']);
        // $ceil   = ceil($grower['rating']);
    
        /* for ($i = 0; $i < $floor; $i++) {
            $stars .= '<i class="fa fa-star"></i>';
        } if ($floor < $grower['rating'] && $grower['rating'] < $ceil) {
            $stars .= '<i class="fa fa-star-half-o"></i>';
        } for ($i = $ceil; $i < 5; $i++) {
            $stars .= '<i class="fa fa-star-o"></i>';
        } */

        for ($i = 0; $i < 5; $i++) {
            $grower_stars .= '<i class="fa fa-star"></i>';
        }

        $settings['title'] = $FoodListing->title . ' from ' . $GrowerOperation->details['name'] . ' | Food From Friends';
    }
}

?>