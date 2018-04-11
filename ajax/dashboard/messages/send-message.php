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

$new_message = $Message->add([
    'user_id'   => $user_id,
    'grower_operation_id' => $grower_operation_id,
    'body'      => $message,
    'sent_by'   => $sent_by,
    'sent_on'   => \Time::now()
]);

if (!$new_message) {
    quit('Could not send message! Please try again');
}

$ThisUser = new User([
    'DB' => $DB,
    'id' => $user_id
]);

switch ($sent_by) {
    case 'user':
        $ThisGrowerOperation = new GrowerOperation([
            'DB' => $DB,
            'id' => $grower_operation_id
        ],[
            'details' => true,
            'team' => true
        ]);
        
        foreach ($ThisGrowerOperation->TeamMembers as $Member) {
            $Mail = new Mail([
                'fromName'  => 'Food From Friends',
                'fromEmail' => 'foodfromfriendsco@gmail.com',
                'toName'   => $Member->name,
                'toEmail'   => $Member->email
            ]);
            
            $Mail->grower_new_message_notification($Member, $ThisGrowerOperation, $ThisUser, $message);
        }

        break;
    
    case 'grower':
        $ThisGrowerOperation = new GrowerOperation([
            'DB' => $DB,
            'id' => $grower_operation_id
        ],[
            'details' => true
        ]);

        $Mail = new Mail([
            'fromName'  => 'Food From Friends',
            'fromEmail' => 'foodfromfriendsco@gmail.com',
            'toName'   => $ThisUser->name,
            'toEmail'   => $ThisUser->email
        ]);

        $Mail->user_new_message_notification($ThisUser, $ThisGrowerOperation, $message);

        break;
}


echo json_encode($json);

?>