<?php

$settings = [
    'title' => 'Edit item | Food From Friends'
];

$Item = new Item([
    'DB' => $DB,
    'S3' => $S3,
    'id' => $_GET['id']
]);

$categories     = $Item->retrieve([
    'table' => 'item_categories'
]);

$subcategories  = $Item->retrieve([
    'table' => 'item_subcategories'
]);

$varieties      = $Item->retrieve([
    'table' => 'item_varieties'
]);

$package_types  = $Item->retrieve([
    'table' => 'item_package_types'
]);

$metrics        = $Item->retrieve([
    'table' => 'item_metrics'
]);

$similar_items  = $Item->retrieve([
    'where' => [
        'grower_operation_id'   => $User->GrowerOperation->id,
        'item_subcategory_id'   => $Item->item_subcategory_id,
        'item_variety_id'       => $Item->item_variety_id
    ]
]);

?>