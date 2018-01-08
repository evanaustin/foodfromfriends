<?php

$settings = [
    'title' => 'Messages to growers | Food From Friends'
];

$Message = new Message([
    'DB' => $DB
]);

$messages = $Message->get_buying_inbox($User->id);

?>