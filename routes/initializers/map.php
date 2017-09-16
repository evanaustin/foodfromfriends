<?php

$city = $_GET['city'];

$Grower = new Grower([
    'DB' => $DB
]);

$growers = $Grower->pull_all();

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

    $data['features'][] = [
        'type'          => 'Feature',
        'properties'    => [
            'scale'         => $grower['id'] * 10,
            'photo'         => (!empty($grower['filename']) ? 'https://s3.amazonaws.com/foodfromfriends/' . ENV . '/profile-photos/' . $grower['filename'] . '.' . $grower['ext'] : ''),
            'name'          => $grower['first_name'],
            'rating'        => (isset($grower['rating']) ? $stars : '<span class="no-rating">New</span>'),
            'foods'         => $grower['foods']
        ],
        'geometry'      => [
            'type'          => 'Point',
            'coordinates'   => [$grower['longitude'], $grower['latitude']]
        ]
    ];
}

?>