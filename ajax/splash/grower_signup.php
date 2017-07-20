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

$Grower = new Grower([
    'DB' => $DB
]);

$exists = $Grower->exists('growers','email', $email );

if (!$exists){
    $results = $Grower->add('growers', [
        'email' => $email
    ]);

    if (!$results) {
        quit('<i class="fa fa-exclamation-triangle"></i> Could not add local grower!');
    }
} else {
    quit('<i class="fa fa-exclamation-triangle"></i> This email is already in use.');
}

$Mail = new Mail([
    'fromName' => 'Food From Friends',
    'fromEmail' => 'foodfromfriendsco@gmail.com',
    'toEmail' => $email
]);

$Mail->thanks_grower_signup(); 

echo json_encode($json);

?>