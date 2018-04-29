<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

if (!empty($User->BuyerAccount->Image->image_id)) {
    $image_deleted = $S3->delete_objects([
        ENV . "/{$User->BuyerAccount->Image->path}/{$User->BuyerAccount->Image->filename}.{$User->BuyerAccount->Image->ext}"
    ]);

    $image_removed  = $User->BuyerAccount->delete('id', $User->BuyerAccount->Image->image_id, 'images');
    if (!$image_removed) quit('Could not remove image');

    $record_removed = $User->BuyerAccount->delete('buyer_account_id', $User->BuyerAccount->id, 'buyer_account_images');
    if (!$record_removed) quit('Could not remove record');
} else {
    quit('There was no image to remove');
}

echo json_encode($json);

?>  
