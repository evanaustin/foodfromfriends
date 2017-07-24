<?php

$User = new User([
    'DB' => $DB,
    'id'=>  1 
]);

$FoodListing = new FoodListing([
    'DB' => $DB,
]);
$Review = new Review([
    'DB' => $DB,
]);

$foodlistings = $FoodListing->join_foodlistings(1);

$available_foodlistings = $FoodListing->retrieve('food_listings', 'is_available','1');

$reviews = $Review->join_reviews(1);

?>