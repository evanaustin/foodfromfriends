<?php

$settings = [
    'title' => 'Your item listings | Food From Friends'
];

$FoodListing = new FoodListing([
    'DB' => $DB
]);

$listings = $FoodListing->get_all_listings($User->GrowerOperation->id);

$listing_count = count(array_filter($listings));

?>