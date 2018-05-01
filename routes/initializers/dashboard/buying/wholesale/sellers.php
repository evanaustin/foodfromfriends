<?php

$settings = [
    'title' => 'Your wholesale sellers | Food From Friends'
];

if ($User->BuyerAccounts) {
    $wholesale_memberships = $User->BuyerAccount->retrieve([
        'where' => [
            'buyer_account_id' => $User->BuyerAccount->id
        ],
        'table' => 'wholesale_relationships'
    ]);
}

?>