<?php

$settings = [
    'title' => 'Add new food listing | Food From Friends'
];

$FoodListing = new FoodListing([
    'DB' => $DB
]);

$food_categories = $FoodListing->get_categories();

$food_subcategories = $FoodListing->get_subcategories();

?>