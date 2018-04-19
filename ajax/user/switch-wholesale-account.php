<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

if (!$LOGGED_IN) quit('You are not logged in');

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'wholesale_account_id' => 'required|integer'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'wholesale_account_id' => 'trim|sanitize_numbers'
]);

$prepared_data = $Gump->run($validated_data);

$User->switch_wholesale_account($prepared_data['wholesale_account_id']);

$json['redirect'] = 'dashboard/buying/orders/overview';

echo json_encode($json);

?>