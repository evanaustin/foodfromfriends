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
    'seller-account-id' => 'integer'
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
    'seller-account-id' => 'trim|sanitize_numbers'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;


/*
 * Add BuyerAccount:Address
 */

$full_address = "{$address_line_1}, {$city}, {$state}";
$prepared_address = str_replace(' ', '+', $full_address);

$geocode = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $prepared_address . '&key=' . GOOGLE_MAPS_KEY);
$output= json_decode($geocode);

$lat = $output->results[0]->geometry->location->lat;
$lng = $output->results[0]->geometry->location->lng;
    
$added = $User->BuyerAccount->add([
    'buyer_account_id'  => $User->BuyerAccount->id,
    'address_line_1'    => $address_line_1,
    'address_line_2'    => $address_line_2,
    'city'              => $city,
    'state'             => $state,
    'zipcode'           => $zipcode,
    'latitude'          => $lat,
    'longitude'         => $lng
], 'buyer_account_addresses');

if (!$added) quit('We could not add your location');


/*
 * Reinitialize User:BuyerAccount
 */

$User = new User([
    'DB' => $DB,
    'id' => $User->id,
    'buyer_account' => true
]);

/*
 * Calculate distance
 */

if (!empty($seller_account_id)) {
    $SellerAccount = new GrowerOperation([
        'DB' => $DB,
        'id' => $seller_account_id
    ], [
        'exchange'  => true
    ]);
    
    if (isset($User, $User->BuyerAccount, $User->BuyerAccount->Address, $User->BuyerAccount->Address->latitude, $User->BuyerAccount->Address->longitude)) {
        $geocode    = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins={$User->BuyerAccount->Address->latitude},{$User->BuyerAccount->Address->longitude}&destinations={$SellerAccount->latitude},{$SellerAccount->longitude}&key=" . GOOGLE_MAPS_KEY);
        $output     = json_decode($geocode);
        $distance   = explode(' ', $output->rows[0]->elements[0]->distance->text);
        $distance_miles = round((($distance[1] == 'ft') ? $distance[0] / 5280 : $distance[0]), 4);
    }

    $json['distance_miles'] = $distance_miles;
}

$json['slug'] = $slug;

echo json_encode($json);