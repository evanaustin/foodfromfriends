<?php

$settings = [
    'title' => 'Your wholesale buyers | Food From Friends'
];

if ($User->GrowerOperation) {
    $wholesale_relationships = $User->GrowerOperation->retrieve([
        'where' => [
            'seller_id' => $User->GrowerOperation->id
        ],
        'table' => 'wholesale_relationships'
    ]);
}

?>