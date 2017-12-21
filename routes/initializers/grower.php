<?php

$settings = [
    'title' => 'Grower profile | Food From Friends'
];

$GrowerOperation = new GrowerOperation([
    'DB' => $DB,
    'id' => $_GET['id']
], [
    'details'   => true,
    'exchange'  => true
]);

if ($GrowerOperation->is_active) {
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
    
    $listings = $FoodListing->get_listings($GrowerOperation->id);
    
    $Review = new Review([
        'DB' => $DB
    ]);
    
    $reviews = $Review->retrieve('grower_id', $GrowerOperation->id);

    $settings['title'] = $GrowerOperation->details['name'] . ' | Food From Friends';
}

?>