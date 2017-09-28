<?php

require 'config.php';

if (ENV == 'prod' && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on')) {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

$Routing = new Routing([
    'path'      => $_GET['path'],
    'landing'   => 'splash',
    'backside'  => [
        'grower',
        // 'stats',
        // 'profile',
        'account'
        // 'messages'
    ]
]);

include SERVER_ROOT . 'routes/template.php';

?>