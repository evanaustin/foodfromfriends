<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

echo '<pre>';
print_r(At::lq());
echo '</pre>';

?>