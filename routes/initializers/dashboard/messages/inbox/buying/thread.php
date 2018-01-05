<?php

$settings = [
    'title' => 'Message thread | Food From Friends'
];

$grower_operation_id = $_GET['grower'];

$grower_operation_id = \Num::clean_int($_GET['grower']);

$Grower = new GrowerOperation([
    'DB' => $DB,
    'id' => $grower_operation_id
],[
    'details' => true
]);

$Message = new Message([
    'DB' => $DB
]);

$messages = $Message->retrieve([
    'where' => [
        'user_id' => $User->id,
        'grower_operation_id' => $Grower->id,
    ],
    'order' => 'sent_on ASC'
]);

?>