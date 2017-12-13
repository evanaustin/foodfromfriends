<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$User->switch_operation($_POST['grower_operation_id']);

$json['redirect'] = 'dashboard/grower';

echo json_encode($json);

?>