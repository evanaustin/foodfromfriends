<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if ($LOGGED_IN) {
    $success = $User->log_out();
    if (!$success) quit('We couldn\'t log you out.');
} else {
    quit('You weren\'t logged in');
}

echo json_encode($json);

?>