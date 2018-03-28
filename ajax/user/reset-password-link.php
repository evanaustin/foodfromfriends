<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'email' => 'required|valid_email|max_len,254'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'email' => 'trim|sanitize_email'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

// check if already logged in
if ($LOGGED_IN) $User->soft_log_out();

$User = new User([
    'DB' => $DB
]);

if ($User->exists('email', $email)) {
    $Mail = new Mail([
        'DB' => $DB,
        'fromName' => 'Food From Friends',
        'fromEmail' => 'foodfromfriendsco@gmail.com',
        'toEmail' => $email
    ]);

    $Mail->reset_password_link($email);
} else {
    quit('There will be a reset password link in your inbox if the email entered belongs to you and is associated with an account');
}

echo json_encode($json);

?>