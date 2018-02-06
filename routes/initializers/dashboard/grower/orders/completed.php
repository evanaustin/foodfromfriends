<?php

$settings = [
    'title' => 'Completed orders | Food From Friends'
];

$OrderGrower = new OrderGrower([
    'DB' => $DB
]);

$completed = $OrderGrower->get_completed($User->GrowerOperation->id);

?>