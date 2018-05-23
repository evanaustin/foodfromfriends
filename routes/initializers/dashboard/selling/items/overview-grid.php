<?php

$settings = [
    'title' => 'Your items | Food From Friends'
];

$FoodListing = new FoodListing([
    'DB' => $DB
]);

$listings = $FoodListing->get_all_listings($User->GrowerOperation->id);

$listing_count = count(array_filter($listings));

if ($listing_count == 0) {
    $msg = 'Hey! Unless your garden is as barren as this page, you\'ve got some adding to do. Hop to it!';
} else if ($listing_count == 1) {
    $msg = 'That\'s a mighty fine listing you\'ve got there. Looks like it could use some company though &ndash;' . (($listings[0]['category_title'] == 'fruit') ? ' more' : '') . ' fruits or veggies, perhaps?';
} else if ($listing_count > 1 && $listing_count < 3) {
    $msg = 'Looking good! Your selection is coming along well. Locavores prefer growers who offer a strong variety of food, so keep on diversifying if you can!';
} else if ($listing_count > 2 && $listing_count < 6) {
    $msg = 'Nice variety you\'ve got there! Seriously, you\'re getting pretty good at this. I wonder if you might be able to handle growing even more&hellip;';
} else if ($listing_count > 5) {
    $msg = 'Woah! We are truly blown away by your selection. Growers like you are the lifeblood of this friendly family. Keep on doing what you\'re doing!';
}

?>