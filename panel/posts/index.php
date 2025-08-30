<?php
define('APP_GUARD', true);
# We are no longer using sessions for authentication
# require_once '../../functions/check-session.php'; // Comment out if you want session checks
require_once '../../functions/check-cookies.php';
require_once '../../functions/hooks.php';
require_once '../../functions/pdo_connection.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png+xml" href="<?= assets('assets/images/icons/home.png') ?>" />
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
                    <h2 class="h4">Posts</h2>
                    <a href="<?= url('panel/posts/create.php') ?>" class="btn btn-sm btn-success">Create</a>
                </section>

                <section class="table-responsive">
                    <table class="table table-striped table-">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category Name</th>
                            <th>Body</th>
                            <th>Status</th>
                            <th>Setting</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $query = "SELECT p.*, c.category_name FROM web_blog.posts p LEFT JOIN web_blog.categories c ON p.category_id = c.category_id WHERE p.user_id = :user_id;";
                            $statement = $pdo->prepare($query);
                            $statement->execute(['user_id' => $decoded->sub]);
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
                                    <a href="<?= url('panel/posts/change-status.php?post_id=' . $post->post_id) ?>" class="btn btn-warning btn-sm">Change Status</a>
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