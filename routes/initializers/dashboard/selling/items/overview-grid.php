<?php

$settings = [
    'title' => 'Your items | Food From Friends'
];

$Item = new Item([
    'DB' => $DB
]);

$items = $Item->get_all_items($User->GrowerOperation->id);

$item_count = count(array_filter($items));

if ($item_count == 0) {
    $msg = 'Hey! Unless your garden is as barren as this page, you\'ve got some adding to do. Hop to it!';
} else if ($item_count == 1) {
    $msg = 'That\'s a mighty fine item you\'ve got there. Looks like it could use some company though &ndash;' . (($items[0]['category_title'] == 'fruit') ? ' more' : '') . ' fruits or veggies, perhaps?';
} else if ($item_count > 1 && $item_count < 3) {
    $msg = 'Looking good! Your selection is coming along well. Locavores prefer growers who offer a strong variety of food, so keep on diversifying if you can!';
} else if ($item_count > 2 && $item_count < 6) {
    $msg = 'Nice variety you\'ve got there! Seriously, you\'re getting pretty good at this. I wonder if you might be able to handle growing even more&hellip;';
} else if ($item_count > 5) {
    $msg = 'Woah! We are truly blown away by your selection. Growers like you are the lifeblood of this friendly family. Keep on doing what you\'re doing!';
}

?>