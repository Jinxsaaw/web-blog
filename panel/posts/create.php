<?php
#fix the bug in image upload and post creation for bytes exceeding limit
define('APP_GUARD', true);
require_once '../../functions/hooks.php';
require_once '../../functions/pdo_connection.php';
# We are no longer using sessions for authentication
# require_once '../../functions/check-session.php'; // Comment out if you want session checks
require_once '../../functions/check-cookies.php';
GLOBAL $pdo;
if (
    isset($_POST['cat_id']) && !empty($_POST['cat_id'])
    && isset($_POST['title']) && !empty($_POST['title'])
    && isset($_POST['body']) && !empty($_POST['body'])
    )
{
	$post_body = $_POST['body'];
	$post_title = $_POST['title'];
	$cat_id = $_POST['cat_id'];
	if (isset($_FILES['image']) && $_FILES['image']['error'] == 0)
	{
		$image = $_FILES['image'];
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $image_mime = pathinfo($image['name'], PATHINFO_EXTENSION);
        if (!in_array($image_mime, $allowed_extensions)) {
            if (session_status() == PHP_SESSION_NONE) 
            {
                session_start();
            }
        $_SESSION['old_post'] = $_POST;
            redirect('/panel/posts/create.php?error=invalid_image_format');
        }
        $base_path = dirname(__DIR__, 2);
		$image_path = '/assets/images/posts/' . pathinfo($image['name'], PATHINFO_FILENAME) . '_' . date("Y_m_d_H_i") . '.' . $image_mime;
		move_uploaded_file($image['tmp_name'], $base_path . $image_path);
	}
    else
    {
        $image_path = NULL;
    }
	$query = $pdo->prepare("INSERT INTO web_blog.posts (post_title, post_body, post_image, user_id, category_id) VALUES (:title, :body, :image, :user_id, :cat_id);");
	$query->execute(['title' => $post_title, 'body' => $post_body, 'image' => $image_path, 'user_id' => $decoded->sub, 'cat_id' => $cat_id]);
	redirect('/panel/posts');

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png+xml" href="<?= assets('assets/images/icons/home.png') ?>" />
    <title>Create Post</title>
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
                <form action="<?= url("/panel/posts/create.php") ?>" method="post" enctype="multipart/form-data">
                <?php
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                    $_POST = $_SESSION['old_post'] ?? [];
                    unset($_SESSION['old_post']);
                }
                ?>
                    <section class="form-group row my-3">
                        <div class="col-auto">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="title ..." value="<?= $_POST['title'] ?? '' ?>">
                        </div>
                    </section>
                    <section class="form-group row my-3">
                        <div class="col-auto">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" name="image" id="image">
                        </div>
                    </section>
                    <section class="form-group row my-3">
                        <div class="col-auto">
                            <label for="cat_id">Category</label>
                            <select class="form-control" name="cat_id" id="cat_id">
                                <?php $query = $pdo->prepare("SELECT * FROM categories;");
                                $query->execute();
                                $categories = $query->fetchAll();
                                foreach($categories as $category) {
                                ?>
                                <option value="<?= $category->category_id ?>"> <?= $category->category_name ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </section>                    
                    <section class="form-group my-3">
                        <label for="body">Body</label>
                        <div class="w-50">
                        <textarea class="form-control" name="body" id="body" rows="5" placeholder="body ..."><?= $_POST['body'] ?? '' ?></textarea>
                        </div>
                    </section>
                    <section class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">Create</button>
                        <a class="btn btn-danger" href="<?= url('panel/posts') ?>">Cancel</a>
                    </section>
                </form>

            </section>
        </section>
    </section>

</section>

<script src="<?= assets('assets/js/jquery.min.js') ?>"></script>
<script src="<?= assets('assets/js/bootstrap.min.js') ?>"></script>
</body>
</html>