<?php
define('APP_GUARD', true);
session_start();
require_once '../functions/hooks.php';

session_destroy();
redirect('auth/login.php');

?>
