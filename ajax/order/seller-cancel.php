<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$_POST = $Gump->sanitize($_POST);

$Gump->validation_rules([
	'ordergrower_id' => 'required|integer'
]);

$validated_data = $Gump->run($_POST);

if ($validated_data === false) {
    quit($Gump->get_readable_errors());
}

$Gump->filter_rules([
	'ordergrower_id' => 'trim|sanitize_numbers'
]);

$prepared_data = $Gump->run($validated_data);

try {
	$OrderGrower = new OrderGrower([
        'DB' => $DB,
        'id' => $prepared_data['ordergrower_id']
    ]);

    $cancelled = $OrderGrower->Status->seller_cancel();
    
    if (!$cancelled) {
        quit('Oops! You cannot cancel this order');
    }

    $Buyer = new User([
        'DB' => $DB,
        'id' => $OrderGrower->user_id
    ]);
    
    $Seller = new GrowerOperation([
        'DB' => $DB,
        'id' => $OrderGrower->grower_operation_id
    ],[
        'details' => true
    ]);

    $Mail = new Mail([
        'fromName'  => 'Food From Friends',
        'fromEmail' => 'foodfromfriendsco@gmail.com',
        'toName'    => $Buyer->name,
        'toEmail'   => $Buyer->email
    ]);
    
    $Mail->seller_cancelled_order_notification($Buyer, $OrderGrower, $Seller);
} catch (\Exception $e) {
	quit($e->getMessage());
}

echo json_encode($json);