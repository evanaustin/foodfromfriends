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

        $active_ex_op = (isset($User) && isset($User->ActiveOrder->Growers[$GrowerOperation->id]->Exchange)) ? $User->ActiveOrder->Growers[$GrowerOperation->id]->Exchange->type : null;

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

        $grower_stars   = ($GrowerOperation->average_rating == 0) ? 'New' : stars($GrowerOperation->average_rating);
        $item_stars     = ($FoodListing->average_rating == 0) ? 'New' : stars($FoodListing->average_rating);

        $ratings = $FoodListing->get_ratings();

        $settings['title'] = $FoodListing->title . ' from ' . $GrowerOperation->details['name'] . ' | Food From Friends';
    }
}

?>