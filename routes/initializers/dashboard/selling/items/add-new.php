<?php

$settings = [
    'title' => 'Add a new item | Food From Friends'
];

$category_id    = \Num::clean_int($_GET['category']);
$subcategory_id = \Num::clean_int($_GET['subcategory']);

$Item = new Item([
    'DB' => $DB
]);

$categories    = $Item->retrieve([
    'table' => 'item_categories'
]);

$subcategories = $Item->retrieve([
    'table' => 'item_subcategories'
]);

$varieties     = $Item->retrieve([
    'table' => 'item_varieties'
]);

$varieties     = $Item->retrieve([
    'table' => 'item_varieties'
]);
    
if (!empty($subcategory_id)) {
    $lim_varieties = $Item->retrieve([
        'table' => 'item_varieties',
        'where' => [
            'item_subcategory_id' => $subcategory_id
        ]
    ]);
}
$package_types  = $Item->retrieve([
    'table' => 'item_package_types'
]);

$metrics        = $Item->retrieve([
    'table' => 'item_metrics'
]);

$similar_items  = $Item->retrieve([
    'where' => [
        'grower_operation_id'   => $User->GrowerOperation->id,
        'item_subcategory_id'   => $subcategory_id
    ]
]);

$item_images    = [];

?>