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

	if (!isset($Order->Growers[$grower_operation_id])) {
		quit('You are not ordering from this grower');
	}

	$OrderGrower = $Order->Growers[$grower_operation_id];
    
    $OrderGrower->Exchange->set_type($exchange_option);

	$json['ordergrower'] = [
		'id'		=> $OrderGrower->id,
		'exchange'	=> $OrderGrower->Exchange->type,
		'ex_fee'	=> ($OrderGrower->Exchange->fee > 0 ? '$' . number_format($OrderGrower->Exchange->fee / 100, 2) : 'Free'),
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