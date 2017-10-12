<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$User->switch_operation($_POST['grower_operation_id']);

$json['redirect'] = ($User->GrowerOperation->permission == 2) ? 'dashboard/grower' : 'dashboard/grower/food-listings/overview';

echo json_encode($json);

?>