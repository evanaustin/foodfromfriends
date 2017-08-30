<?php

$settings = [
    'title' => 'Edit listing | Food From Friends'
];

$FoodListing = new FoodListing([
    'DB' => $DB,
    'S3' => $S3,
    'id' => $_GET['id']
]);

$listing_title = ucfirst(!empty($FoodListing->subcategory_title) && empty($FoodListing->other_subcategory) ? $FoodListing->subcategory_title : $FoodListing->other_subcategory); 

?>