<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'grower_operation_id' => 'required|integer',
	'delivery_settings_id' => 'required|integer', // pass in 0 for this and meetup_settings_id if reverting to pickup
	'meetup_settings_id' => 'required|integer'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'grower_operation_id' => 'trim|sanitize_numbers',
	'delivery_settings_id' => 'trim|sanitize_numbers',
	'meetup_settings_id' => 'trim|sanitize_numbers'
]);

$prepared_data = $Gump->run($validated_data);

// Add to cart
// ----------------------------------------------------------------------------
try {
	$Order = new Order();
	$Order = $Order->get_cart($User->id);

	$GrowerOperation = new GrowerOperation(['id' => $prepared_data['grower_operation_id']]);

	$Order->set_exchange_method(
		$GrowerOperation, 
		$prepared_data['exchange_type'], 
		$prepared_data['delivery_settings_id'], 
		$prepared_data['meetup_settings_id']
	);
} catch (\Exception $e) {
	quit($e->getMessage());
}



echo json_encode($json);