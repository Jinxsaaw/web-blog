<?php
define('APP_GUARD', true);
require_once 'functions/hooks.php';
require_once 'functions/pdo_connection.php';
GLOBAL $pdo;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Blog Posts</title>
        <link rel="stylesheet" href="<?= assets('assets/css/bootstrap.min.css') ?>" media="all" type="text/css">
        <link rel="stylesheet" href="<?= assets('assets/css/style.css') ?>" media="all" type="text/css">
    </head>
    <body>
        <section id="app">

            <?php require_once "layouts/top-nav.php"?>

            <section class="container my-5">
                <!-- Example row of columns -->
                <section class="row">
                    <?php
                        $query = $pdo->prepare("SELECT * FROM posts WHERE post_status = 1 ORDER BY created_at DESC");
                        $query->execute();
                        $posts = $query->fetchAll();
                        if ( $posts ):
                            foreach ($posts as $post):
                                # In the query we already filtered post_status = 1
                                # So this check is not necessary but good to know
                                // if( $post->post_status != 1 ) continue;
                    ?>
                        <section class="col-md-4">
                            <section class="mb-2 overflow-hidden" style="max-height: 15rem;"><img class="img-fluid" src="<?= assets($post->post_image) ?>" alt="<?= $post->post_image ?>"></section>
                            <h2 class="h5 text-truncate"><?= $post->post_title ?></h2>
                            <p><?= substr($post->post_body, 0, 30) ?></p>
                            <p><a class="btn btn-primary" href="<?= url('details.php?post_id=' . $post->post_id) ?>" role="button">View details »</a></p>
                        </section>
                    <?php   
                            endforeach;
                        endif;
                    ?>
                </section>
            </section>

        </section>
        <script src="<?= assets('assets/js/jquery.min.js') ?>"></script>
        <script src="<?= assets('assets/js/bootstrap.min.js') ?>"></script>
    </body>
</html>