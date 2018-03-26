<?php

$settings = [
    'title' => 'Messages to customers | Food From Friends'
];

if (isset($_GET['grower'])) {
    $grower_operation_id = \Num::clean_int($_GET['grower']);

    if (isset($User->Operations[$grower_operation_id])) {
        $User->GrowerOperation = $User->Operations[$grower_operation_id];
    }
} else if (isset($User->Operations)) {
    foreach ($User->Operations as $Op) {
        if ($Op->type !== 'individual') {
            continue;
        } else {
            $User->GrowerOperation = $Op;
            break;
        }
    }
}

if (isset($User->GrowerOperation) && ((!isset($grower_operation_id) && $User->GrowerOperation->type == 'individual') || $User->GrowerOperation->id == $grower_operation_id)) {
    $Message = new Message([
        'DB' => $DB
    ]);
    
    $messages = $Message->get_selling_inbox($User->GrowerOperation->id);
}


?>