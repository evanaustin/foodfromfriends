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

$Seller = new GrowerOperation([
    'DB' => $DB,
    'id' => $seller_id
], [
    'team' => true
]);

$invitee = $User->BuyerAccount->retrieve([
    'where' => [
        'buyer_account_id' => $User->BuyerAccount->id,
        'seller_id' => $seller_id
    ],
    'table' => 'wholesale_relationships'
]);

if (!$invitee) {
    $association_added = $User->GrowerOperation->add([
        'buyer_account_id'  => $User->BuyerAccount->id,
        'seller_id'         => $Seller->id,
        'status'            => 1
    ], 'wholesale_relationships');

    foreach ($Seller->TeamMembers as $Member) {
        $Mail = new Mail([
            'fromName'  => 'Food From Friends',
            'fromEmail' => 'foodfromfriendsco@gmail.com',
            'toName'    => $Member->name,
            'toEmail'   => $Member->email
        ]);
        
        $Mail->new_wholesale_request($Member, $Seller, $User->BuyerAccount);
    }
} else {
    quit("You have already requested membership with {$Seller->name}");
}

echo json_encode($json);

?>