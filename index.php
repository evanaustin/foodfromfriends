<?php

require 'config.php';

$Routing = new Routing([
    'path'      => $_GET['path'],
    'landing'   => (ENV == 'prod' ? 'splash' : 'map')
]);

include SERVER_ROOT . 'routes/template.php';

?>