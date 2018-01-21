<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$OrderGrower = new OrderGrower([
    'DB' => $DB,
    'id' => $_GET['suborder']
]);

try {
    $OrderGrower->clear();
    
    error_log("Suborder {$OrderGrower->id} cleared");
} catch(\Exception $e) {
    error_log("Suborder {$OrderGrower->id} not cleared");
}

?>