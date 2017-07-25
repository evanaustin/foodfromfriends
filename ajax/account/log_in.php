<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

foreach ($_POST as $k => $v) {
    if (isset($v) && !empty($v)) {
        ${$k} = rtrim($v);
    } else {
        quit('The ' . strtoupper(str_replace('_', ' ', $k)) . ' field is required');
    }
}

// run checks
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    quit('Please enter a valid email');
} else if (strlen($password) < 8) {
    quit('Your password should have 8 characters or more');
} else if ($LOGGED_IN) {
    quit('You are already logged in');
}

$User = new User([
    'DB' => $DB
]);

if ($User->exists('email', $email)) {
    $logged_in = $User->authenticate($email, $password);

    if (!$logged_in) quit('The credentials you provided are incorrect');
} else {
    quit('You don\'t have an account with us yet');
}

echo json_encode($json);

?>