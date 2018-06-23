<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'seller-id' => 'required|integer',
	'exchange-option'   => 'required|alpha'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'seller-id'	=> 'trim|sanitize_numbers',
	'exchange-option'   => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

// Update exchange method
// ----------------------------------------------------------------------------
try {
	$Order = new Order([
		'DB' => $DB
	]);

	$Order = $Order->get_cart($User->BuyerAccount->id);

	if (!isset($Order->Growers[$seller_id])) {
		quit('You are not ordering from this seller');
	}

	$OrderGrower = $Order->Growers[$seller_id];
    
    $OrderGrower->Exchange->set_type($exchange_option);

	$json['ordergrower'] = [
		'id'		=> $OrderGrower->id,
		'exchange'	=> $OrderGrower->Exchange->type,
		'ex_fee'	=> ($OrderGrower->Exchange->fee > 0 ? _amount($OrderGrower->Exchange->fee) : 'Free'),
	];

	$json['order'] = [
		'subtotal'	=> _amount($Order->subtotal),
		'ex_fee'	=> _amount($Order->exchange_fees),
		'fff_fee'	=> _amount($Order->fff_fee),
		'total'		=> _amount($Order->total)
	];
} catch (\Exception $e) {
	quit($e->getMessage());
}



echo json_encode($json);