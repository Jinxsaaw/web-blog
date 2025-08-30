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
    $query = $pdo->prepare("SELECT p.post_id FROM posts p WHERE p.category_id = :category_id");
    $query->execute([
        'category_id' => $_GET['cat_id']
    ]);
    $posts = $query->fetchAll();
    if($posts) {
        redirect('panel/categories' . '?category_delete=failed' . '&reason=category_in_use');
    }
    if(!$posts) {
        $query = $pdo->prepare("DELETE FROM categories WHERE category_id = :category_id");
        $query->execute([
            'category_id' => $_GET['cat_id']
        ]);
        redirect('panel/categories' . '?category_delete=successful');
    }
}
redirect('panel/categories');
# Later add error handling
// else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name']) && empty($_POST['category_name']))
// {
//     $error = "Category name is required.";
// }
?>