<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'grower-operation-id'	=> 'required|integer',
	'exchange-option'		=> 'required|alpha'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'grower-operation-id'	=> 'trim|sanitize_numbers',
	'exchange-option'		=> 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

// Update exchange method
// ----------------------------------------------------------------------------
try {
	$Order = new Order([
		'DB' => $DB
	]);

	$Order = $Order->get_cart($User->id);

	$GrowerOperation = new GrowerOperation([
		'DB' => $DB,
		'id' => $grower_operation_id
	],[
		'details' => true,
		'exchange' => true
	]);

	if (!isset($Order->Growers[$GrowerOperation->id])) {
		quit('You are not ordering from this grower');
	}

	$Order->set_exchange_method(
		$exchange_option,
		$User,
		$GrowerOperation 
	);

	$OrderGrower = $Order->Growers[$grower_operation_id];

	$json['ordergrower'] = [
		'id'		=> $OrderGrower->id,
		'exchange'	=> ucfirst($OrderGrower->exchange_option),
		'ex_fee'	=> '$' . number_format($OrderGrower->exchange_fee / 100, 2),
	];

	$json['order'] = [
		'subtotal'	=> '$' . number_format($Order->subtotal / 100, 2),
		'ex_fee'	=> '$' . number_format($Order->exchange_fees / 100, 2),
		'fff_fee'	=> '$' . number_format($Order->fff_fee / 100, 2),
		'total'		=> '$' . number_format($Order->total / 100, 2)
	];
} catch (\Exception $e) {
	quit($e->getMessage());
}



echo json_encode($json);