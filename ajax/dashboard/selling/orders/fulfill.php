<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

use Treffynnon\At\Wrapper as At;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'ordergrower_id' => 'required|integer'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'ordergrower_id' => 'trim|sanitize_numbers'
]);

$prepared_data = $Gump->run($validated_data);

try {
	$OrderGrower = new OrderGrower([
        'DB' => $DB,
        'id' => $prepared_data['ordergrower_id']
    ]);

    $OrderGrower->fulfill();

    // Schedule system job for payout clearing
    if (ENV != 'dev') {
        $job = 'wget -O - ' . PUBLIC_ROOT . 'scheduled/clear.php?suborder=' . $OrderGrower->id;
        $time = 'now + 3 days';
        $queue = 'c';
        At::cmd($job, $time, $queue);
    }
} catch (\Exception $e) {
	quit($e->getMessage());
}

echo json_encode($json);