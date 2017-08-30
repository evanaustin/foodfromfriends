<?php 

$Pickup = new Pickup([
    'DB' => $DB
]);

if ($Pickup->exists('user_id', $User->id)){ 
    $details = $Pickup->get_details($User->id);
} 


?>
