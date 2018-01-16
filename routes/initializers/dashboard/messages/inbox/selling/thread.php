<?php

$settings = [
    'title' => 'Message thread | Food From Friends'
];



if (isset($_GET['grower'])) {
    $grower_operation_id = \Num::clean_int($_GET['grower']);

    if (isset($User->Operations[$grower_operation_id])) {
        $User->GrowerOperation = $User->Operations[$grower_operation_id];
    }
}

if (isset($User->GrowerOperation) && ((!isset($grower_operation_id) && $User->GrowerOperation->type == 'none') || $User->GrowerOperation->id == $grower_operation_id)) {
    $Grower = new GrowerOperation([
        'DB' => $DB,
        'id' => $User->GrowerOperation->id
    ],[
        'details' => true
    ]);
    
    $user_id = \Num::clean_int($_GET['user']);

    $Customer = new User([
        'DB' => $DB,
        'id' => $user_id
    ]);

    $Message = new Message([
        'DB' => $DB
    ]);

    $messages = $Message->retrieve([
        'where' => [
            'user_id' => $user_id,
            'grower_operation_id' => $Grower->id,
        ],
        'order' => 'sent_on ASC'
    ]);
}

?>