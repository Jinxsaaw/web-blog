<?php
if (!defined('APP_GUARD'))
{
    die('Direct access is forbidden!');
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-blue ">

    <a class="navbar-brand " href="<?= url('panel') ?>">Admin Panel</a>
    <button class="navbar-toggler " type="button " data-toggle="collapse " data-target="#navbarSupportedContent " aria-controls="navbarSupportedContent " aria-expanded="false " aria-label="Toggle navigation ">
        <span class="navbar-toggler-icon "></span>
    </button>

    <div class="collapse navbar-collapse " id="navbarSupportedContent ">
        <ul class="navbar-nav mr-auto ">
            <li class="nav-item active ">
                <a class="nav-link " href="<?= url('') ?>">Home <span class="sr-only ">(current)</span></a>
            </li>
            <?php
                $query = $pdo->prepare("SELECT * FROM categories");
                $query->execute();
                $categories = $query->fetchAll();
                foreach ($categories as $category):
            ?>
            <li class="nav-item ">
                <a class="nav-link " href="<?= url('category.php?cat_id=' . $category->category_id) ?>"><?= $category->category_name ?></a>
            </li>
            <?php endforeach; ?>

        </ul>
    </div>

    <section class="d-inline ">

        <a class="text-decoration-none text-white px-2 " href="<?= url('auth/register.php') ?>">register</a>
        <a class="text-decoration-none text-white " href="<?= url('auth/login.php') ?>">login</a>

        <a class="text-decoration-none text-white px-2 " href="<?= url('auth/logout.php') ?>">logout</a>

    </section>
</nav>