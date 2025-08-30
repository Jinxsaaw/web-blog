<?php
if (!defined('APP_GUARD')) {
    http_response_code(401);
    die();
}
require_once __DIR__ .  '/../vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();
$secretKey = $_ENV['JWT_SECRET'];
return $secretKey;
?>