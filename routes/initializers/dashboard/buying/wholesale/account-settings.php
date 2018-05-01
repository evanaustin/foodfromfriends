<?php

$settings = [
    'title' => 'Your wholesale account | Food From Friends'
];

$BuyerAccount = new BuyerAccount([
    'DB' => $DB
]);

$buyer_account_types = $BuyerAccount->get_types();

?>