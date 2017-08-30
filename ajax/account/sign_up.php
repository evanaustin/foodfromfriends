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

$dob = strtotime($day . ' ' . $month . ' ' . $year);

$User = new User([
    'DB' => $DB
]);

// run checks
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    quit('Please enter a valid email');
} else if (strlen($password) < 8) {
    quit('Your password should have 8 characters or more');
} else if ($dob > strtotime('-18 years')) {
    quit('You must be 18 or older to sign up');
} else if ($User->exists('email', $email)) {
    quit('An existing account is already using this email');
}

$new_user = $User->add([
    'email' => $email,
    'password' => hash('sha256', $password),
    'first_name' => $first_name,
    'last_name' => $last_name,
    'dob' => $dob,
    'registered_on' => time()
]);

if ($new_user != false) {
    $logged_in = $User->log_in($new_user['last_insert_id']);
    if (!$logged_in) quit('We couldn\'t automatically log you in');
} else {
    quit('We couldn\'t create your account');
}

echo json_encode($json);

?>