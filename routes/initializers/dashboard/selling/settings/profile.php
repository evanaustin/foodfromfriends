<?php

$settings = [
    'title' => 'Your seller profile | Food From Friends'
];

$GrowerOperation = new GrowerOperation([
    'DB' => $DB
]);

$operation_types = $GrowerOperation->get_types();

?>