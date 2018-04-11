<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

$FoodListing = new FoodListing([
    'DB' => $DB,
    'S3' => $S3,
    'id' => $_POST['listing_id']
]);

if (!empty($FoodListing->filename)) {
    $record_removed = $FoodListing->delete('filename', $FoodListing->filename, 'food_listing_images');
    
    if (!$record_removed) quit('Could not edit image record');
    
    $img_removed = $S3->delete_objects([
        ENV . '/food-listing/' . $FoodListing->filename . '.' . $FoodListing->ext
    ]);
} else {
    quit('There was no image to remove');
}

echo json_encode($json);

?>  
