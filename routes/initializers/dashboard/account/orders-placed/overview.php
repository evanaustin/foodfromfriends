<?php

$settings = [
    'title' => 'Your placed orders | Food From Friends'
];

$Order = new Order([
    'DB' => $DB
]);

$placed = $Order->get_placed($User->id);

?>