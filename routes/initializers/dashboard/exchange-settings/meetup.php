<?php 

$Meetup = new Meetup([
    'DB' => $DB
]);
 
$settings = $Meetup->get_settings($User->id);

if ($Meetup->exists('user_id',$User->id)){ 
    $details_1 = $Meetup->get_details($User->id, 1);
    $details_2 = $Meetup->get_details($User->id, 2);
    $details_3 = $Meetup->get_details($User->id, 3);
} 


?>
