<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'relationship_id' => 'integer',
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'relationship_id' => 'trim|sanitize_numbers',
]);

$prepared_data = $Gump->run($validated_data);

$WholesaleRelationship = new WholesaleRelationship([
    'DB' => $DB,
    'id' => $prepared_data['relationship_id']
]);

try {
    $WholesaleRelationship->approve_request();
} catch (\Exception $e) {
    quit($e->getMessage());
}

$BuyerAccount = new BuyerAccount([
    'DB' => $DB,
    'id' => $WholesaleRelationship->buyer_account_id
], [
    'team' => true
]);

$GrowerOperation = new GrowerOperation([
    'DB' => $DB,
    'id' => $WholesaleRelationship->seller_id
]);

foreach ($BuyerAccount->TeamMembers as $Member) {
    $Mail = new Mail([
        'fromName'  => 'Food From Friends',
        'fromEmail' => 'foodfromfriendsco@gmail.com',
        'toName'    => $Member->name,
        'toEmail'   => $Member->email
    ]);
    
    $Mail->wholesale_request_approval($Member, $BuyerAccount, $GrowerOperation);
}

echo json_encode($json);

?>