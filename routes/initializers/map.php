<?php

$settings = [
    'title' => 'Map | Food From Friends'
];

$city = $_GET['city'];

$GrowerOperation = new GrowerOperation([
    'DB' => $DB
]);

$growers = $GrowerOperation->pull_all();

$grower_count = count($growers);
$tile_width = 'col-6';

if ($grower_count > 4) {
    $tile_width .= ' col-lg-4';
}

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

$c = 0;

foreach ($growers as $grower) {
    $stars  = '';

    $floor  = floor($grower['rating']);
    $ceil   = ceil($grower['rating']);

    for ($i = 0; $i < $floor; $i++) {
        $stars .= '<i class="fa fa-star"></i>';
    } if ($floor < $grower['rating'] && $grower['rating'] < $ceil) {
        $stars .= '<i class="fa fa-star-half-o"></i>';
    } for ($i = $ceil; $i < 5; $i++) {
        $stars .= '<i class="fa fa-star-o"></i>';
    }

    $growers[$c]['stars'] = (isset($grower['rating']) ? $stars : '<span class="no-rating">New</span>');

    if (!empty($User->latitude) && !empty($User->longitude)) {
        $distance = [];

        $length = getDistance([
            'lat' => $User->latitude,
            'lng' => $User->longitude
        ],
        [
            'lat' => $grower['latitude'],
            'lng' => $grower['longitude']
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
            'scale'         => $grower['id'] * 10,
            'photo'         => (!empty($grower['filename']) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $grower['filename'] . '.' . $grower['ext'] : ''),
            'name'          => $grower['first_name'],
            'rating'        => $growers[$c]['stars'],
            'distance'      => (!empty($distance) ? $distance['length'] . ' ' . $distance['units'] . ' away' : $grower['city'] . ', ' . $grower['state']),
            'listings'      => $grower['listings'] . ' listing' . ($grower['listings'] > 1 ? 's' : '')
        ],
        'geometry'      => [
            'type'          => 'Point',
            'coordinates'   => [$grower['longitude'], $grower['latitude']]
        ]
    ];

    $c++;
}

?>