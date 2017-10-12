<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'address-line-1'    => 'required|alpha_numeric_space|max_len,25',
    'city'              => 'required|alpha_space|max_len,25',
    'state'             => 'required|regex,/^[A-Z]{2}$/',
    'zip'               => 'required|regex,/^[0-9]{5}$/'
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
	'zip'               => 'trim|whole_number',
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$full_address = $address_line_1 . ', ' . $city . ', ' . $state;
$prepared_address = str_replace(' ', '+', $full_address);

$geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $prepared_address . '&key=' . GOOGLE_MAPS_KEY);
$output= json_decode($geocode);

$lat = $output->results[0]->geometry->location->lat;
$lng = $output->results[0]->geometry->location->lng;

if ($User->GrowerOperation->exists('grower_operation_id', $User->GrowerOperation->id, 'grower_operation_addresses')) {
    $updated = $User->GrowerOperation->update([
        'address_line_1'    => $address_line_1,
        'address_line_2'    => (isset($address_line_2) ? $address_line_2 : ''),
        'city'              => $city,
        'state'             => $state,
        'zipcode'           => $zip,
        'latitude'          => $lat,
        'longitude'         => $lng
    ], 'grower_operation_id', $User->GrowerOperation->id, 'grower_operation_addresses');
    
    if (!$updated) quit('We could not update your location');
} else {
    $added = $User->GrowerOperation->add([
        'grower_operation_id'   => $User->GrowerOperation->id,
        'address_line_1'        => $address_line_1,
        'address_line_2'        => $address_line_2,
        'city'                  => $city,
        'state'                 => $state,
        'zipcode'               => $zip,
        'latitude'              => $lat,
        'longitude'             => $lng
    ], 'grower_operation_addresses');
    
    if (!$added) quit('We could not add your operation\'s location');
}

$User->GrowerOperation->check_active($User);

echo json_encode($json);

?>