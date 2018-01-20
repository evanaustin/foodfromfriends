<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

use Treffynnon\At\Wrapper as At;

echo '<pre>';
var_dump(At::lq('capture'));
var_dump(At::lq('expire'));
var_dump(At::lq('clear'));
echo '</pre>';

?>