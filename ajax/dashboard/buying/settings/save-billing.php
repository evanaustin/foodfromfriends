<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'card-name'         => 'required|alpha_space',
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
	'card-name'         => 'trim|sanitize_string',
	'address-line-1'    => 'trim|sanitize_string',
	'address-line-2'    => 'trim|sanitize_string',
	'city'              => 'trim|sanitize_string',
	'state'             => 'trim|sanitize_string',
	'zipcode'           => 'trim|whole_number'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

if ($User->BuyerAccount->Billing) {
    $updated = $User->BuyerAccount->update([
        'card_name'         => $card_name,
        'address_line_1'    => $address_line_1,
        'address_line_2'    => (isset($address_line_2) ? $address_line_2 : ''),
        'city'              => $city,
        'state'             => $state,
        'zipcode'           => $zipcode
    ], 'buyer_account_id', $User->BuyerAccount->id, 'buyer_account_billing');
    
    if (!$updated) quit('We could not update your billing information');
} else {
    $added = $User->BuyerAccount->add([
        'card_name'         => $card_name,
        'buyer_account_id'  => $User->BuyerAccount->id,
        'address_line_1'    => $address_line_1,
        'address_line_2'    => $address_line_2,
        'city'              => $city,
        'state'             => $state,
        'zipcode'           => $zipcode
    ], 'buyer_account_billing');
    
    if (!$added) quit('We could not add your billing information');
}

echo json_encode($json);

?>