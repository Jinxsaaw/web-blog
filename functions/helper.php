<?php 

// config
define("BASE_URL", 'localhost/php_project');

// Helpers AKA Hooks
function redirect($url)
{
    header("Location: " . trim(BASE_URL, "/ ") . "/" . trim($url, "/ "));
    exit;
}

function asset($files)
{
    return trim(BASE_URL, "/ ") . "/" . trim($files, "/ ");
}

function url($url)
{
    return trim(BASE_URL, "/ ") . "/" . trim($url, "/ ");
}

function dd($var)
{
    echo "<pre>";
    var_dump($var);
    die();
}








?>