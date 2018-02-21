<?php

$settings = [
    'title' => 'Edit listing | Food From Friends'
];

$FoodListing = new FoodListing([
    'DB' => $DB,
    'S3' => $S3,
    'id' => $_GET['id']
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