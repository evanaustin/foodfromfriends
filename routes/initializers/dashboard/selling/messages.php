<?php

$settings = [
    'title' => 'Messages to customers | Food From Friends'
];

$Message = new Message([
    'DB' => $DB
]);

$messages = $Message->get_selling_inbox($User->GrowerOperation->id);


?>