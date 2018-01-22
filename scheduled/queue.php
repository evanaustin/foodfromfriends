<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

use Treffynnon\At\Wrapper as At;

echo '<strong>Attempt capture</strong>';

foreach(At::lq('a') as $Job) {
    $scheduled = new DateTime($Job->__get('date'), new DateTimeZone('UTC'));
    $scheduled->setTimezone(new DateTimeZone('America/New_York'));
    $date = $scheduled->format('F d, Y \a\t g:i A');
    
    echo "<pre>{$Job->__get('job_number')} : {$date}</pre>";
}

echo '<strong>Expire</strong>';

foreach(At::lq('b') as $Job) {
    $scheduled = new DateTime($Job->__get('date'), new DateTimeZone('UTC'));
    $scheduled->setTimezone(new DateTimeZone('America/New_York'));
    $date = $scheduled->format('F d, Y \a\t g:i A');
    
    echo "<pre>{$Job->__get('job_number')} : {$date}</pre>";
}

echo '<strong>Clear</strong>';

foreach(At::lq('c') as $Job) {
    $scheduled = new DateTime($Job->__get('date'), new DateTimeZone('UTC'));
    $scheduled->setTimezone(new DateTimeZone('America/New_York'));
    $date = $scheduled->format('F d, Y \a\t g:i A');
    
    echo "<pre>{$Job->__get('job_number')} : {$date}</pre>";
}

?>