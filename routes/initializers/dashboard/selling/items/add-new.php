<?php

$settings = [
    'title' => 'Add a new item | Food From Friends'
];

$category_id    = (isset($_GET['category']))    ? \Num::clean_int($_GET['category']) : 0;
$subcategory_id = (isset($_GET['subcategory'])) ? \Num::clean_int($_GET['subcategory']) : 0;
$variety_id     = (isset($_GET['variety']))     ? \Num::clean_int($_GET['variety']) : 0;

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

$where_similar = [
    'grower_operation_id'   => $User->GrowerOperation->id,
    'item_subcategory_id'   => $subcategory_id
];

if (!empty($variety_id)) {
    $where_similar['item_variety_id'] = $variety_id;
}

$similar_items  = $Item->retrieve([
    'where' => $where_similar
]);

$item_images    = [];

?>