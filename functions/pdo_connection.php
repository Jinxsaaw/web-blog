<?php
if (!defined('APP_GUARD'))
{
    http_response_code(401);
}
GLOBAL $pdo;

try
{
    $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ);
    $pdo = new PDO("mysql:host=localhost;dbname=web_blog", 'root', '', $options);
    $user_id = 1; // From login session
    $token = bin2hex(random_bytes(16)); // Or use UUID
    $stmt = $pdo->prepare("UPDATE web_blog.users SET url_token = ? WHERE user_id = ?");
    $stmt->execute([$token, $user_id]);
    return $pdo;
}
catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
    die();
}

?>