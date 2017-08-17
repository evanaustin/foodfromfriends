<?php

$FoodListing = new FoodListing([
    'DB' => $DB
]);

$listings = $FoodListing->get_listings($User->id);

$listing_count = count(array_filter($listings));

?>