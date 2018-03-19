<?php

require 'config.php';

$Routing = new Routing([
    'DB'        => $DB,
    'path'      => $_GET['path'],
    'landing'   => 'home'
]);

include SERVER_ROOT . 'routes/template.php';

?>