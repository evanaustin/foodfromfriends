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
            'DB_HOST'   => '',
            'DB_NAME'   => '',
            'DB_USER'   => '',
            'DB_PW'     => ''
        ]; break;
    case 'dev':
        $env_constants = [
            'PUBLIC_ROOT' => '/Projects/foodfromfriends/',
            'DB_HOST'   => 'localhost',
            'DB_NAME'   => '',
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