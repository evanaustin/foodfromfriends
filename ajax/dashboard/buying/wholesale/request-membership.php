<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
    'seller-id'  => 'integer',
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'seller-id'  => 'trim|sanitize_numbers',
]);

$prepared_data = $Gump->run($validated_data);

foreach ($prepared_data as $k => $v) ${str_replace('-', '_', $k)} = $v;

$invitee = $User->WholesaleAccount->retrieve([
    'where' => [
        'wholesale_account_id' => $User->WholesaleAccount->id
    ],
    'table' => 'wholesale_account_memberships'
]);

$Seller = new GrowerOperation([
    'DB' => $DB,
    'id' => $seller_id
]);

if (!$invitee) {
    $association_added = $User->GrowerOperation->add([
        'wholesale_account_id'  => $User->WholesaleAccount->id,
        'seller_id'             => $Seller->id,
        'status'                => 1
    ], 'wholesale_account_memberships');

    // ! TODO: send trans email to seller team members
} else {
    quit("You have already requested membership with {$Seller->name}");
}

echo json_encode($json);

?>