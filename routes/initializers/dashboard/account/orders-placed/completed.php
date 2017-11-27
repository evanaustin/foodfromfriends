<?php

$settings = [
    'title' => 'Your completed orders | Food From Friends'
];

$Order = new Order([
    'DB' => $DB
]);

$completed = $Order->get_completed($User->id);

?>