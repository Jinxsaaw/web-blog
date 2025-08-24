<?php
if (!defined('APP_GAURD'))
{
    die('Direct access is forbidden!');
}
GLOBAL $pdo;

try
{
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ);
    $pdo = new PDO("mysql:host=localhost;dbname=web_blog", 'root', '', $options);
    return $pdo;
}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
    die();
}

?>