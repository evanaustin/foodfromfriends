<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

foreach ($_POST as $k => $v) ${str_replace('-', '_', $k)} = $v;

$Gump->validation_rules([
    'user-id'   => 'required|integer',
    'grower-operation-id' => 'required|integer',
    'sent-by'   => 'required|alpha',
    'message'   => 'required'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors()[0]);
}

$Gump->filter_rules([
	'user-id'   => 'trim|whole_number',
    'grower-operation-id' => 'trim|whole_number',
    'sent-by'   => 'trim|sanitize_string',
	'message'   => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$Message = new Message([
    'DB' => $DB
]);

$message_sent = $Message->add([
    'user_id'   => $user_id,
    'grower_operation_id' => $grower_operation_id,
    'body'      => $message,
    'sent_by'   => $sent_by,
    'sent_on'   => \Time::now()
]);

if (!$message_sent) {
    quit('Could not send message! Please try again');
}

echo json_encode($json);

?>