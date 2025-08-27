<?php 
if (!defined('APP_GUARD'))
{
    redirect('');
    die('Direct access is forbidden!');
}

// config
define("DOMAIN", 'http://localhost/web-blog');

// Helpers AKA Hooks
function redirect($url)
{
    header("Location: " . trim(DOMAIN, "/ ") . "/" . trim($url, "/ "));
    exit;
}

function assets($files)
{
    return trim(DOMAIN, "/ ") . "/" . trim($files, "/ ");
}

function url($url)
{
    return trim(DOMAIN, "/ ") . "/" . trim($url, "/ ");
}

function dd($var)
{
    echo "<pre>";
    var_dump($var);
    die();
}








?>