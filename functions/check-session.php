<?php
session_start();
require_once 'hooks.php';

if(!isset($_SESSION['user']))
{
    redirect('auth/login.php');
}


?>