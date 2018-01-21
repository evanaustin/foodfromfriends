<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$OrderGrower = new OrderGrower([
    'DB' => $DB,
    'id' => $_GET['order']
]);

try {
    $Order->attempt_capture();
    
    error_log("Order {$OrderGrower->id} captured");
} catch(\Exception $e) {
    error_log("Order {$OrderGrower->id} voided");
}

?>