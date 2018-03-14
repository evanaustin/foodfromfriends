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

$OrderFoodListing = new OrderFoodListing([
    'DB' => $DB
]);

$ordered_listings = $OrderFoodListing->retrieve([
    'where' => [
        'food_listing_id' => $FoodListing->id
    ]
]);

// check whether any orders reference this listing
if (count($ordered_listings) > 0) {
    $listing_deleted = $FoodListing->update([
        'archived_on' => \Time::now()
    ]);
} else {
    // first remove the image and its record, if they exist
    if (!empty($FoodListing->filename)) {
        $record_removed = $FoodListing->delete('filename', $FoodListing->filename, 'food_listing_images');

        if (!$record_removed) quit('Could not remove listing image information');
        
        $img_removed = $S3->delete_objects([
            ENV . '/items/' . $FoodListing->filename . '.' . $FoodListing->ext
        ]);
    }

    $listing_deleted = $FoodListing->delete('id', $FoodListing->id);
}

if (!$listing_deleted) quit('Could not remove food listing');

// reinitialize User & Operation for fresh check
$User = new User([
    'DB' => $DB,
    'id' => $USER['id']
]);

if (!empty($User->GrowerOperation)) {
    if (isset($_SESSION['user']['active_operation_id']) && $_SESSION['user']['active_operation_id'] != $User->GrowerOperation->id) {
        $User->GrowerOperation = $User->Operations[$_SESSION['user']['active_operation_id']];
    }
}

$User->GrowerOperation->check_active($User);

echo json_encode($json);

?>  
