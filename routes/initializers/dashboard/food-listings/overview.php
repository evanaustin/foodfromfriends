<?php

$FoodListing = new FoodListing([
    'DB' => $DB
]);

$listings = $FoodListing->get_listings($User->id);

?>