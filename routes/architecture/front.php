<?php

$body = [
    'header'    => 'routes/components/header',
    'view'      => 'routes/views/' . $Routing->path,
    'footer'    => 'routes/components/footer',
    'modal'     => 'routes/modals/' . $Routing->path
];

if (!$LOGGED_IN) {
    $body['sign_up'] = 'routes/modals/sign-up';
    $body['log_in'] = 'routes/modals/log-in';
}

?>