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

// initialize empty User object
$User = new User([
    'DB' => $DB
]);

// ensure new User is 18+ yrs old
if (strtotime("{$day} {$month} {$year}") > strtotime('-18 years')) {
    quit('You must be 18 or older to sign up');
}

// ensure User w/ email doesn't exist
if ($User->exists('email', $email)) {
    quit('An existing account is already using this email');
}

// prepare DOB
$date   = DateTime::createFromFormat('d-F-Y H:i:s', "{$day}-{$month}-{$year} 12:00:00");
$dob    = $date->format('Y-m-d H:i:s');


/*
 * Add User
 */

$new_user = $User->add([
    'email'         => $email,
    'password'      => hash('sha256', $password),
    'first_name'    => ucfirst($first_name),
    'last_name'     => ucfirst($last_name),
    'dob'           => $dob,
    'registered_on' => \Time::now(),
    'timezone'      => $timezone
]);


/*
 * Log in User
 */

if ($new_user != false) {
    $logged_in = $User->log_in($new_user['last_insert_id']);
    
    if (!$logged_in) {
        quit('We couldn\'t automatically log you in');
    }
} else {
    quit('We couldn\'t create your account');
}


/*
 * Create User:BuyerAccount
 */

$BuyerAccount = new BuyerAccount([
    'DB' => $DB
]);

try {
    $buyer_account_id = $BuyerAccount->create($USER['id'], [
        'name'  => "{$first_name} {$last_name}",
        'type'  => 1
    ],[
        'is_default' => 1
    ]);
} catch (\Exception $e) {
    quit($e->getMessage());
}


/*
 * Create User:GrowerOperation
 */

$SellerAccount = new GrowerOperation([
    'DB' => $DB
]);

try {
    $seller_account_id = $SellerAccount->create($USER['id'], [
        'name'  => "{$first_name} {$last_name}",
        'type'  => 1
    ],[
        'is_default' => 1
    ]);
} catch (\Exception $e) {
    quit($e->getMessage());
}


/* 
 * Reinitialize User w/ Accounts
 */

$User = new User([
    'DB' => $DB,
    'id' => $USER['id'],
    'buyer_account' => true,
    'seller_account' => true
]);
    
$User->switch_buyer_account($buyer_account_id);
$User->switch_operation($seller_account_id);


/*
 * Join team
 * @todo: do this for BuyerAccounts
 */

if (!empty($operation_key) && !empty($personal_key)) {

    // update user association as manager
    $association_added = $SellerAccount->update([
        'user_id'       => $user_id,
        'permission'    => 1,
        'is_default'    => 1
    ], 'referral_key' , $personal_key, 'grower_operation_members');

    if (!$association_added) quit('Could not join team');
}


/*
 * Send trans email notification
 */

$Mail = new Mail([
    'fromName'  => 'Food From Friends',
    'fromEmail' => 'foodfromfriendsco@gmail.com',
    'toEmail'   => $email
]);

$Mail->thanks_signup();


/*
 * Redirect after completion
 */

if (isset($redirect) && $redirect == 'false') {
    $json['redirect'] = false;
} else if (isset($redirect)) {
    $json['redirect'] = $redirect;
} else {
    $json['redirect'] = PUBLIC_ROOT . 'dashboard/account/settings/personal';
}


echo json_encode($json);