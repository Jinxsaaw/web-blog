<?php
define('APP_GUARD', true);
# We are no longer using session checks for authentication
# require_once '../../functions/check-session.php'; // Commented out to enable session checks
require_once '../../functions/check-cookies.php'; // New cookie-based authentication with JWT
require_once '../../functions/hooks.php';
require_once '../../functions/pdo_connection.php';

GLOBAL $pdo;
if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['cat_id']) && !empty($_GET['cat_id']))
{
    $query = $pdo->prepare("DELETE FROM web_blog.categories WHERE category_id = :category_id");
    $query->execute([
        'category_id' => $_GET['cat_id']
    ]);
}
redirect('panel/categories');
# Later add error handling
// else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name']) && empty($_POST['category_name']))
// {
//     $error = "Category name is required.";
// }
?>