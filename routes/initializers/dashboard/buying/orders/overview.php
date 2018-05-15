<?php

$settings = [
    'title' => 'Your orders | Food From Friends'
];

$Order = new Order([
    'DB' => $DB
]);

$placed = $Order->get_placed($User->BuyerAccount->id);

?>