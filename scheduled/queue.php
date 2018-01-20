<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

use Treffynnon\At\Wrapper as At;

echo '<pre>';
print_r(At::lq());
echo '</pre>';

?>