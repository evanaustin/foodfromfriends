<?php

$settings = [
    'title' => 'Your team members | Food From Friends'
];

if ($User->GrowerOperation) {
    $team_members = $User->GrowerOperation->get_team_members();
}

$imgs = ['corn','tree','lemongrass'];

?>