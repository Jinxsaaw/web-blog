<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!defined('APP_GUARD'))
{
    redirect('');
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
                $query_cat_nav = $pdo->prepare("SELECT * FROM categories");
                $query_cat_nav->execute();
                $nav_categories = $query_cat_nav->fetchAll();
                foreach ($nav_categories as $nav_category):
            ?>
            <li class="nav-item ">
                <a class="nav-link " href="<?= url('category.php?cat_id=' . $nav_category->category_id) ?>"><?= $nav_category->category_name ?></a>
            </li>
            <?php endforeach; ?>

        </ul>
    </div>

    <section class="d-inline ">

        <?php
            if( !isset($_SESSION['user']) ):
        
        ?>
        <a class="text-decoration-none text-white px-2 " href="<?= url('auth/register.php') ?>">Register</a>
        <a class="text-decoration-none text-white " href="<?= url('auth/login.php') ?>">Log In</a>
        <?php
            else:
        ?>
        <a class="text-decoration-none text-white px-2 " href="<?= url('auth/logout.php') ?>">Log Out</a>
        <?php
            endif;
        ?>
    </section>
</nav>