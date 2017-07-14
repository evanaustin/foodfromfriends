<?php

/**
* Define constants
**/

$constants = [
    'ENV'		    => (isset($_ENV['SERVER_NAME'])) ? 'prod' : 'dev',
    'SERVER_ROOT'   => __DIR__ . '/'
];

foreach ($constants as $constant => $value) {
    define($constant, $value);
}

switch(ENV) {
    case 'prod':
        $env_constants = [
            'PUBLIC_ROOT' => '/',
            'DB_HOST'   => 'localhost',
            'DB_NAME'   => 'varaloka_foodfromfriends',
            'DB_USER'   => 'varaloka_fff',
            'DB_PW'     => 'ux2JJkIfs;,@'
        ]; break;
    case 'dev':
        $env_constants = [
            'PUBLIC_ROOT' => '/Projects/foodfromfriends/',
            'DB_HOST'   => 'localhost',
            'DB_NAME'   => 'foodfromfriends',
            'DB_USER'   => 'root',
            'DB_PW'     => 'root'
        ]; break;
}

foreach ($env_constants as $constant => $value) {
    define($constant, $value);
}



/**
 * Autoload
 **/

require 'vendor/autoload.php';

spl_autoload_register(function($class_name) {
    include 'core/' . $class_name . '.php';
});



/**
 * Database
 **/

try {
    $DB = new DB('mysql:host='. DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PW);
} catch(PDOException $e) {
    echo $e->getMessage();
}



/**
 * AWS
 **/

$AWS = new Aws();
$S3 = new S3($AWS);



/**
 * User session
 **/

session_start();

$LOGGED_IN = isset($_SESSION['user']);

if ($LOGGED_IN) {
    $USER = $_SESSION['user'];
    $User = new User([
        'DB' => $DB,
        'id' => $USER['id']
    ]);
}

?>