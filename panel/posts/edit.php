<?php 
define('APP_GUARD', true);
# We are no longer using sessions for authentication
# require_once '../../functions/check-session.php'; // Comment out if you want session checks
require_once '../../functions/check-cookies.php';
require_once '../../functions/hooks.php';
require_once '../../functions/pdo_connection.php';
GLOBAL $pdo;

# Fetch the existing post data and checking if the post_id is valid
if ( isset($_GET['post_id']) && !empty($_GET['post_id']) )
{
    $query = $pdo->prepare("SELECT * FROM web_blog.posts WHERE post_id = :post_id");
    $query->execute(['post_id' => $_GET['post_id']]);
    $post = $query->fetch();
}
else
{
    redirect('panel/posts');
}
if( !$post ) {
    redirect('panel/posts');
}

# Handling the form submission and updating the post
if  (
    isset($_POST['title']) && !empty($_POST['title']) && 
    isset($_POST['body']) && !empty($_POST['body']) && 
    isset($_POST['cat_id']) && !empty($_POST['cat_id']) && 
    isset($_GET['post_id']) && !empty($_GET['post_id']) && 
    $post
    )
    {
    
    #checking if the category exists
    $query = $pdo->prepare("SELECT * FROM categories WHERE category_id = :cat_id;");
    $query->execute(['cat_id' => $_POST['cat_id']]);
    $category = $query->fetch();

    #Checking if new image is uploaded
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0)
    {
        $allowed_extentions = ['jpg', 'jpeg', 'png', 'gif'];
        $image = $_FILES['image'];
        $image_extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        if(!in_array(strtolower($image_extension), $allowed_extentions))
        {
            redirect('panel/posts/edit.php?post_id=' . $_GET['post_id'] . '&error=invalid_image');
        }
        $base_path = dirname(__DIR__, 2);

        # Deleting the old image file if it exists
        if(file_exists($base_path . $post->post_image))
        {
            unlink($base_path . $post->post_image);
        }
        $image_path = '/assets/images/posts/' . date('Y_m_d_H_i_s_') . $image['name'];
        move_uploaded_file($image['tmp_name'], $base_path . $image_path);
    }
    else
    {
        $image_path = $post->post_image; // Keep the old image if no new one is uploaded
    }

    if ( $category )
    {
        $query = $pdo->prepare("UPDATE web_blog.posts SET post_title = :title, post_body = :body, post_image = :image, category_id = :cat_id WHERE post_id = :post_id;");
        $query->execute([
            'title' => $_POST['title'],
            'body' => $_POST['body'],
            'image' => $image_path,
            'cat_id' => $_POST['cat_id'],
            'post_id' => $_GET['post_id']
        ]);
        redirect('panel/posts');

    }
    else
    {
        redirect('panel/posts/edit.php?post_id=' . $_GET['post_id'] . '&error=invalid_category');
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png+xml" href="<?= assets('assets/images/icons/home.png') ?>" />
    <title>Edit Post</title>
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

                    <form action="<?=  url('panel/posts/edit.php?post_id=') . $post->post_id ?>" method="post" enctype="multipart/form-data">
                        <section class="form-group row my-3">
                            <div class="col-auto">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="title ..." value="<?= $post->post_title ?>">
                            </div>
                        </section>
                        <section class="form-group row my-3">
                            <div class="col-auto">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" name="image" id="image" >
                            <img src="<?= assets($post->post_image); ?>" class="mt-3 ml-2" height="200" width="200" alt="">
                            </div>
                        </section>
                        <section class="form-group row my-3">
                            <div class="col-auto">
                                <label for="cat_id">Category</label>
                                <select class="form-control" name="cat_id" id="cat_id">
                                <?php
                                $query = $pdo->prepare("SELECT * FROM web_blog.categories;");
                                $query->execute();
                                $categories = $query->fetchAll();
                                foreach($categories as $category)
                                {
                                ?>
                                    <option value="<?= $category->category_id ?>" <?php if( $category->category_id == $post->category_id) {echo 'selected';} ?>><?= $category->category_name ?></option>
                                <?php
                                }
                                ?>
                                </select>
                            </div>
                        </section>
                        <section class="form-group my-3">
                            <label for="body">Body</label>
                            <div class="w-50">
                                <textarea class="form-control" name="body" id="body" rows="5" placeholder="body ..."><?= htmlspecialchars($post->post_body) ?></textarea>
                            </div>
                        </section>
                        <section class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a class="btn btn-danger" href="<?= url('panel/posts') ?>">Cancel</a>
                        </section>
                    </form>

                </section>
            </section>
        </section>

    </section>

    <script src="<?= url('assets/js/jquery.min.js') ?>"></script>
    <script src="<?= url('assets/js/bootstrap.min.js') ?>"></script>
</body>

</html>