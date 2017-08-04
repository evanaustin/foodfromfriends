<?php

$FoodListing = new FoodListing([
    'DB' => $DB,
    'S3' => $S3,
    'id' => $_GET['id']
]);

?>