<?php 

$settings = [
    'title' => 'Your pickup preferences | Food From Friends'
];

$Pickup = new Pickup([
    'DB' => $DB
]);

$details = $Pickup->get_details($User->GrowerOperation->id);

$imgs = ['corn','tree','lemongrass'];

?>
