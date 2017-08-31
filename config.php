<?php

/**
* Define constants
**/

define('ENV', (isset($_ENV['SERVER_NAME']) ? 'prod' : 'dev'));

switch(ENV) {
    case 'prod':
        require '/secrets/foodfromfriends.php';

        $env_constants = [
            'PUBLIC_ROOT'   => 'https://foodfromfriends.co/'
        ];
        
        break;
    case 'dev':
        require 'secrets.php';

        $env_constants = [
            'PUBLIC_ROOT'   => '/Projects/foodfromfriends/'
        ];
        
        break;
}

$constants = [
    'SERVER_ROOT'       => __DIR__ . '/',
    'DB_HOST'           => $DB_HOST,
    'DB_NAME'           => $DB_NAME,
    'DB_USER'           => $DB_USER,
    'DB_PW'             => $DB_KEY,
    'SENDGRID_APIKEY'   => $SENDGRID_API,
    'AWS_KEY'           => $AWS_KEY,
    'AWS_SECRET'        => $AWS_SECRET
];

$constants = $env_constants + $constants;

foreach ($constants as $constant => $value) {
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
 * GUMP Validator
 **/

// include 'vendor/wixel/gump/gump.class.php';
$Gump = new GUMP();



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



/**
* Error Reporting
**/

define('DEBUG', false);

error_reporting(E_ALL);
ini_set('display_errors', DEBUG ? 'On' : 'Off');

?>