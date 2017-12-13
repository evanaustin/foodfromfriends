<?php

$settings = [
    'title' => 'Orders under review | Food From Friends'
];

$OrderGrower = new OrderGrower([
    'DB' => $DB
]);

$under_review = $OrderGrower->get_under_review($User->GrowerOperation->id);

?>