<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

foreach ($_POST as $k => $v) ${str_replace('-', '_', $k)} = $v;

$Gump->validation_rules([
    'buyer-account-id'  => 'required|integer',
    'sent-by'           => 'required|alpha',
    'message'           => 'required'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors()[0]);
}

$Gump->filter_rules([
	'buyer-account-id'  => 'trim|whole_number',
    'sent-by'           => 'trim|sanitize_string',
	'message'           => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$Message = new Message([
    'DB' => $DB
]);

$message_recorded = $Message->add([
    'buyer_account_id'      => $buyer_account_id,
    'grower_operation_id'   => $User->GrowerOperation->id,
    'body'                  => $message,
    'sent_by'               => $sent_by,
    'sent_on'               => \Time::now()
]);

if (!$message_recorded) {
    quit('Could not send message! Please try again');
}

$BuyerAccount = new BuyerAccount([
    'DB' => $DB,
    'id' => $buyer_account_id
],[
    'team' => true
]);

foreach ($BuyerAccount->TeamMembers as $Member) {
    $Mail = new Mail([
        'fromName'  => 'Food From Friends',
        'fromEmail' => 'foodfromfriendsco@gmail.com',
        'toName'    => $Member->name,
        'toEmail'   => $Member->email
    ]);
}

$Mail->buyer_new_message_notification($Member, $BuyerAccount, $User->GrowerOperation, $message);

echo json_encode($json);

?>