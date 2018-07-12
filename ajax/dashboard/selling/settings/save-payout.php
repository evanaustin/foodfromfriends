<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'pay-to'            => 'required|alpha_space|max_len,35',
    'routing'           => 'required|alpha_space|max_len,9',
    'bank-account'      => 'required|alpha_space|max_len,17',
    'first-name'        => 'alpha_space|max_len,25',
    'last-name'         => 'alpha_space|max_len,25',
    'address-line-1'    => 'required|alpha_numeric_space|max_len,35',
    'address-line-2'    => 'alpha_numeric_space|max_len,25',
    'city'              => 'required|alpha_space|max_len,35',
    'state'             => 'required|regex,/^[a-zA-Z]{2}$/',
    'zipcode'           => 'required|regex,/^[0-9]{5}$/'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'pay-to'            => 'trim|sanitize_string',
	'routing'           => 'trim|sanitize_string',
	'bank-account'      => 'trim|sanitize_string',
	'first-name'        => 'trim|sanitize_string',
	'last-name'         => 'trim|sanitize_string',
	'address-line-1'    => 'trim|sanitize_string',
	'address-line-2'    => 'trim|sanitize_string',
	'city'              => 'trim|sanitize_string',
	'state'             => 'trim|sanitize_string',
	'zipcode'           => 'trim|whole_number',
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

if ($User->GrowerOperation->exists('seller_id', $User->GrowerOperation->id, 'seller_payout_settings')) {
    $updated = $User->GrowerOperation->update([
        'pay_to'                => $pay_to,
        'routing_number'        => $routing,
        'account_number'        => $bank_account,
        'first_name'            => (isset($first_name) ? $first_name : ''),
        'last_name'             => (isset($last_name) ? $last_name : ''),
        'address_line_1'        => $address_line_1,
        'address_line_2'        => (isset($address_line_2) ? $address_line_2 : ''),
        'city'                  => $city,
        'state'                 => $state,
        'zipcode'               => $zipcode
    ], 'seller_id', $User->GrowerOperation->id, 'seller_payout_settings');
    
    if (!$updated) quit('We could not save your payout settings');
} else {
    $added = $User->GrowerOperation->add([
        'seller_id'             => $User->GrowerOperation->id,
        'pay_to'                => $pay_to,
        'routing_number'        => $routing,
        'account_number'        => $bank_account,
        'first_name'            => (isset($first_name) ? $first_name : ''),
        'last_name'             => (isset($last_name) ? $last_name : ''),
        'address_line_1'        => $address_line_1,
        'address_line_2'        => $address_line_2,
        'city'                  => $city,
        'state'                 => $state,
        'zipcode'               => $zipcode
    ], 'seller_payout_settings');
    
    if (!$added) quit('We could not save your payout settings');
}

echo json_encode($json);

?>