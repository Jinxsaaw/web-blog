<?php
require_once '../../functions/hooks.php';
require_once '../../functions/pdo_connection.php';

GLOBAL $pdo;
if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['post_id']) && !empty($_GET['post_id']))
{
    $query = $pdo->prepare("SELECT * FROM web_blog.posts WHERE post_id = :post_id");
    $query->execute([
        'post_id' => $_GET['post_id']
    ]);
    $post = $query->fetch();
    if (!$post)
    {
        redirect('panel/posts');
    }
    else
    {
        $query = $pdo->prepare("DELETE FROM posts WHERE post_id = :post_id");
        $query->execute(['post_id' => $_GET['post_id']]);
        redirect('panel/posts');
    }
    # Later add user permission check
    // if ($post['user_id'] !== $_SESSION['user_id'])
    // {
    //     redirect('panel/posts');
    // }
}
redirect('panel/posts');
# Later add error handling
// else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name']) && empty($_POST['category_name']))
// {
//     $error = "Category name is required.";
// }
?>