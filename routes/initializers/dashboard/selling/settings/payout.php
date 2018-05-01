<?php

$settings = [
    'title' => 'Your payout settings | Food From Friends'
];

if ($User->GrowerOperation) {
    $payout_settings = $User->GrowerOperation->retrieve([
        'where' => [
            'seller_id' => $User->GrowerOperation->id
        ],
        'table' => 'seller_payout_settings',
        'limit' => 1
    ]);
}

?>