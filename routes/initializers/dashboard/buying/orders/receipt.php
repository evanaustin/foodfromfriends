<?php

$settings = [
    'title' => 'Order recepit | Food From Friends'
];

$encrypted_id = $_GET['id'];
$id = (explode('-', $encrypted_id)[1]) / 3;

$Order = new Order([
    'DB' => $DB,
    'id' => $id
]);
    
if ($Order->buyer_account_id == $User->BuyerAccount->id) {
    $settings = [
        'title' => "Order ({$encrypted_id}) receipt | Food From Friends"
    ];
}

?>