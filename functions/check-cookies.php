<?php
if (!defined('APP_GUARD')) {
    define('APP_GUARD', true);
}
require_once 'hooks.php';
# Load secret key for JWT and adjust path if needed
require_once __DIR__ . '/jwt_config.php';
GLOBAL $secretKey;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
function getJWT () {
    if(!empty($_COOKIE['jwt_token'])) {
        return $_COOKIE['jwt_token'];
    }

    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if ( preg_match('/Bearer\s(\S+)/', $authHeader, $matches) )
    {
        return $matches[1];
    }
    return NULL;
}

$jwt = getJWT();
if(!$jwt) {
    redirect('auth/login.php');
    die();
}

if ($jwt) {
    try
    {
        $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
        // Token is valid. User authenticated!
        $role = $decoded->data->role ?? 'user';
        if ($role !== 'admin') {
            setcookie('jwt_token', '', time() - 3600, '/', '', true, true);
            redirect('auth/login.php' . urlencode('?error=access_denied'));
            die();
        }
    }
    catch (Exception $e)
    {
        // Invalid token
        setcookie('jwt_token', '', time() - 3600, '/', '', true, true);
        $text = $e->getMessage();
        redirect('auth/login.php' . urlencode("?error=$text"));
        die();
    }

}

?>