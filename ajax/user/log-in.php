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
    'timezone'      => 'required|regex,/^[A-Za-z\/\_]+$/',
    'operation-key' => 'min_len,4',
    'personal-key'  => 'min_len,7'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'email'     => 'trim|sanitize_email',
	'password'  => 'trim|sanitize_string',
	'timezone'  => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

// check if already logged in
if ($LOGGED_IN) $User->soft_log_out();

$User = new User([
    'DB' => $DB
]);

/* if (!$User->exists('email', $email)) {
    quit('You don\'t have an account with us yet');
} */

// authenticate login
$user_id = $User->authenticate($email, $password);

if (!$user_id) {
    quit('The credentials you provided are incorrect');
}

// check if joining team
if (!empty($operation_key) && !empty($personal_key)) {
    // should probably search by both referral key & operation ID
    $association = $User->retrieve([
        'where' => [
            'referral_key' => $personal_key
        ],
        'table' => 'grower_operation_members'
    ]);
    
    $association = $association[0];

    // make sure association exists
    if ($association) {

        // make sure freshly logged in user belongs to association
        if ($association['user_id'] == $user_id) {
            
            $GrowerOperation = new GrowerOperation([
                'DB' => $DB,
                'id' => $association['grower_operation_id']
            ]);

            // make sure operation key is legit
            if ($GrowerOperation->referral_key == $operation_key) {
                
                // make sure personal key is unused
                if ($association['permission'] == 0) {
                    // check if team elsewhere
                    $team_elsewhere = $GrowerOperation->check_team_elsewhere($user_id);
                    
                    // update user association/permission
                    $association_added = $GrowerOperation->update([
                        'permission'    => 1,
                        'is_default'    => ($team_elsewhere ? 0 : 1)
                    ], 'referral_key' , $personal_key, 'grower_operation_members');
                
                    if (!$association_added) quit('Could not join team');
                } else {
                    quit('You\'re already a member of this team');
                }
            } else {
                quit('Your operation key is invalid');
            }
        } else {
            quit('You were not invited to this team');
        }
    } else {
        quit('Your personal key is invalid');
    }
}

$logged_in = $User->log_in($user_id);

if ($logged_in < 1) {
    quit('We could not log you in');
}

$User = new User([
    'DB' => $DB,
    'id' => $user_id,
    'buyer_account' => false,
    'seller_account' => false
]);

if ($timezone != $User->timezone) {
    $User->update([
        'timezone' => $timezone
    ]);
}

if (isset($redirect) && $redirect == 'false') {
    $json['redirect'] = false;
} else {
    $json['redirect'] = PUBLIC_ROOT;
}

echo json_encode($json);

?>