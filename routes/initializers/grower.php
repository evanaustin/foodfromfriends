<?php

$settings = [
    'title' => 'Grower profile | Food From Friends'
];

// ! verify ID

$GrowerOperation = new GrowerOperation([
    'DB' => $DB,
    'id' => $_GET['id']
], [
    'details'   => true,
    'exchange'  => true
]);

if ($GrowerOperation->is_active) {
    $joined_on = new DateTime($GrowerOperation->created_on, new DateTimeZone('UTC'));
    $joined_on->setTimezone(new DateTimeZone('America/New_York'));

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
    
    $FoodListing = new FoodListing([
        'DB' => $DB
    ]);

    $grower_stars = stars($GrowerOperation->average_rating);
    
    $listings = $FoodListing->get_all_listings($GrowerOperation->id);

    $ratings = $GrowerOperation->retrieve([
        'where' => [
            'grower_operation_id' => $GrowerOperation->id
        ],
        'table' => 'grower_operation_ratings',
        'recent' => true
    ]);

    $settings['title'] = $GrowerOperation->details['name'] . ' | Food From Friends';
}

?>