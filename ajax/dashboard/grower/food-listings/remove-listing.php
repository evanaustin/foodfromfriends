<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

$FoodListing = new FoodListing([
    'DB' => $DB,
    'S3' => $S3,
    'id' => $_POST['listing_id']
]);

// this is a temporary hard-coded value to represent the number 
// of orders placed on this listing until the order class is built
$past_orders = 0;

$listing_deleted = false;

// check whether any orders reference this listing
if ($past_orders > 0) {
    $listing_deleted = $FoodListing->update([
        'deleted_on' => time()
    ], 'id', $FoodListing->id);
} else {
    // first remove the image and its record, if they exist
    if (!empty($FoodListing->filename)) {
        $record_removed = $FoodListing->delete('filename', $FoodListing->filename, 'food_listing_images');

        if (!$record_removed) quit('Could not remove listing image information');
        
        $img_removed = $S3->delete_objects([
            ENV . '/food-listings/' . $FoodListing->filename . '.' . $FoodListing->ext
        ]);
    }

    $listing_deleted = $FoodListing->delete('id', $FoodListing->id);
}

if (!$listing_deleted) quit('Could not remove food listing');

$User->GrowerOperation->check_active($User);

echo json_encode($json);

?>  
