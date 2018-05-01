<?php

$settings = [
    'title' => 'Messages to sellers | Food From Friends'
];

$Message = new Message([
    'DB' => $DB
]);

$messages = $Message->get_buying_inbox($User->BuyerAccount->id);

?>