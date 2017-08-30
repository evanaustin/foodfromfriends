<?php 

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

$json['error'] = null;
$json['success'] = true;

if (!empty($User->filename)) {
    $record_removed = $User->delete('filename', $User->filename, 'user_profile_images');
    
    if (!$record_removed) quit('Could not remove image record');
    
    $img_removed = $S3->delete_objects([
        'user/profile-photos/' . $User->filename . '.' . $User->ext
    ]);
} else {
    quit('There was no image to remove');
}

echo json_encode($json);

?>  
