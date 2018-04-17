<?php

$settings = [
    'title' => 'Review order | Food From Friends'
];

// $order_grower_id = \Num::clean_int($_GET['id']);
$order_grower_id = $_GET['id'];

$OrderGrower = new OrderGrower([
    'DB' => $DB,
    'id' => $order_grower_id
]);

// ! @todo verify this user has permission to report

$Seller = new GrowerOperation([
    'DB' => $DB,
    'id' => $OrderGrower->grower_operation_id
],
[
    'details' => true
]);

?>