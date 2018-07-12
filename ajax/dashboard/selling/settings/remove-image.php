<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!$LOGGED_IN) quit('You are not logged in');

if (!empty($User->GrowerOperation->filename)) {
    $record_removed = $User->GrowerOperation->delete('filename', $User->GrowerOperation->filename, 'grower_operation_images');
    
    if (!$record_removed) quit('Could not remove image record');
    
    $img_removed = $S3->delete_objects([
        ENV . '/grower-operation-images/' . $User->GrowerOperation->filename . '.' . $User->GrowerOperation->ext
    ]);
} else {
    quit('There was no image to remove');
}

echo json_encode($json);

?>  
