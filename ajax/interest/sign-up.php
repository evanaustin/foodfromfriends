<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'grower-operation-id'   => 'required|integer',
    'email'                 => 'required|valid_email|max_len,254'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'grower-operation-id'   => 'trim|whole_number',
	'email'                 => 'trim|sanitize_email',
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$User = new User([
    'DB' => $DB
]);

$response = $User->add([
    'grower_operation_id'   => $grower_operation_id,
    'email'                 => $email
], 'interest_signups');

echo json_encode($json);

?>