<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$OrderGrower = new OrderGrower([
    'DB' => $DB,
    'id' => $_GET['suborder']
]);

try {
    $OrderGrower->expire();
} catch(\Exception $e) {
    error_log($e->getMessage());
}

?>