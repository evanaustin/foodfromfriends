<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$Order = new Order([
    'DB' => $DB,
    'id' => $_GET['order']
]);

try {
    if ($Order->Charge->total > 0) {
        $Order->Charge->capture();
    } else {
        $Order->Charge->release();
    }
} catch(\Exception $e) {
    error_log($e->getMessage());
}

?>