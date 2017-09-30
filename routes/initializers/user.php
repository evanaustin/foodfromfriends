<?php

$settings = [
    'title' => 'User profile | Food From Friends'
];

$ThisUser = new User([
    'DB' => $DB,
    'id' => $_GET['id']
]);

$Delivery = new Delivery([
    'DB' => $DB
]);

$delivery_settings = $Delivery->retrieve('grower_operation_id', $ThisUser->GrowerOperation->id);
$delivery_offered = (!empty($delivery_settings)) ? $delivery_settings[0]['is_offered'] : false;

$Pickup = new Pickup([
    'DB' => $DB
    ]);
    
$pickup_settings = $Pickup->retrieve('grower_operation_id', $ThisUser->GrowerOperation->id);
$pickup_offered = (!empty($pickup_settings)) ? $pickup_settings[0]['is_offered'] : false;

$Meetup = new Meetup([
    'DB' => $DB
]);
    
$meetup_settings = $Pickup->retrieve('grower_operation_id', $ThisUser->GrowerOperation->id);
$meetup_offered = (!empty($meetup_settings)) ? $meetup_settings[0]['is_offered'] : false;

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
    'DB' => $DB,
]);

$listings = $FoodListing->get_listings($ThisUser->GrowerOperation->id);

$Review = new Review([
    'DB' => $DB
]);

$reviews = $Review->retrieve('grower_id', $ThisUser->id);

?>