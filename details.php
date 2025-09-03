<?php
define('APP_GUARD', true);
require_once 'functions/hooks.php';
require_once 'functions/pdo_connection.php';
GLOBAL $pdo;
if ( $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['post_id']) && !empty($_GET['post_id']) )
{
    # You can also add a WHERE condition to check post_status = 1
    $query = $pdo->prepare("SELECT p.*, c.category_name AS cn FROM posts p LEFT JOIN categories c ON p.category_id = c.category_id WHERE post_id = :post_id");
    $query->execute(['post_id' => $_GET['post_id']]);
    $post = $query->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png+xml" href="<?= htmlspecialchars(assets('assets/images/icons/home.png')) ?>" />
        <title>Post Details</title>
        <link rel="stylesheet" href="<?= htmlspecialchars(assets('assets/css/bootstrap.min.css')) ?>" media="all" type="text/css">
        <link rel="stylesheet" href="<?= htmlspecialchars(assets('assets/css/style.css')) ?>" media="all" type="text/css">
    </head>
    <body>
    <section id="app">
        <?php require_once "layouts/top-nav.php"?>

        <section class="container my-5">
            <section class="row">
                <section class="col-md-12">
                <?php 
                    if ( $post && $post->post_status == 1 ):
                ?>
                    <h1><?= $post->post_title ?></h1>
                    <h5 class="d-flex justify-content-between align-items-center">
                        <a href="<?= htmlspecialchars(url('category.php?cat_id=' . $post->category_id)) ?>"><?= $post->cn ?></a>
                        <span class="date-time"><?= $post->created_at ?></span>
                    </h5>
                    <article class="bg-article p-3"><img class="float-right mb-2 ml-2" style="width: 18rem;" src="<?= htmlspecialchars(assets($post->post_image)) ?>" alt="<?= htmlspecialchars(assets($post->post_image)) ?>"><?= htmlspecialchars($post->post_body) ?></article>
                <?php
                    else:
                ?>
                    <section>post not found!</section>
                <?php
                    endif;
                ?>
                </section>
            </section>
        </section>

    </section>
        <script src="<?= htmlspecialchars(assets('assets/js/jquery.min.js')) ?>"></script>
        <script src="<?= htmlspecialchars(assets('assets/js/bootstrap.min.js')) ?>"></script>
    </body>
</html>