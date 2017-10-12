<?php

$body = [
    'header'    => 'routes/components/header',
    'view'      => 'routes/views/' . $Routing->path,
    'modal'     => 'routes/modals/' . $Routing->path
];

if (!$LOGGED_IN) {
    $body['sign-up'] = 'routes/modals/sign-up';
    $body['log-in'] = 'routes/modals/log-in';
}

?>