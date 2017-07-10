<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

foreach ($_POST as $k => $v) if (isset($v) && !empty($v)) ${$k} = rtrim($v);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    quit('Please enter a valid email.');
}
$Locavore = new Locavore([
    'DB' => $DB
]);

$results = $Locavore->exists('locavores','email', $email );

if ($results == false){  

    $results = $Locavore->add('locavores', [
        'email' => $email
    ]);

    if (!$results) {
        quit('Could not add locavore!');
    }
}
else{
      quit('This email was already used.');
}
 echo json_encode($json);

?>