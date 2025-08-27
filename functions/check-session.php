<?php
if (!defined('APP_GUARD'))
{
    redirect('');
    die('Direct access is forbidden!');
}
session_start();
require_once 'hooks.php';

if(!isset($_SESSION['user']))
{
    redirect('auth/login.php');
}


?>