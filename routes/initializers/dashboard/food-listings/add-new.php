<?php

$FoodListing = new FoodListing([
    'DB' => $DB
]);

$food_categories = $FoodListing->get_categories();

$food_subcategories = $FoodListing->get_subcategories();

?>