<?php

$settings = [
    'title' => 'Team Member Invitation | Food From Friends'
];

foreach ($_GET as $k => $v) if (isset($v) && !empty($v)) ${$k} = rtrim($v);

$email = str_replace(' ', '+', $email);

?>