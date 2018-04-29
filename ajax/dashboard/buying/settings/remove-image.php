<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');
// quit(ENV . "/{$User->BuyerAccount->Image->path}/{$User->BuyerAccount->Image->filename}.{$User->BuyerAccount->Image->ext}");
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

if (isset($User->GrowerOperation) && $User->GrowerOperation->type == 'individual') {
    // reinitialize User & Operation for fresh check
    $User = new User([
        'DB' => $DB,
        'id' => $USER['id']
    ]);

    if (isset($_SESSION['user']['active_operation_id']) && $_SESSION['user']['active_operation_id'] != $User->GrowerOperation->id) {
        $User->GrowerOperation = $User->Operations[$_SESSION['user']['active_operation_id']];
    }

    $User->GrowerOperation->check_active();
}

echo json_encode($json);

?>  
