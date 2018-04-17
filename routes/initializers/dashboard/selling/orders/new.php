<?php

$settings = [
    'title' => 'New orders | Food From Friends'
];

$OrderGrower = new OrderGrower([
    'DB' => $DB
]);

$new = $OrderGrower->get_new($User->GrowerOperation->id);

?>