<?php

$settings = [
    'title' => 'Your items | Food From Friends'
];

$Item = new Item([
    'DB' => $DB
]);

$raw_categories = $Item->retrieve([
    'table' => 'food_categories'
]);

$hashed_categories = [];

foreach($raw_categories as $raw_category) {
    if (!isset($hashed_categories[$raw_category['id']])) {
        $hashed_categories[$raw_category['id']] = $raw_category['title'];
    }
}

$raw_items = $Item->get_raw_items($User->GrowerOperation->id);

$hashed_items = [];

foreach($raw_items as $raw_item) {
    if (!isset($hashed_items[$raw_item['food_category_id']])) {
        $hashed_items[$raw_item['food_category_id']] = [
            $raw_item['id'] => new Item([
                'DB' => $DB,
                'id' => $raw_item['id']
            ])
        ];
    }
}

// $item_count = count(array_filter($raw_items));

?>