<?php

$settings = [
    'title' => 'Your food listings | Food From Friends'
];

$FoodListing = new FoodListing([
    'DB' => $DB
]);

$listings = $FoodListing->get_listings($User->GrowerOperation->id);

$listing_count = count(array_filter($listings));

?>