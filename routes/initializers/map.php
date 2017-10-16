<?php

$settings = [
    'title' => 'Map | Food From Friends'
];

$city = $_GET['city'];

$GrowerOperation = new GrowerOperation([
    'DB' => $DB
]);

$growers = $GrowerOperation->pull_all_active();

// Set the tile width for the results pane
$grower_count = count($growers);
$tile_width = 'col-6';

if ($grower_count > 4) {
    $tile_width .= ' col-lg-4';
}

// To be converted to JSON for Map
$data = [
    'type'  => 'FeatureCollection',
    'crs'   => [
        'type' => 'name',
        'properties' => [
            'name' => 'Growers'
        ]
    ],
    'features' => []
];

/*
   Iterate through all active growers
    - get grower type
    - get star rating
    - get distance between user and grower
   Add to $growers array w/ $c
 */

$c = 0;

foreach ($growers as $grower) {
    // get grower type
    $ThisGrowerOperation = new GrowerOperation([
        'DB' => $DB,
        'id' => $grower['id']
    ]);

    $growers[$c]['listing_count'] = $ThisGrowerOperation->count_listings();

    // variances
    if ($ThisGrowerOperation->type == 'none') {
        $ThisUser   = new User([
            'DB' => $DB,
            'id' => $grower['user_id']
        ]);
    
        $growers[$c]['filename']   = (!empty($ThisUser->filename)) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $ThisUser->filename . '.' . $ThisUser->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg';
        
        $growers[$c]['latitude']   = $ThisUser->latitude;
        $growers[$c]['longitude']  = $ThisUser->longitude;
    
        $growers[$c]['name']       = $ThisUser->first_name;
        $growers[$c]['city']       = $ThisUser->bio;
    
        $growers[$c]['city']       = $ThisUser->city;
        $growers[$c]['state']      = $ThisUser->state;
    
        $growers[$c]['joined_on']  = $ThisUser->registered_on;
    } else {
        $growers[$c]['filename']   = (!empty($ThisGrowerOperation->filename)) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/grower-operation-images/' . $ThisGrowerOperation->filename . '.' . $ThisGrowerOperation->ext . '?' . time() : PUBLIC_ROOT . 'media/placeholders/default-thumbnail.jpg';
        
        $growers[$c]['latitude']   = $ThisGrowerOperation->latitude;
        $growers[$c]['longitude']  = $ThisGrowerOperation->longitude;
    
        $growers[$c]['name']       = $ThisGrowerOperation->name;
        $growers[$c]['bio']        = $ThisGrowerOperation->bio;
    
        $growers[$c]['city']       = $ThisGrowerOperation->city;
        $growers[$c]['state']      = $ThisGrowerOperation->state;
    
        $growers[$c]['joined_on']  = $ThisGrowerOperation->created_on;
    }

    // get star rating
    /* $stars  = '';

    $floor  = floor($grower['rating']);
    $ceil   = ceil($grower['rating']);

    for ($i = 0; $i < $floor; $i++) {
        $stars .= '<i class="fa fa-star"></i>';
    } if ($floor < $grower['rating'] && $grower['rating'] < $ceil) {
        $stars .= '<i class="fa fa-star-half-o"></i>';
    } for ($i = $ceil; $i < 5; $i++) {
        $stars .= '<i class="fa fa-star-o"></i>';
    }

    $growers[$c]['stars'] = (isset($grower['rating']) ? $stars : '<span class="no-rating">New</span>'); */


    // get distance between user and grower
    if (!empty($User->latitude) && !empty($User->longitude)) {
        $distance = [];

        $length = getDistance([
            'lat' => $User->latitude,
            'lng' => $User->longitude
        ],
        [
            'lat' => $growers[$c]['latitude'],
            'lng' => $growers[$c]['longitude']
        ]);

        if ($length < 0.1) {
            $distance['length'] = round($length * 5280);
            $distance['units'] = 'feet';
        } else {
            $distance['length'] = round($length, 1);
            $distance['units'] = 'miles';
        }

        $growers[$c]['distance'] = $distance;
    }

    $data['features'][] = [
        'type'          => 'Feature',
        'properties'    => [
            // 'scale'         => $grower['id'] * 10,
            'photo'         => $growers[$c]['filename'],
            'name'          => $growers[$c]['name'],
            // 'rating'        => $growers[$c]['stars'],
            'distance'      => (!empty($distance) ? $distance['length'] . ' ' . $distance['units'] . ' away' : $city . ', ' . $state),
            'listings'      => $growers[$c]['listing_count'] . ' listing' . ($growers[$c]['listing_count'] > 1 ? 's' : '')
        ],
        'geometry'      => [
            'type'          => 'Point',
            'coordinates'   => [$growers[$c]['longitude'], $growers[$c]['latitude']]
        ]
    ];

    $c++;
}

?>