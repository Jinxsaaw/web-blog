<?php
define('APP_GUARD', true);
session_start();
require_once '../functions/hooks.php';
setcookie('jwt_token', '', time() - 3600, '/', '', true, true);
session_destroy();
redirect('auth/login.php');

?>
