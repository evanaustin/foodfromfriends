<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'ordergrower-id'    => 'required|integer',
	'seller-score'      => 'integer',
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'ordergrower-id'    => 'trim|sanitize_numbers',
    'seller-score'      => 'trim|sanitize_numbers',
    'seller-review'     => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

try {
	$OrderGrower = new OrderGrower([
        'DB' => $DB,
        'id' => $prepared_data['ordergrower-id']
    ]);

    $OrderGrower->review($prepared_data);
} catch (\Exception $e) {
	quit($e->getMessage());
}

echo json_encode($json);