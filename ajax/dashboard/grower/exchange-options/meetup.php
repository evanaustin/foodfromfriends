<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

foreach ($_POST as $k => $v) ${str_replace('-', '_', $k)} = $v;

$rules = [
    'is-offered'        => 'required|boolean',
    'address-line-2'    => 'alpha_space|max_len,15'
];

if ($is_offered) {
    $rules['address-line-1'] = 'required|alpha_numeric_space|max_len,25';
    $rules['city'] = 'required|alpha_space|max_len,25';
    $rules['state'] = 'required|regex,/^[A-Z]{2}$/';
    $rules['zip'] = 'required|regex,/^[0-9]{5}$/';
    $rules['time'] = 'required';
}

$Gump->validation_rules($rules);
$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors()[0]);
}

$Gump->filter_rules([
    'address-line-1'    => 'trim|sanitize_string',
	'address-line-2'    => 'trim|sanitize_string',
	'city'              => 'trim|sanitize_string',
	'state'             => 'trim|sanitize_string',
	'zip'               => 'trim|whole_number'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$Meetup = new Meetup([
    'DB' => $DB
]);

if ($Meetup->exists('grower_operation_id', $User->GrowerOperation->id)) { 
    $updated = $Meetup->update([
        'is_offered'            => $is_offered,
        'address_line_1'        => ($is_offered ? $address_line_1 : ''),
        'address_line_2'        => ($is_offered ? $address_line_2 : ''),
        'city'                  => ($is_offered ? $city : ''),
        'state'                 => ($is_offered ? $state : ''),
        'zip'                   => ($is_offered ? $zip : ''),
        'time'                  => ($is_offered ? $time : ''),
    ], 'grower_operation_id', $User->GrowerOperation->id);

    if (!$updated) quit('We could not update your meetup preferences');
} else {
    $added = $Meetup->add([
        'grower_operation_id'   => $User->GrowerOperation->id,
        'is_offered'            => $is_offered,
        'address_line_1'        => $address_line_1,
        'address_line_2'        => $address_line_2,
        'city'                  => $city,
        'state'                 => $state,
        'zip'                   => $zip,
        'time'                  => $time
    ]);

    if (!$added) quit('We could not save your meetup preferences');
}

// reinitialize User for fresh check
$User = new User([
    'DB' => $DB,
    'id' => $USER['id']
]);

$User->GrowerOperation->check_active($User);

echo json_encode($json);

?>