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
    'state'             => 'required|regex,/^[a-zA-Z]{2}$/',
    'zipcode'           => 'required|regex,/^[0-9]{5}$/',
    'title'             => 'regex,/^[a-zA-Z0-9\-\s]*$/|max_len,100',
    'day'               => 'required|max_len,9',
    'start-time'        => 'required|max_len,8',
    'end-time'          => 'required|max_len,8',
    'deadline'          => 'integer',
    'order-minimum'     => 'required|regex,/^[0-9]+.[0-9]{2}$/|min_numeric, 0|max_numeric, 1000000'
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
    'zipcode'           => 'trim|whole_number',
    'title'             => 'trim|sanitize_string',
    'day'               => 'trim|sanitize_string',
    'start-time'        => 'trim|sanitize_string',
    'end-time'          => 'trim|sanitize_string',
    'deadline'          => 'trim|whole_number',
    'order-minimum'     => 'trim|sanitize_floats'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;


/*
 * Get LAT & LNG
 */
$full_address = "{$address_line_1}, {$city}, {$state}";
$prepared_address = str_replace(' ', '+', $full_address);

$geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $prepared_address . '&key=' . GOOGLE_MAPS_KEY);
$output = json_decode($geocode);

$lat = $output->results[0]->geometry->location->lat;
$lng = $output->results[0]->geometry->location->lng;


/*
 * Add Meetup
 */
$Meetup = new Meetup([
    'DB' => $DB
]);

$added = $Meetup->add([
    'grower_operation_id'   => $User->GrowerOperation->id,
    'title'                 => $title,
    'address_line_1'        => $address_line_1,
    'address_line_2'        => $address_line_2,
    'city'                  => $city,
    'state'                 => $state,
    'zipcode'               => $zipcode,
    'latitude'              => $lat,
    'longitude'             => $lng,
    'day'                   => $day,
    'start_time'            => $start_time,
    'end_time'              => $end_time,
    'deadline'              => $deadline,
    'order_minimum'         => $order_minimum * 100
]);

if (!$added) quit('We could not save your exchange location');

$json['meetup'] = [
    'title'     => $title,
    'address'   => "{$address_line_1} {$address_line_2}, {$city}, {$state}",
    'day'       => $day,
    'time'      => "{$start_time} &ndash; {$end_time}",
    'deadline'  => $deadline,
    'order_minimum' => _amount($order_minimum * 100)
];

echo json_encode($json);

?>