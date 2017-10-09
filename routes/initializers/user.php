<?php

$settings = [
    'title' => 'User profile | Food From Friends'
];

$ThisUser = new User([
    'DB' => $DB,
    'id' => $_GET['id']
]);

if (!empty($User->latitude) && !empty($User->longitude) && !empty($ThisUser->latitude) && !empty($ThisUser->longitude)) {
    $length = getDistance([
        'lat' => $User->latitude,
        'lng' => $User->longitude
    ],
    [
        'lat' => $ThisUser->latitude,
        'lng' => $ThisUser->longitude
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

$listings = $FoodListing->get_listings($ThisUser->GrowerOperation->id);

$Review = new Review([
    'DB' => $DB
]);

$reviews = $Review->retrieve('grower_id', $ThisUser->id);

?>