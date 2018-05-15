<?php

$settings = [
    'title' => 'Message thread | Food From Friends'
];

$seller_id = $_GET['seller'];

$seller_id = \Num::clean_int($_GET['seller']);

$Seller = new GrowerOperation([
    'DB' => $DB,
    'id' => $seller_id
],[
    'details' => true
]);

$Message = new Message([
    'DB' => $DB
]);

$messages = $Message->retrieve([
    'where' => [
        'buyer_account_id'      => $User->BuyerAccount->id,
        'grower_operation_id'   => $Seller->id,
    ],
    'order' => 'sent_on ASC'
]);

?>