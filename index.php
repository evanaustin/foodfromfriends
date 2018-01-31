<?php

require 'config.php';

if ((ENV == 'prod' || ENV == 'stage') && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on')) {
    header('Location: https://www.' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

$Routing = new Routing([
    'path'      => $_GET['path'],
    'landing'   => (ENV == 'prod' ? 'splash' : 'map')
]);

include SERVER_ROOT . 'routes/template.php';

?>