<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'email'         => 'required|valid_email|max_len,254',
    'password'      => 'required|min_len,8',
    'first-name'    => 'required|alpha_dash',
    'last-name'     => 'required|alpha_dash',
    'day'           => 'required|integer',
    'month'         => 'required|alpha',
    'year'          => 'required|integer',
    'timezone'      => 'required|regex,/^[A-Za-z\/\_]+$/'
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
	'year'          => 'trim|whole_number',
	'timezone'      => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$dob = strtotime($day . ' ' . $month . ' ' . $year);

if ($dob > strtotime('-18 years')) {
    quit('You must be 18 or older to sign up');
}

$User = new User([
    'DB' => $DB
]);

if ($User->exists('email', $email)) {
    quit('An existing account is already using this email');
}

$date = DateTime::createFromFormat('d-F-Y H:i:s', "{$day}-{$month}-{$year} 12:00:00");
$dob = $date->format('Y-m-d H:i:s');

$Slug = new Slug([
    'DB' => $DB
]);

$slug = $Slug->slugify_name("{$first_name} {$last_name}", 'users');

if (empty($slug)) {
    throw new \Exception('Slug generation failed');
}

$new_user = $User->add([
    'email'         => $email,
    'password'      => hash('sha256', $password),
    'first_name'    => ucfirst($first_name),
    'last_name'     => ucfirst($last_name),
    'dob'           => $dob,
    'slug'          => $slug,
    'registered_on' => \Time::now(),
    'timezone'      => $timezone
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

if (isset($redirect) && $redirect == 'false') {
    $json['redirect'] = false;
} else if (isset($redirect)) {
    $json['redirect'] = $redirect;
} else if (isset($GrowerOperation)) {
    $json['redirect'] = PUBLIC_ROOT . 'dashboard/selling';
} else {
    $json['redirect'] = PUBLIC_ROOT . 'dashboard/account/edit-profile/basic-information';
}

echo json_encode($json);

?>