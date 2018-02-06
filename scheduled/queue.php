<?php

$config = 'config.php';
while (!file_exists($config)) $config = '../' . $config;
require $config;

use Treffynnon\At\Wrapper as At;

?>

<p>

    <strong>Attempt capture</strong>

    <?php

    foreach(At::lq('a') as $Job) {
        $scheduled = new DateTime($Job->__get('date'), new DateTimeZone('UTC'));
        $scheduled->setTimezone(new DateTimeZone('America/New_York'));
        $date = $scheduled->format('F d, Y \a\t g:i A');
        
        echo "<pre>{$Job->__get('job_number')} : {$date}</pre>";
    }

    ?>

</p>

<p>

    <strong>Expire</strong>

    <?php

    foreach(At::lq('b') as $Job) {
        $scheduled = new DateTime($Job->__get('date'), new DateTimeZone('UTC'));
        $scheduled->setTimezone(new DateTimeZone('America/New_York'));
        $date = $scheduled->format('F d, Y \a\t g:i A');
        
        echo "<pre>{$Job->__get('job_number')} : {$date}</pre>";
    }

    ?>

</p>

<p>

    <strong>Clear</strong>

    <?php

    foreach(At::lq('c') as $Job) {
        $scheduled = new DateTime($Job->__get('date'), new DateTimeZone('UTC'));
        $scheduled->setTimezone(new DateTimeZone('America/New_York'));
        $date = $scheduled->format('F d, Y \a\t g:i A');
        
        echo "<pre>{$Job->__get('job_number')} : {$date}</pre>";
    }

    ?>

</p>