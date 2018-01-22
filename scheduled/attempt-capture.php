<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$Order = new Order([
    'DB' => $DB,
    'id' => $_GET['order']
]);

try {
    $Stripe = new Stripe();

    if ($Order->total > 0) {
        $Stripe->capture($Order->stripe_charge_id, $Order->total);
    } else {
        $Stripe->refund($Order->stripe_charge_id);
    }
} catch(\Exception $e) {
    error_log($e->getMessage());
}

?>