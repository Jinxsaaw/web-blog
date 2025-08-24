<?php
define('APP_GUARD', true);
require_once '../../functions/check-session.php';
require_once '../../functions/hooks.php';
require_once '../../functions/pdo_connection.php';

GLOBAL $pdo;

if(isset($_GET['cat_id']) && !empty($_GET['cat_id']))
{
    $statement = $pdo->prepare("SELECT * FROM web_blog.categories WHERE category_id = :cat_id");
    $statement->execute(['cat_id' => $_GET['cat_id']]);
    $category = $statement->fetch();
} 
else
{
    
    redirect('panel/categories');
}
if (!$category) {
    redirect('panel/categories');
}
if(isset($_POST['category_name']) && !empty($_POST['category_name']))
{
    $query = $pdo->prepare("UPDATE web_blog.categories SET category_name = :cat_name WHERE category_id = :cat_id");
    $query->execute([
        'cat_name' => $_POST['category_name'],
        'cat_id' => $category->category_id,
    ]);
    redirect('panel/categories');
}
# Later add error handling
// else if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name']) && empty($_POST['category_name']))
// {
//     $error = "Category name is required.";
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Category</title>
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

                    <form action="<?= url('panel/categories/edit.php?cat_id=' . $category->category_id) ?>" method="post">
                        <section class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" name="category_name" id="name" value="<?= htmlspecialchars($category->category_name, ENT_QUOTES, 'UTF-8') ?>" required>
                        </section>
                        <section class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
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