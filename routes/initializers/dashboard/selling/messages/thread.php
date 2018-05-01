<?php

$settings = [
    'title' => 'Message thread | Food From Friends'
];

$buyer_account_id = \Num::clean_int($_GET['buyer']);

$BuyerAccount = new BuyerAccount([
    'DB' => $DB,
    'id' => $buyer_account_id
]);

$Message = new Message([
    'DB' => $DB
]);

$messages = $Message->retrieve([
    'where' => [
        'buyer_account_id'      => $buyer_account_id,
        'grower_operation_id'   => $User->GrowerOperation->id,
    ],
    'order' => 'sent_on ASC'
]);

?>