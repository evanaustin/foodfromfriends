<?php

$settings = [
    'title' => 'Your pending orders | Food From Friends'
];

$Order = new Order([
    'DB' => $DB
]);

$pending = $Order->get_pending($User->id);

?>