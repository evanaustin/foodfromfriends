<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'membership_id' => 'integer',
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'membership_id' => 'trim|sanitize_numbers',
]);

$prepared_data = $Gump->run($validated_data);

$WholesaleAccount = new WholesaleAccount([
    'DB' => $DB
]);

try {
    $WholesaleAccount->unapprove_membership($prepared_data['membership_id']);
} catch (\Exception $e) {
    quit($e->getMessage());
}

echo json_encode($json);

?>