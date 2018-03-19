<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

foreach ($_POST as $k => $v) ${str_replace('-', '_', $k)} = $v;

$rules = [
    'is-offered' => 'required|boolean'
];

if ($is_offered) {
    $rules['address-line-1']    = 'required|alpha_numeric_space|max_len,35';
    $rules['address-line-2']    = 'alpha_numeric_space|max_len,25';
    $rules['city']              = 'required|alpha_space|max_len,35';
    $rules['state']             = 'required|regex,/^[A-Z]{2}$/';
    $rules['zipcode']           = 'required|regex,/^[0-9]{5}$/';
    $rules['time']              = 'required';
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
	'zipcode'           => 'trim|whole_number'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

if (!$User->GrowerOperation) {
    $GrowerOperation = new GrowerOperation([
        'DB' => $DB
    ]);

    try {
        $grower_operation_id = $GrowerOperation->create($User, [
            'type' => 1
        ]);
    } catch (\Exception $e) {
        error_log($e->getMessage());
        quit('Hmm, something went wrong!');
    }
} else {
    $grower_operation_id = $User->GrowerOperation->id;
}

$Meetup = new Meetup([
    'DB' => $DB
]);

if ($Meetup->exists('grower_operation_id', $grower_operation_id)) { 
    $updated = $Meetup->update([
        'is_offered'            => $is_offered,
        'address_line_1'        => ($is_offered ? $address_line_1 : ''),
        'address_line_2'        => ($is_offered ? $address_line_2 : ''),
        'city'                  => ($is_offered ? $city : ''),
        'state'                 => ($is_offered ? $state : ''),
        'zipcode'                   => ($is_offered ? $zipcode : ''),
        'time'                  => ($is_offered ? $time : ''),
    ], 'grower_operation_id', $grower_operation_id);

    if (!$updated) quit('We could not update your meetup preferences');
} else {
    $added = $Meetup->add([
        'grower_operation_id'   => $grower_operation_id,
        'is_offered'            => $is_offered,
        'address_line_1'        => $address_line_1,
        'address_line_2'        => $address_line_2,
        'city'                  => $city,
        'state'                 => $state,
        'zipcode'                   => $zipcode,
        'time'                  => $time
    ]);

    if (!$added) quit('We could not save your meetup preferences');
}

// reinitialize User & Operation for fresh check
$User = new User([
    'DB' => $DB,
    'id' => $USER['id']
]);

if (!empty($User->GrowerOperation)) {
    if (isset($_SESSION['user']['active_operation_id']) && $_SESSION['user']['active_operation_id'] != $User->GrowerOperation->id) {
        $User->GrowerOperation = $User->Operations[$_SESSION['user']['active_operation_id']];
    }
}

$is_active = $User->GrowerOperation->check_active();

$json['is_active']  = $is_active;
$json['link'] = $User->GrowerOperation->link;

echo json_encode($json);

?>