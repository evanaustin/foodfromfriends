<?php 

$Delivery = new Delivery([
    'DB' => $DB
]);

$details = $Delivery->get_details($User->id);

?>
