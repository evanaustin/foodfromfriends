<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

parse_str($argv, $params);

foreach($params as $k => $v) {
    error_log($v);
}

?>