<?php 

$settings = [
    'title' => 'Your delivery preferences | Food From Friends'
];

$Delivery = new Delivery([
    'DB' => $DB
]);

$details = $Delivery->get_details($User->id);

$imgs = ['corn','tree','lemongrass'];

?>
