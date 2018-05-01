<?php

$settings = [
    'title' => 'Food From Friends'
];

// $city = $_GET['city'];

$GrowerOperation = new GrowerOperation([
    'DB' => $DB
]);

$growers = $GrowerOperation->retrieve([
    'where' => [
        'is_active' => true
    ],
    'order' => 'position'
]);

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

$Growers = [];

if (!empty($growers)) {
    foreach ($growers as $grower) {
        $Growers[$grower['id']] = new GrowerOperation([
            'DB' => $DB,
            'id' => $grower['id'],
        ], [
            'details'   => true
        ]);

        $ThisGrower = $Growers[$grower['id']];
    
        $ThisGrower->listing_count  = $ThisGrower->count_listings();
        $ThisGrower->stars          = stars($ThisGrower->average_rating);
    
        // Get distance between user and seller
        if (isset($User) 
        && !empty($User->latitude) && !empty($User->longitude) 
        && !empty($ThisGrower->latitude) && !empty($ThisGrower->longitude)) {
            $distance = [];
    
            $length = getDistance([
                'lat' => $User->latitude,
                'lng' => $User->longitude
            ], [
                'lat' => $ThisGrower->latitude,
                'lng' => $ThisGrower->longitude
            ]);
    
            if ($length < 0.1) {
                $distance['length'] = round($length * 5280);
                $distance['units']  = 'feet';
            } else {
                $distance['length'] = round($length);
                $distance['units']  = 'miles';
            }
    
            $ThisGrower->distance   = $distance;
        }

        $data['features'][] = [
            'type'          => 'Feature',
            'properties'    => [
                'link'      => $ThisGrower->link,
                'photo'     => 'https://s3.amazonaws.com/foodfromfriends/' . ENV . "/grower-operation-images/{$ThisGrower->filename}.{$ThisGrower->ext}",
                'name'      => $ThisGrower->name,
                'rating'    => $ThisGrower->stars,
                'distance'  => (!empty($ThisGrower->distance['length']) ? "{$ThisGrower->distance['length']} {$ThisGrower->distance['units']} away" : "{$ThisGrower->city}, {$ThisGrower->state}"),
                'listings'  => "<strong>{$ThisGrower->listing_count}</strong> listing" . ($ThisGrower->listing_count == 1 ? '' : 's')
            ],
            'geometry'      => [
                'type'          => 'Point',
                'coordinates'   => [$ThisGrower->longitude, $ThisGrower->latitude]
            ]
        ];
    
        $c++;
    }
}

if (isset($User)) {
    $wishlist = $User->retrieve([
        'where' => [
            'buyer_account_id' => $User->BuyerAccount->id
        ],
        'table' => 'wish_list_items',
        'limit' => 1
    ]);
}

?>