<h3>Screenshots from the beta buildout</h3>

<?php

$screenshots = [
    'search',
    'grower-profile',
    'food-listing',
    'grower-dashboard-landing',
    'blank-overview',
    'add-listing',
    'edit-listing',
    'listings-overview',
    'enable-delivery',
    'enable-pickup',
    'enable-meetup',
    'set-operation',
];

foreach ($screenshots as $screenshot) {
    img('screenshots/' . $screenshot, 'png', 'local', 'img-fluid');
}

?>