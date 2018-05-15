<?php

$settings = [
    'title' => 'Pending orders | Food From Friends'
];

$OrderGrower = new OrderGrower([
    'DB' => $DB
]);

$pending = $OrderGrower->get_pending($User->GrowerOperation->id);

?>