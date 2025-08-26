<?php
define('APP_GUARD', true);
require_once 'functions/hooks.php';
require_once 'functions/pdo_connection.php';
GLOBAL $pdo;
if ( isset($_GET['cat_id']) && !empty($_GET['cat_id']) ){
    $cat_id = $_GET['cat_id'];
    $query = $pdo->prepare("SELECT * FROM posts WHERE category_id = :cat_id AND post_status = 1 ORDER BY created_at DESC");
    $query->execute(['cat_id' => $cat_id]);
    $posts = $query->fetchAll();
} else
{
    redirect('');
}
if ( isset($_GET['cat_id']) && !empty($_GET['cat_id']) )
{
    $cat_id = $_GET['cat_id'];
    $query = $pdo->prepare("SELECT * FROM categories WHERE category_id = :cat_id");
    $query->execute(['cat_id' => $cat_id]);
    $category = $query->fetch();
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png+xml" href="<?= assets('assets/images/icons/home.png') ?>" />
        <title><?= $category->category_name ?> Posts</title>
        <link rel="stylesheet" href="<?= assets('assets/css/bootstrap.min.css') ?>" media="all" type="text/css">
        <link rel="stylesheet" href="<?= assets('assets/css/style.css') ?>" media="all" type="text/css">
    </head>
    <body>
        <section id="app"> 
            <?php require_once "layouts/top-nav.php"?>
            <section class="container my-5"> 
                <section class="row">
                    <section class="col-12">
                        <h1><?= $category->category_name ?></h1>
                        <hr>
                    </section>
                </section> 
                <section class="row">
                    <?php
                        foreach ($posts as $post):
                    ?>
                    <section class="col-md-4">
                        <section class="mb-2 overflow-hidden" style="max-height: 15rem;">
                            <img class="img-fluid" src="<?= assets($post->post_image); ?>" alt="<?= $post->post_image ?>">
                        </section>
                        <h2 class="h5 text-truncate"><?= $post->post_title ?></h2>
                        <p><?= substr($post->post_body, 0, 30) ?></p>
                        <p><a class="btn btn-primary" href="" role="button">View details »</a></p>
                    </section>
                    <?php
                       endforeach;
                    ?>
                </section> 
                <?php
                    if ( empty($posts) ):
                ?>
                <section class="row">
                        <section class="col-12">
                            <h1>No posts yet for this category!</h1>
                        </section>
                </section> 
                <?php
                    endif;
                ?>
            </section>

        </section>
        <script src="<?= assets('assets/js/jquery.min.js') ?>"></script>
        <script src="<?= assets('assets/js/bootstrap.min.js') ?>"></script>
    </body>
</html>