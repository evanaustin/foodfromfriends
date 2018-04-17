<?php

$settings = [
    'title' => 'Your orders | Food From Friends'
];

$Order = new Order([
    'DB' => $DB
]);

$is_wholesale = (isset($User->WholesaleAccount));
$buyer_id = ($is_wholesale) ? $User->WholesaleAccount->id : $User->id;

$placed = $Order->get_placed($buyer_id, $is_wholesale);

?>