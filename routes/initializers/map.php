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
    ]
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

if (!empty($growers)) {
    foreach ($growers as $grower) {
        // get grower type
        $ThisGrowerOperation = new GrowerOperation([
            'DB' => $DB,
            'id' => $grower['id'],
        ], [
            'details'   => true
        ]);
    
        $growers[$c]['listing_count'] = $ThisGrowerOperation->count_listings();
    
        $growers[$c]['path']        = $ThisGrowerOperation->details['path'];
        $growers[$c]['ext']         = $ThisGrowerOperation->details['ext'];
        
        $growers[$c]['latitude']    = $ThisGrowerOperation->details['lat'];
        $growers[$c]['longitude']   = $ThisGrowerOperation->details['lng'];
    
        $growers[$c]['name']        = $ThisGrowerOperation->details['name'];
        $growers[$c]['bio']         = $ThisGrowerOperation->details['bio'];
    
        $growers[$c]['city']        = $ThisGrowerOperation->details['city'];
        $growers[$c]['state']       = $ThisGrowerOperation->details['state'];
    
        $growers[$c]['joined_on']   = $ThisGrowerOperation->details['joined'];
    
        $growers[$c]['stars'] = stars($ThisGrowerOperation->average_rating);
    
    
        // get distance between user and grower
        if (isset($User) 
        && !empty($User->latitude) && !empty($User->longitude) 
        && !empty($GrowerOperation->details['lat']) && !empty($GrowerOperation->details['lng'])) {
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
                // 'scale'     => $growers[$c]['listing_count'] * 10,
                'id'        => $grower['id'],
                'photo'     => 'https://s3.amazonaws.com/foodfromfriends/' . ENV . $growers[$c]['path'] . '.' . $growers[$c]['ext'],
                'name'      => $growers[$c]['name'],
                'rating'    => $growers[$c]['stars'],
                'distance'  => (!empty($distance) ? $distance['length'] . ' ' . $distance['units'] . ' away' : $growers[$c]['city'] . ', ' . $growers[$c]['state']),
                'listings'  => $growers[$c]['listing_count'] . ' listing' . ($growers[$c]['listing_count'] > 1 ? 's' : '')
            ],
            'geometry'      => [
                'type'          => 'Point',
                'coordinates'   => [$growers[$c]['longitude'], $growers[$c]['latitude']]
            ]
        ];
    
        $c++;
    }
}

?>