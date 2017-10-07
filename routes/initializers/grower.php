<?php

$settings = [
    'title' => 'Grower profile | Food From Friends'
];

$GrowerOperation = new GrowerOperation([
    'DB' => $DB,
    'id' => $_GET['id']
]);

$team_members = $GrowerOperation->get_team_members();

if ($GrowerOperation->type == 'none') {
    $ThisUser   = new User([
        'DB' => $DB,
        'id' => $team_members[0]['id']
    ]);

    $filename   = (!empty($ThisUser->filename)) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $ThisUser->filename . '.' . $ThisUser->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg';
    
    $latitude   = $ThisUser->latitude;
    $longitude  = $ThisUser->longitude;

    $name       = $ThisUser->first_name;
    $city       = $ThisUser->bio;

    $city       = $ThisUser->city;
    $state      = $ThisUser->state;

    $joined_on  = $ThisUser->registered_on;
} else {
    $filename   = (!empty($GrowerOperation->filename)) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/grower-operation-images/' . $GrowerOperation->filename . '.' . $GrowerOperation->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg';
    
    $latitude   = $GrowerOperation->latitude;
    $longitude  = $GrowerOperation->longitude;

    $name       = $GrowerOperation->name;
    $bio        = $GrowerOperation->bio;

    $city       = $GrowerOperation->city;
    $state      = $GrowerOperation->state;

    $joined_on  = $GrowerOperation->created_on;
}

if (!empty($User->latitude) && !empty($User->longitude) && !empty($latitude) && !empty($longitude)) {
    $length = getDistance([
        'lat' => $User->latitude,
        'lng' => $User->longitude
    ],
    [
        'lat' => $latitude,
        'lng' => $longitude
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

?>