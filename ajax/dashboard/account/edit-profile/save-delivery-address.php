<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'address-line-1'    => 'required|alpha_numeric_space|max_len,35',
    'address-line-2'    => 'alpha_numeric_space|max_len,25',
    'city'              => 'required|alpha_space|max_len,35',
    'state'             => 'required|regex,/^[A-Z]{2}$/',
    'zipcode'           => 'required|regex,/^[0-9]{5}$/'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'address-line-1'    => 'trim|sanitize_string',
	'address-line-2'    => 'trim|sanitize_string',
	'city'              => 'trim|sanitize_string',
	'state'             => 'trim|sanitize_string',
	'zipcode'           => 'trim|whole_number'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$full_address = $address_line_1 . ', ' . $city . ', ' . $state;
$prepared_address = str_replace(' ', '+', $full_address);

$geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $prepared_address . '&key=' . GOOGLE_MAPS_KEY);
$output= json_decode($geocode);

$lat = $output->results[0]->geometry->location->lat;
$lng = $output->results[0]->geometry->location->lng;

if ($User->exists('user_id', $User->id, 'user_delivery_address')) {
    $updated = $User->update([
        'address_line_1'    => $address_line_1,
        'address_line_2'    => (isset($address_line_2) ? $address_line_2 : ''),
        'city'              => $city,
        'state'             => $state,
        'zipcode'           => $zipcode,
        'latitude'          => $lat,
        'longitude'         => $lng,
        'instructions'      => $instructions
    ], 'user_id', $User->id, 'user_delivery_address');
    
    if (!$updated) quit('We could not update your delivery address');
} else {
    $added = $User->add([
        'user_id'           => $User->id,
        'address_line_1'    => $address_line_1,
        'address_line_2'    => $address_line_2,
        'city'              => $city,
        'state'             => $state,
        'zipcode'           => $zipcode,
        'latitude'          => $lat,
        'longitude'         => $lng,
        'instructions'      => $instructions
    ], 'user_delivery_address');
    
    if (!$added) quit('We could not add your delivery address');
}

echo json_encode($json);

?>