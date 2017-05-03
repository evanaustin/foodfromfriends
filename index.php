<?php

require 'config.php';

if (ENV == 'prod' && (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on')) {
    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

$path = (empty($_GET['path']) ? 'home' : $_GET['path']);
$page = str_replace('/', '-', $path);

$template       = SERVER_ROOT . 'routes/template.php';
$initializer    = SERVER_ROOT . 'routes/initializers/' . $path . '.php';

$body = [
    'header'    => SERVER_ROOT . 'routes/components/header.php',
    'view'      => SERVER_ROOT . 'routes/views/' . $path . '.php',
    'footer'    => SERVER_ROOT . 'routes/components/footer.php',
    'modal'     => SERVER_ROOT . 'routes/modals/' . $path . '.php',
];

$localScript    = 'js/views/' . $path;

include $template;

?>