<?php

$settings = [
    'title' => 'User profile | Food From Friends'
];

$ProfileUser = new User([
    'DB' => $DB,
    'id' => $_GET['id']
]);

$FoodListing = new FoodListing([
    'DB' => $DB,
]);

$foodlistings = $FoodListing->join_foodlistings($ProfileUser->id);
$available_foodlistings = $FoodListing->retrieve('is_available',1);

$Review = new Review([
    'DB' => $DB
]);

$reviews = $Review->join_reviews($ProfileUser->id);

$ProfileUser_info = $ProfileUser->retrieve('id', $ProfileUser->id);

$Delivery = new Delivery([
    'DB' => $DB
]);

$delivery_details = $Delivery->get_details($ProfileUser->id);

$Meetup = new Meetup([
    'DB' => $DB
]);
 
$settings = $Meetup->get_settings($ProfileUser->id);

if ($Meetup->exists('user_id',$ProfileUser->id)){ 
    $meetup_details_1 = $Meetup->get_details($ProfileUser->id, 1);
    $meetup_details_2 = $Meetup->get_details($ProfileUser->id, 2);
    $meetup_details_3 = $Meetup->get_details($ProfileUser->id, 3);
} 

$Pickup = new Pickup([
    'DB' => $DB
]);

if ($Pickup->exists('user_id',$ProfileUser->id)){ 
    $pickup_details = $Pickup->get_details($ProfileUser->id);
} 




?>