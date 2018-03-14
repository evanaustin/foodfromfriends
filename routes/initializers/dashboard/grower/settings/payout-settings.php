<?php

$settings = [
    'title' => 'Payout settings | Food From Friends'
];

$payout_settings = $User->GrowerOperation->retrieve([
    'where' => [
        'seller_id' => $User->GrowerOperation->id
    ],
    'table' => 'seller_payout_settings',
    'limit' => 1
]);

?>