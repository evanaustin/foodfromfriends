<?php

$settings = [
    'title' => 'Add a new item listing | Food From Friends'
];

$FoodListing = new FoodListing([
    'DB' => $DB
]);

$item_categories    = $FoodListing->retrieve([
    'table' => 'food_categories'
]);

$item_subcategories = $FoodListing->retrieve([
    'table' => 'food_subcategories'
]);

$item_varieties     = $FoodListing->retrieve([
    'table' => 'item_varieties'
]);

?>