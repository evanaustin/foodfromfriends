<?php

$settings = [
    'title' => 'Your wholesale account | Food From Friends'
];

$WholesaleAccount = new WholesaleAccount([
    'DB' => $DB
]);

$wholesale_account_types = $WholesaleAccount->get_types();

?>