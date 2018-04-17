<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

if (!empty($User->WholesaleAccount->filename)) {
    $record_removed = $User->WholesaleAccount->delete('filename', $User->WholesaleAccount->filename, 'wholesale_account_images');
    
    if (!$record_removed) quit('Could not remove image record');
    
    $img_removed = $S3->delete_objects([
        ENV . '/wholesale-account-images/' . $User->WholesaleAccount->filename . '.' . $User->WholesaleAccount->ext
    ]);
} else {
    quit('There was no image to remove');
}

echo json_encode($json);

?>  
