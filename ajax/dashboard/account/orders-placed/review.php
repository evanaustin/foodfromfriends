<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'ordergrower-id'    => 'required|integer',
	'seller-score'      => 'required|integer',
	'seller-review'     => 'required'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'ordergrower-id'    => 'trim|sanitize_numbers',
    'seller-score'      => 'trim|sanitize_numbers',
    'seller-review'     => 'trim|sanitize_string'
]);

$prepared_data = $Gump->run($validated_data);

try {
	$OrderGrower = new OrderGrower([
        'DB' => $DB,
        'id' => $prepared_data['ordergrower-id']
    ]);

    $OrderGrower->review($prepared_data);
    
    $Seller = new GrowerOperation([
        'DB' => $DB,
        'id' => $OrderGrower->grower_operation_id
    ],[
        'details' => true,
        'team' => true
    ]);

    foreach ($Seller->TeamMembers as $Member) {
        $Mail = new Mail([
            'fromName'  => 'Food From Friends',
            'fromEmail' => 'foodfromfriendsco@gmail.com',
            'toName'   => $Member->name,
            'toEmail'   => $Member->email
        ]);
        
        $Mail->reviewed_order_notification($Member, $Seller, $OrderGrower, $User);
    }
} catch (\Exception $e) {
	quit($e->getMessage());
}

echo json_encode($json);