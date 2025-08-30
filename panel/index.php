<?php
define('APP_GUARD', true);
require_once '../functions/hooks.php';
# We are no longer using sessions for authentication
# require_once '../functions/check-session.php'; // Comment out if you want session checks
require_once '../functions/check-cookies.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png+xml" href="<?= assets('assets/images/icons/home.png') ?>" />
        <title>Admin Panel</title>
        <link rel="stylesheet" href="<?= assets('assets/css/bootstrap.min.css') ?>" media="all" type="text/css">
        <link rel="stylesheet" href="<?= assets('assets/css/style.css') ?>" media="all" type="text/css">
    </head>
    <body>
        <section id="app">

            <?php require_once 'layouts/top-nav.php'; ?>
            
            <section class="container-fluid">
                <section class="row">
                    <section class="col-md-2 p-0">
                        <?php require_once 'layouts/side-bar.php'; ?>
                    </section>
                    <section class="col-md-10 pb-3">

                        <section style="min-height: 80vh;" class="d-flex justify-content-center align-items-center">
                            <section>
                                <h1>Profile</h1>
                                <ul class="mt-2 li">
                                    <li><h3>About me text area</h3></li>
                                    <li><h3>Status</h3></li>
                                    <li><h3>Created at</h3></li>
                                    <li><h3>Modifed at</h3></li>
                                    <li><h3>Profile picture</h3></li>
                                </ul>
                            </section>
                        </section>

                    </section>
                </section>
            </section>


        </section>

        <script src="<?= assets('assets/js/jquery.min.js') ?>"></script>
        <script src="<?= assets('assets/js/bootstrap.min.js') ?>"></script>
    </body>
</html>