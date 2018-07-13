<?php

/*
* Define constants
*/

switch($_SERVER['SERVER_NAME']) {
    case 'www.foodfromfriends.co':
    case 'foodfromfriends.co':
        $env = [
            'ENV'           => 'prod',
            'PUBLIC_ROOT'   => 'https://' . $_SERVER['SERVER_NAME'] . '/',
            'SERVER_IP'     => '45.77.107.173'
        ];

        break;
    case 'www.foodfromfriends.xyz':
    case 'foodfromfriends.xyz':
        $env = [
            'ENV'           => 'stage',
            'PUBLIC_ROOT'   => 'https://' . $_SERVER['SERVER_NAME'] . '/',
            'SERVER_IP'     => '45.77.104.9'
        ];    
        
        break;
    case 'localhost':
        $env = [
            'ENV'           => 'dev',
            'PUBLIC_ROOT'   => 'http://localhost/'
        ];
        
        break;
}

define('SERVER_ROOT', __DIR__ . '/');

require SERVER_ROOT . ($env['ENV'] == 'dev' ? '' : '../') . 'secrets.php';

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


/*
 * Autoload
 */

require 'vendor/autoload.php';

spl_autoload_register(function($class_name) {
    include 'core/' . $class_name . '.php';
});


/*
 * Database
 */

try {
    $DB = new DB('mysql:host='. DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PW);
} catch(PDOException $e) {
    error_log($e->getMessage());
}


/*
 * GUMP Validator
 */

$Gump   = new GUMP();


/*
 * AWS
 */

$AWS    = new Aws();
$S3     = new S3($AWS);


/*
 * Maintain session
 */

session_start();


/*
 * Routing
 */

if (isset($_GET['path'])) {
    $_SESSION['path'] = $_GET['path'];
}

$Routing = new Routing([
    'DB'        => $DB,
    'path'      => $_SESSION['path'],
    'landing'   => 'home'
]);


/*
 * Configure User
 */

$LOGGED_IN = isset($_SESSION['user']);

if ($LOGGED_IN) {
    $USER = $_SESSION['user'];
}

if ($LOGGED_IN && !isset($_GET['token'])) {
    $User = new User([
        'DB' => $DB,
        'id' => $USER['id'],
        'buyer_account'     => ($Routing->template == 'front' || ($Routing->template == 'dashboard' && $Routing->section == 'buying'))    ? true : false,
        'seller_account'    => ($Routing->template == 'front' || ($Routing->template == 'dashboard' && $Routing->section == 'selling'))   ? true : false,
    ]);

    if (!empty($User->BuyerAccount)) {
        if (isset($_SESSION['user']['active_buyer_account_id']) && $_SESSION['user']['active_buyer_account_id'] != $User->BuyerAccount->id) {
            $User->BuyerAccount = new BuyerAccount([
                'DB' => $DB,
                'id' => $_SESSION['user']['active_buyer_account_id']
            ]);
        } else if (!isset($_SESSION['user']['active_buyer_account_id'])) {
            $_SESSION['user']['active_buyer_account_id'] = $User->BuyerAccount->id;
        }
    } else {
        $_SESSION['user']['active_buyer_account_id'] = null;
    }
    
    if (!empty($User->GrowerOperation)) {
        if (isset($_SESSION['user']['active_operation_id']) && $_SESSION['user']['active_operation_id'] != $User->GrowerOperation->id) {
            $User->GrowerOperation = $User->Operations[$_SESSION['user']['active_operation_id']];
        } else if (!isset($_SESSION['user']['active_operation_id'])) {
            $_SESSION['user']['active_operation_id'] = $User->GrowerOperation->id;
        }
    } else {
        $_SESSION['user']['active_operation_id'] = null;
    }
}

use \Firebase\JWT\JWT;

if (isset($_GET['token'])) {
    $JWT = JWT::decode($_GET['token'], JWT_KEY, array('HS256'));

    // token logs in User
    if (isset($JWT->user_id) && (!isset($JWT->time) || (time() - $JWT->iss_on <= $JWT->time))) {
        // User is already logged in under a different ID; reset Session:User:ID
        if ($LOGGED_IN && ($USER['id'] != $JWT->user_id)) {
            $_SESSION['user']['id'] = null;
        } 

        // User is already logged in under same ID -OR- Session:User:ID is not set
        if (($LOGGED_IN && ($USER['id'] == $JWT->user_id)) || !isset($_SESSION['user']['id'])) {
            $User = new User([
                'DB' => $DB,
                'id' => $JWT->user_id,
                'buyer_account'     => ($Routing->template == 'front' || ($Routing->template == 'dashboard' && $Routing->section == 'buying'))    ? true : false,
                'seller_account'    => ($Routing->template == 'front' || ($Routing->template == 'dashboard' && $Routing->section == 'selling'))   ? true : false,
            ]);

            $User->log_in($JWT->user_id);
            $LOGGED_IN = true;
        }
        
        if (!empty($JWT->buyer_account_id)) {
            $User->switch_buyer_account($JWT->buyer_account_id);
        } else {
            // $User->BuyerAccount = false;
            $_SESSION['user']['active_buyer_account_id'] = null;
        }
        
        if (!empty($JWT->grower_operation_id)) {
            $User->switch_operation($JWT->grower_operation_id);
        } else {
            // $User->GrowerOperation = false;
            $_SESSION['user']['active_operation_id'] = null;
        }
    }
}


/*
* Cron connection
*/

/*if (ENV != 'dev') {
    try {
    	$Cron = new Cron(SERVER_IP, '22', SERVER_USER, SERVER_PW);
	} catch (\Exception $e) {
		error_log($e->getMessage());
	}
}*/


/*
* Error Reporting
*/

define('DEBUG', false);

error_reporting(E_ALL);
ini_set('display_errors', DEBUG ? 'On' : 'Off');

?>