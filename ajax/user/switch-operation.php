<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

if (!$LOGGED_IN) quit('You are not logged in');

$json['error'] = null;
$json['success'] = true;

$User->switch_operation($_POST['grower_operation_id']);

$json['redirect'] = 'dashboard/selling/';

echo json_encode($json);

?>