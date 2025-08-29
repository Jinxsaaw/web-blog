<?php
if (!defined('APP_GUARD'))
{
    http_response_code(401);
}
session_start();
require_once 'hooks.php';

if(!isset($_SESSION['user']))
{
    redirect('auth/login.php');
}


?>