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

$updated = $User->update([
    'address_line_1'    => $address_line_1,
    'address_line_2'    => (isset($address_line_2) ? $address_line_2 : ''),
    'city'              => $city,
    'state'             => $state,
    'zipcode'           => $zip
], 'id', $User->id);

if (!$updated) quit('We could not update your location');

echo json_encode($json);

?>