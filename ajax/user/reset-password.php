<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'token-email'       => 'required|valid_email|max_len,254',
    'email'             => 'required|valid_email|max_len,254',
    'password'          => 'required|min_len,8',
    'confirm-password'  => 'required|min_len,8'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
    'token-email'   => 'trim|sanitize_email',
    'email'         => 'trim|sanitize_email',
    'password'      => 'trim|sanitize_string',
    'new_password'  => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

// check if already logged in
if ($LOGGED_IN) $User->soft_log_out();

$User = new User([
    'DB' => $DB
]);

if ($password != $confirm_password) quit('The passwords you entered do not match');

if (!($User->exists('email', $token_email)) || !($User->exists('email', $email)) || $token_email != $email) {
    quit('Your password could not be reset');
} else {
    $reset = $User->reset_password($email, $password);

    if (!$reset) quit('We could not reset your password');
}

echo json_encode($json);

?>