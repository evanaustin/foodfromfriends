<?php

$settings = [
    'title' => 'Log in | Food From Friends'
];

foreach ($_GET as $k => $v) if (isset($v) && !empty($v)) ${$k} = rtrim($v);

?>