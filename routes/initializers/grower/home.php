<?php

$FoodListing = new FoodListing([
    'DB' => $DB
]);

$categories = $FoodListing->retrieve('food_categories');
$subcategories_veg = $FoodListing->retrieve('food_subcategories', 'food_category_id','1');
$subcategories_fruit = $FoodListing->retrieve('food_subcategories', 'food_category_id','2');
$subcategories_egg = $FoodListing->retrieve('food_subcategories', 'food_category_id','3');
$subcategories_dairy = $FoodListing->retrieve('food_subcategories', 'food_category_id','4');
$subcategories_meat = $FoodListing->retrieve('food_subcategories', 'food_category_id','5');
$subcategories_sea = $FoodListing->retrieve('food_subcategories', 'food_category_id','6');
$subcategories_bev = $FoodListing->retrieve('food_subcategories', 'food_category_id','7');
$subcategories_herb = $FoodListing->retrieve('food_subcategories', 'food_category_id','8');

?>