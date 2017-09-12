<?php

$city = $_GET['city'];

$growers = $User->pull_all_growers();
console_log($growers);

?>