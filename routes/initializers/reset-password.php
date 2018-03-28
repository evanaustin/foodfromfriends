<?php

use \Firebase\JWT\JWT;

$settings = [
    'title' => 'Reset your password | Food From Friends'
];

if (isset($_GET['token'])) {
    try {
        $JWT = JWT::decode($_GET['token'], JWT_KEY, array('HS256'));
    } catch(\Firebase\JWT\SignatureInvalidException $e) {
        $authentic_token = false;
    }

    if (isset($JWT->email) && (time() - $JWT->iss_on <= $JWT->time)) {
        $authentic_token = true;
    }
}

?>