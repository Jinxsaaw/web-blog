<?php 
require_once '../../functions/hooks.php';
require_once '../../functions/pdo_connection.php';
GLOBAL $pdo;
# The only issue with this code is that it doesn't show the already uploaded image but it will keep the old image if nothing is uploaded!
if(isset($_GET['post_id']) && !empty($_GET['post_id']))
{
    $query = $pdo->prepare("SELECT web_blog.p.*, web_blog.c.category_name AS cn FROM web_blog.posts p LEFT JOIN web_blog.categories c ON web_blog.p.category_id = web_blog.c.category_id WHERE web_blog.p.post_id = :post_id");
    $query->execute(['post_id' => $_GET['post_id']]);
    $post = $query->fetch();
}
else
{
    redirect('panel/posts');
}
if(!$post) {
    redirect('panel/posts');
}

if(isset($_POST['title']) && !empty($_POST['title']) && isset($_POST['body']) && !empty($_POST['body']) && isset($_POST['cat_id']) && !empty($_POST['cat_id']) && isset($_GET['post_id']) && !empty($_GET['post_id']) && $post)
{

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
        $image_path = '/assets/images/posts/' . date('Y_m_d_H_i_s_') . $image['name'];
        move_uploaded_file($image['tmp_name'], $base_path . $image_path);
    }
    else
    {
        $image_path = $post->post_image; // Keep the old image if no new one is uploaded
    }
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


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP panel</title>
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
                        <section class="form-group">
                            <label for="title">Title</label>
                            <input type="text" class="form-control" name="title" id="title" placeholder="title ..." value="<?= $post->post_title ?>">
                        </section>
                        <section class="form-group">
                            <label for="image">Image</label>
                            <input type="file" class="form-control" name="image" id="image" value="<?= $post->post_image ?>">
                        </section>
                        <section class="form-group">
                            <label for="cat_id">Category</label>
                            <select class="form-control" name="cat_id" id="cat_id">
                                <?php
                                $query = $pdo->prepare("SELECT * FROM web_blog.categories;");
                                $query->execute();
                                $categories = $query->fetchAll();
                                foreach($categories as $category)
                                {
                                    if($category->category_id == $post->category_id)
                                    {
                                        ?>
                                        <option value="<?= $category->category_id ?>" selected><?= $category->category_name ?></option>
                                        <?php
                                    }
                                
                                else
                                {
                                ?>
                                <option value="<?= $category->category_id ?>"><?= $category->category_name ?></option>
                                <?php
                                }
                                }
                                ?>
                            </select>
                        </select>
                        </section>
                        <section class="form-group">
                            <label for="body">Body</label>
                            <textarea class="form-control" name="body" id="body" rows="5" placeholder="body ..."><?= htmlspecialchars($post->post_body) ?></textarea>
                        </section>
                        <section class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
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