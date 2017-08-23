<?php 

$Meetup = new Meetup([
    'DB' => $DB
]);
 

if ($Meetup->exists('user_id', $User->id)) { 
    $details = $Meetup->get_details($User->id);
}

?>
