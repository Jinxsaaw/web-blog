<?php
if (!defined('APP_GAURD'))
{
    die('Direct access is forbidden!');
}
session_start();
require_once 'hooks.php';

if(!isset($_SESSION['user']))
{
    redirect('auth/login.php');
}


?>