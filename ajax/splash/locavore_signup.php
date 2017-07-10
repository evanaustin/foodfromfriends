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

$Locavore = new Locavore([
    'DB' => $DB
]);

$exists = $Locavore->exists('locavores','email', $email );

if (!$exists) {  
    $results = $Locavore->add('locavores', [
        'email' => $email
    ]);

    if (!$results) {
        quit('<i class="fa fa-exclamation-triangle"></i> Could not add locavore!');
    }
} else {
    quit('<i class="fa fa-exclamation-triangle"></i> This email is already in use.');
}

echo json_encode($json);

?>