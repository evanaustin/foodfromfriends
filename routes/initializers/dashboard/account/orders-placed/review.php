<?php

$settings = [
    'title' => 'Review order | Food From Friends'
];

$order_grower_id = \Num::clean_int($_GET['id']);

$OrderGrower = new OrderGrower([
    'DB' => $DB,
    'id' => $order_grower_id
]);

$Seller = new GrowerOperation([
    'DB' => $DB,
    'id' => $OrderGrower->grower_operation_id
],
[
    'details' => true
]);

// CSS shows it right to left, so order it backwards in the array
$scores = [
    5 => 'Excellent',
    4 => 'Great',
    3 => 'Good',
    2 => 'Okay',
    1 => 'Poor',
];

?>