<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

foreach ($_POST as $k => $v) ${str_replace('-', '_', $k)} = $v;

$rules = [
    'is-offered' => 'required|boolean'
];

if ($is_offered) {
    $rules['instructions']  = 'required';
    $rules['time']          = 'required';
}

$Gump->validation_rules($rules);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors()[0]);
}

$Gump->filter_rules([
    'instructions'  => 'trim|sanitize_string',
	'time'          => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$Pickup = new Pickup([
    'DB' => $DB
]);

if ($Pickup->exists('grower_operation_id', $grower_operation_id)){
    $updated = $Pickup->update([
        'is_offered'            => $is_offered,
        'instructions'          => ($is_offered ? $instructions : ''),
        'time'                  => ($is_offered ? $time : '')
    ], 'grower_operation_id', $grower_operation_id);

    if (!$updated) quit('We could not update your pickup preferences');
} else {
    $added = $Pickup->add([
        'grower_operation_id'   => $grower_operation_id,
        'is_offered'            => $is_offered,
        'instructions'          => $instructions,
        'time'                  => $time
    ]);

    if (!$added) quit('We could not save your pickup preferences');
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