<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

foreach ($_POST as $k => $v) if (isset($v) && !empty($v)) ${$k} = rtrim($v);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    quit('<i class="fa fa-exclamation-triangle"></i> Please enter a valid email.');
}

// set referral keys
$referral_keys = [
    'operation' => $User->GrowerOperation->referral_key,
    'personal'  => $User->GrowerOperation->gen_referral_key(7)
];

$Mail = new Mail([
    'fromName' => 'Food From Friends',
    'fromEmail' => 'foodfromfriendsco@gmail.com',
    'toEmail' => $email
]);

$invitee = $User->retrieve([
    'where' => [
        'email' => $email
    ]
]);

if (!$invitee) {
    // new user

    // create association
    $association_added = $User->GrowerOperation->add([
        'grower_operation_id'   => $User->GrowerOperation->id,
        'user_id'               => 0,
        'permission'            => 0,
        'referral_key'          => $referral_keys['personal']
    ], 'grower_operation_members');

    // send sign up email
    $send = $Mail->team_invite_grower_signup($User, $User->GrowerOperation, $referral_keys);
} else {
    // existing user

    // check to see if association exists
    $association = $User->GrowerOperation->check_association($invitee[0]['id'], $User->GrowerOperation->id);

    if (!$association) {
        // create association
        $association_added = $User->GrowerOperation->add([
            'grower_operation_id'   => $User->GrowerOperation->id,
            'user_id'               => $invitee[0]['id'],
            'permission'            => 0,
            'referral_key'          => $referral_keys['personal']
        ], 'grower_operation_members');
    
        // send join team email
        $send = $Mail->team_invite_grower_join($User, $User->GrowerOperation, $referral_keys);
    } else {
        quit('This person has already been invited to this team.');
    }
}

echo json_encode($json);

?>