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
        ENV . '/profile-photos/' . $User->filename . '.' . $User->ext
    ]);
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
