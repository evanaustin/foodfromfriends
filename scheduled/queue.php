<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

use Treffynnon\At\Wrapper as At;

echo 'attempt capture';
foreach(At::lq('a') as $Job) {
    echo "<pre>{$Job->data['job_number']} : {$Job->data['date']}";
}

echo 'expire';
foreach(At::lq('b') as $Job) {
    echo "<pre>{$Job->data['job_number']} : {$Job->data['date']}";
}

echo 'clear';
foreach(At::lq('c') as $Job) {
    echo "<pre>{$Job->data['job_number']} : {$Job->data['date']}";
}

?>