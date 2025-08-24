<?php
require_once '../../functions/check-session.php';
require_once '../../functions/hooks.php';
require_once '../../functions/pdo_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Posts Panel</title>
    <link rel="stylesheet" href="<?= assets('assets/css/bootstrap.min.css') ?>" media="all" type="text/css">
    <link rel="stylesheet" href="<?= assets('assets/css/style.css') ?>" media="all" type="text/css">
</head>
<body>
<section id="app">
    <?php require_once '../layouts/top-nav.php'; ?>

    <section class="container-fluid">
        <section class="row">
            <section class="col-md-2 p-0">
                <?php require_once '../layouts/side-bar.php'; ?>
            </section>
            <section class="col-md-10 pt-3">

                <section class="mb-2 d-flex justify-content-between align-items-center">
                    <h2 class="h4">Articles</h2>
                    <a href="<?= url('panel/posts/create.php') ?>" class="btn btn-sm btn-success">Create</a>
                </section>

                <section class="table-responsive">
                    <table class="table table-striped table-">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>image</th>
                            <th>title</th>
                            <th>category name</th>
                            <th>body</th>
                            <th>status</th>
                            <th>setting</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query = "SELECT web_blog.posts.*, web_blog.categories.category_name FROM web_blog.posts LEFT JOIN web_blog.categories ON web_blog.posts.category_id = web_blog.categories.category_id";
                            $statement = $pdo->prepare($query);
                            $statement->execute();
                            $posts = $statement->fetchAll();
                            foreach($posts as $post) {
                            ?>
                            <tr>
                                <td><?= $post->post_id ?></td>
                                <td><img style="width: 90px;" src="<?= assets($post->post_image) ?>"></td>
                                <td><?= $post->post_title ?></td>
                                <td><?= $post->category_name ?></td>
                                <td><?= substr($post->post_body, 0, 30) . "..."; ?></td>
                                <td>
                                    <?php if ($post->post_status == 1) { ?>
                                    <span class="text-success">enable</span>
                                    <?php } else { ?>
                                    <span class="text-danger">disable</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="<?= url('panel/posts/change-status.php?post_id=' . $post->post_id) ?>" class="btn btn-warning btn-sm">Change status</a>
                                    <a href="<?= url('panel/posts/edit.php?post_id=' . $post->post_id); ?>" class="btn btn-info btn-sm">Edit</a>
                                    <a href="<?= url('panel/posts/delete.php?post_id=' . $post->post_id); ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </section>


            </section>
        </section>
    </section>





</section>

<script src="<?= assets('assets/js/jquery.min.js') ?>"></script>
<script src="<?= assets('assets/js/bootstrap.min.js') ?>"></script>
</body>
</html>