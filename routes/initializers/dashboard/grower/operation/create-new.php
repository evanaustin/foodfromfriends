<?php

$settings = [
    'title' => 'Create a new operation | Food From Friends'
];

$GrowerOperation = new GrowerOperation([
    'DB' => $DB
]);

$operation_types = $GrowerOperation->get_types();

?>