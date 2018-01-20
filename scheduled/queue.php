<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

use Treffynnon\At\Wrapper as At;

echo '<pre>';

    echo 'capture:';
    var_dump(At::lq('a'));

    echo 'expire:';
    var_dump(At::lq('b'));

    echo 'clear:';
    var_dump(At::lq('c'));

echo '</pre>';

?>