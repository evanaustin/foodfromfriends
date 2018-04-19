<?php

$settings = [
    'title' => 'Your wholesale buyers | Food From Friends'
];

if ($User->GrowerOperation) {
    $wholesale_memberships = $User->GrowerOperation->retrieve([
        'where' => [
            'seller_id' => $User->GrowerOperation->id
        ],
        'table' => 'wholesale_account_memberships'
    ]);
}

?>