<?php 

$settings = [
    'title' => 'Your meetups | Food From Friends'
];

$Meetup = new Meetup([
    'DB' => $DB
]);

$meetups = $Meetup->retrieve([
    'where' => [
        'grower_operation_id' => $User->GrowerOperation->id
    ]
]);

?>
