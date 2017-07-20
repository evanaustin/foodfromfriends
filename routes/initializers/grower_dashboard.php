<?php

$Food_listing = new Food_listing([
    'DB' => $DB
]);

$categories = $Food_listing->retrieve('food_category');
$subcategories_veg = $Food_listing->retrieve('food_subcategory', 'food_category_id','1');
$subcategories_fruit = $Food_listing->retrieve('food_subcategory', 'food_category_id','2');
$subcategories_egg = $Food_listing->retrieve('food_subcategory', 'food_category_id','3');
$subcategories_dairy = $Food_listing->retrieve('food_subcategory', 'food_category_id','4');
$subcategories_meat = $Food_listing->retrieve('food_subcategory', 'food_category_id','5');
$subcategories_sea = $Food_listing->retrieve('food_subcategory', 'food_category_id','6');
$subcategories_bev = $Food_listing->retrieve('food_subcategory', 'food_category_id','7');
$subcategories_herb = $Food_listing->retrieve('food_subcategory', 'food_category_id','8');

?>