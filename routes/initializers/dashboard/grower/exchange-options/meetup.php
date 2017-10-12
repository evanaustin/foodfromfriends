<?php 

$settings = [
    'title' => 'Your meetup preferences | Food From Friends'
];

$Meetup = new Meetup([
    'DB' => $DB
]);

$details = $Meetup->get_details($User->GrowerOperation->id);

$imgs = ['corn','tree','lemongrass'];

?>
