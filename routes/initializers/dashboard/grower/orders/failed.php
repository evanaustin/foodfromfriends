<?php

$settings = [
    'title' => 'Failed orders | Food From Friends'
];

$OrderGrower = new OrderGrower([
    'DB' => $DB
]);

$completed = $OrderGrower->get_failed($User->GrowerOperation->id);

?>