<?php

$settings = [
    'title' => 'Your wholesale buyers | Food From Friends'
];

if ($User->WholesaleAccounts) {
    $wholesale_memberships = $User->WholesaleAccount->retrieve([
        'where' => [
            'wholesale_account_id' => $User->WholesaleAccount->id
        ],
        'table' => 'wholesale_account_memberships'
    ]);
}

?>