<?php 
require_once '../../functions/hooks.php';
require_once '../../functions/pdo_connection.php';

GLOBAL $pdo;

if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['post_id']) && !empty($_GET['post_id']))
{
    $query = $pdo->prepare("SELECT * FROM posts WHERE post_id = :post_id");
    $query->execute(['post_id' => $_GET['post_id']]);
    $post = $query->fetch();
    if (!$post)
    {
        redirect('panel/posts');
    }
    else
    {
        $query = $pdo->prepare("UPDATE posts SET post_status = :post_status WHERE post_id = :post_id");
        $query->execute(
            [
                'post_status' => $post->post_status == 1 ? 0 : 1, // Toggle status
                'post_id' => $_GET['post_id']
            ]
        );
        redirect('panel/posts');
    }
}


















?>