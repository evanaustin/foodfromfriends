<?php

$settings = [
    'title' => 'Your item items | Food From Friends'
];

$Item = new Item([
    'DB' => $DB
]);

$items = $Item->get_all_items($User->GrowerOperation->id);

$item_count = count(array_filter($items));

?>