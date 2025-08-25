<?php
define('APP_GUARD', true);
require_once 'functions/hooks.php';
require_once 'functions/pdo_connection.php';
GLOBAL $pdo;
if ( isset($_GET['cat_id']) && !empty($_GET['cat_id']) ){
    $cat_id = $_GET['cat_id'];
    $query = $pdo->prepare("SELECT p.*, c.category_name FROM posts p LEFT JOIN categories c ON p.category_id = c.category_id WHERE p.category_id = :cat_id ORDER BY p.created_at DESC");
    $query->execute(['cat_id' => $cat_id]);
    $posts = $query->fetchAll();
} else
{
    redirect('');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>PHP tutorial</title>
        <link rel="stylesheet" href="<?= assets('assets/css/bootstrap.min.css') ?>" media="all" type="text/css">
        <link rel="stylesheet" href="<?= assets('assets/css/style.css') ?>" media="all" type="text/css">
    </head>
    <body>
        <section id="app"> 
            <?php require_once "layouts/top-nav.php"?>

            <section class="container my-5"> 
                <?php
                if ( $posts ):
                    foreach ( $posts as $post):
                        if( $post->post_status != 1 ) continue;
                ?>
                <section class="row">
                    <section class="col-12">
                        <h1><?= $post->category_name ?></h1>
                        <hr>
                    </section>
                </section> 
                <section class="row"> 
                    <section class="col-md-4">
                        <section class="mb-2 overflow-hidden" style="max-height: 15rem;">
                            <img class="img-fluid" src="<?= assets($post->post_image); ?>" alt="<?= $post->post_image ?>">
                        </section>
                        <h2 class="h5 text-truncate"><?= $post->post_title ?></h2>
                        <p><?= substr($post->post_body, 0, 30) ?></p>
                        <p><a class="btn btn-primary" href="" role="button">View details »</a></p>
                    </section>
                </section> 
                <?php
                    endforeach;
                else:
                ?>
                <section class="row">
                        <section class="col-12">
                            <h1>Category not found!</h1>
                        </section>
                <?php
                        endif;
                ?>
                </section> 
            </section>

        </section>
        <script src="<?= assets('assets/js/jquery.min.js') ?>"></script>
        <script src="<?= assets('assets/js/bootstrap.min.js') ?>"></script>
    </body>
</html>