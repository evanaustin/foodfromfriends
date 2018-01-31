<?php

/**
* Define constants
**/

switch($_SERVER['SERVER_NAME']) {
    case 'www.foodfromfriends.co':
    case 'foodfromfriends.co':
        $env = [
            'ENV'           => 'prod',
            'PUBLIC_ROOT'   => 'https://www.' . $_SERVER['SERVER_NAME'] . '/',
            'SERVER_IP'     => '45.77.100.31'
        ];

        break;
    case 'www.chameleonrenaissance.com':
    case 'chameleonrenaissance.com':
        $env = [
            'ENV'           => 'stage',
            'PUBLIC_ROOT'   => 'https://www.' . $_SERVER['SERVER_NAME'] . '/',
            'SERVER_IP'     => '45.77.104.9'
        ];    
        
        break;
    case 'localhost':
        $env = [
            'ENV'           => 'dev',
            'PUBLIC_ROOT'   => '/Projects/foodfromfriends/'
        ];
        
        break;
}

define('SERVER_ROOT', __DIR__ . '/');

require SERVER_ROOT . 'secrets.php';

$secrets = [
    'DB_HOST'           => $DB_HOST,
    'DB_NAME'           => $DB_NAME,
    'DB_USER'           => $DB_USER,
    'DB_PW'             => $DB_KEY,
    'JWT_KEY'           => $JWT_KEY,
    'STRIPE_PK_LIVE'    => $STRIPE_PK_LIVE,
    'STRIPE_SK_LIVE'    => $STRIPE_SK_LIVE,
    'STRIPE_PK_TEST'    => $STRIPE_PK_TEST,
    'STRIPE_SK_TEST'    => $STRIPE_SK_TEST,
    'SENDGRID_KEY'      => $SENDGRID_KEY,
    'AWS_KEY'           => $AWS_KEY,
    'AWS_SECRET'        => $AWS_SECRET,
    'GOOGLE_MAPS_KEY'   => $GOOGLE_MAPS_KEY,
    'SERVER_USER'       => (!empty($SERV_USER) ? $SERV_USER : ''),
    'SERVER_PW'         => (!empty($SERV_PW) ? $SERV_PW : '')
];

$constants = $env + $secrets;

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

$Gump   = new GUMP();



/**
 * AWS
 **/

$AWS    = new Aws();
$S3     = new S3($AWS);



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

    if (!empty($User->GrowerOperation)) {
        if (isset($_SESSION['user']['active_operation_id']) && $_SESSION['user']['active_operation_id'] != $User->GrowerOperation->id) {
            $User->GrowerOperation = $User->Operations[$_SESSION['user']['active_operation_id']];
        } else if (!isset($_SESSION['user']['active_operation_id'])) {
            $_SESSION['user']['active_operation_id'] = $User->GrowerOperation->id;
        }
    }
}



/**
* Cron connection
**/

if (ENV != 'dev') {
    $Cron = new Cron(SERVER_IP, '22', SERVER_USER, SERVER_PW);
}


/**
* Error Reporting
**/

define('DEBUG', false);

error_reporting(E_ALL);
ini_set('display_errors', DEBUG ? 'On' : 'Off');

?>