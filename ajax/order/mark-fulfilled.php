<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'order_grower_id' => 'required|integer'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'order_grower_id' => 'trim|sanitize_numbers'
]);

$prepared_data = $Gump->run($validated_data);

// Add to cart
// ----------------------------------------------------------------------------
try {
	$OrderGrower = new OrderGrower(['id' => $prepared_data['order_grower_id']]);
	$OrderGrower->mark_fulfilled();
} catch (\Exception $e) {
	quit($e->getMessage());
}



echo json_encode($json);