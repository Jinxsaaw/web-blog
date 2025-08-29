<?php
define('APP_GUARD', true);
# We are no longer using session checks for authentication
# require_once '../../functions/check-session.php'; // Commented out to enable session checks
require_once '../../functions/check-cookies.php'; // New cookie-based authentication with JWT
require_once '../../functions/hooks.php';
require_once '../../functions/pdo_connection.php';

GLOBAL $pdo;
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['category_name']) && !empty($_POST['category_name']))
{
    $query = "INSERT INTO web_blog.categories (category_name) VALUES (:category_name)";
    $statement = $pdo->prepare($query);
    $statement->execute([
        'category_name' => $_POST['category_name']
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

                    <form action="create.php" method="post">
                        <section class="form-group row">
                            <div class="col-auto">
                                <label for="name">Category :</label>
                                <input type="text" class="form-control" name="category_name" id="name" value="<?= isset($_POST['category_name']) ? htmlspecialchars($_POST['category_name']) : '' ?>" placeholder="Name.." required>
                            </div>
                        </section>
                        <section class="form-group">
                            <button type="submit" class="btn btn-primary">Create</button>
                            <a class="btn btn-danger mr-10" href="<?= url('panel/categories') ?>">Cancel</a>
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