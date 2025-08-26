<?php
if (!defined('APP_GUARD'))
{
    die('Direct access is forbidden!');
    redirect('index.php');
}
?>
<section class="sidebar">
    <section class="sidebar-link">
        <a href="<?= url('panel') ?>">Panel</a>
    </section>
    <section class="sidebar-link">
        <a href="<?= url('panel/categories') ?>">Categories</a>
    </section>
    <section class="sidebar-link">
        <a href="<?= url('panel/posts') ?>">Posts</a>
    </section>
</section>