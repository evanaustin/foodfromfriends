<?php

$settings = [
    'title' => 'Your items | Food From Friends'
];

$Item = new Item([
    'DB' => $DB
]);

$raw_categories = $Item->retrieve([
    'table' => 'item_categories'
]);

$hashed_categories = [];

foreach($raw_categories as $raw_category) {
    if (!isset($hashed_categories[$raw_category['id']])) {
        $hashed_categories[$raw_category['id']] = $raw_category['title'];
    }
}

$raw_subcategories = $Item->retrieve([
    'table' => 'item_subcategories'
]);

$hashed_subcategories = [];

foreach($raw_subcategories as $raw_subcategory) {
    if (!isset($hashed_subcategories[$raw_subcategory['id']])) {
        $hashed_subcategories[$raw_subcategory['id']] = $raw_subcategory['title'];
    }
}

$raw_varieties = $Item->retrieve([
    'table' => 'item_varieties'
]);

$hashed_varieties = [];

foreach($raw_varieties as $raw_variety) {
    if (!isset($hashed_varieties[$raw_variety['id']])) {
        $hashed_varieties[$raw_variety['id']] = $raw_variety['title'];
    }
}

$raw_items = $Item->get_items($User->GrowerOperation->id, [
    'is_wholesale' => true
]);

$hashed_items = [];

foreach($raw_items as $raw_item) {
    if (!isset($hashed_items[$raw_item['item_category_id']])) {
        $hashed_items[$raw_item['item_category_id']] = [];
    }
    
    if (!isset($hashed_items[$raw_item['item_category_id']][$raw_item['item_subcategory_id']])) {
        $hashed_items[$raw_item['item_category_id']][$raw_item['item_subcategory_id']] = [];
    }
    
    if (!isset($hashed_items[$raw_item['item_category_id']][$raw_item['item_subcategory_id']][$raw_item['item_variety_id']])) {
        $hashed_items[$raw_item['item_category_id']][$raw_item['item_subcategory_id']][$raw_item['item_variety_id']] = [];
    }

    $hashed_items[$raw_item['item_category_id']][$raw_item['item_subcategory_id']][$raw_item['item_variety_id']][$raw_item['id']] = new Item([
        'DB' => $DB,
        'id' => $raw_item['id']
    ]);
    
    // $hashed_items[$raw_item['item_category_id']][$raw_item['item_subcategory_id']][$raw_item['item_variety_id']][$raw_item['id']] = $raw_item['id'];
}

$package_types = $Item->retrieve([
    'table' => 'item_package_types'
]);

$metrics = $Item->retrieve([
    'table' => 'item_metrics'
]);

// $item_count = count(array_filter($raw_items));

?>