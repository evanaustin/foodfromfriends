<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'email'         => 'required|valid_email',
    'password'      => 'required|min_len,8',
    'first-name'    => 'required|alpha_dash',
    'last-name'     => 'required|alpha_dash',
    'day'           => 'required|integer',
    'month'         => 'required|alpha',
    'year'          => 'required|integer'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'email'         => 'trim|sanitize_email',
	'password'      => 'trim|sanitize_string',
	'first-name'    => 'trim|sanitize_string',
	'last-name'     => 'trim|sanitize_string',
	'day'           => 'trim|whole_number',
	'month'         => 'trim|sanitize_string',
	'year'          => 'trim|whole_number'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$dob = strtotime($day . ' ' . $month . ' ' . $year);

$User = new User([
    'DB' => $DB
]);

// run checks
if ($dob > strtotime('-18 years')) {
    quit('You must be 18 or older to sign up');
} else if ($User->exists('email', $email)) {
    quit('An existing account is already using this email');
}

$new_user = $User->add([
    'email'         => $email,
    'password'      => hash('sha256', $password),
    'first_name'    => $first_name,
    'last_name'     => $last_name,
    'dob'           => $dob,
    'registered_on' => time()
]);

if ($new_user != false) {
    $logged_in = $User->log_in($new_user['last_insert_id']);
    if (!$logged_in) quit('We couldn\'t automatically log you in');
} else {
    quit('We couldn\'t create your account');
}

if (!empty($operation_key) && !empty($personal_key)) {
    $GrowerOperation = new GrowerOperation([
        'DB' => $DB
    ]);

    // update user association as manager
    $association_added = $GrowerOperation->update([
        'user_id'       => $new_user['last_insert_id'],
        'permission'    => 1,
        'is_default'    => 1
    ], 'referral_key' , $personal_key, 'grower_operation_members');

    if (!$association_added) quit('Could not join team');
}

$Mail = new Mail([
    'fromName'  => 'Food From Friends',
    'fromEmail' => 'foodfromfriendsco@gmail.com',
    'toEmail'   => $email
]);

$Mail->thanks_signup();

if (isset($GrowerOperation)) {
    if ($GrowerOperation->permission == 2) {
        $json['redirect'] = PUBLIC_ROOT . 'dashboard/grower';
    } else {
        $json['redirect'] = PUBLIC_ROOT . 'dashboard/grower/food-listings/overview';
    }
} else {
    $json['redirect'] = PUBLIC_ROOT . 'map';
}

echo json_encode($json);

?>