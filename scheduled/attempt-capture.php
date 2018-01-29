<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$Order = new Order([
    'DB' => $DB,
    'id' => $_GET['order']
]);

try {
    if ($Order->total > 0) {
        $Order->capture();
    } else {
        $Order->release();
    }
} catch(\Exception $e) {
    error_log($e->getMessage());
}

?>