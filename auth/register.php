<?php
define('APP_GUARD', true);
require_once '../functions/hooks.php';
require_once '../functions/pdo_connection.php';
#later make an assoc array for all different errors and write the warning under the input inside small tag!;
$error = NULL;
$email_error = NULL;
$first_name_error = NULL;
$last_name_error = NULL;
$password_error = NULL;
$confirm_error = NULL;
$errors = [];
GLOBAL $pdo;
if ( //done
    empty($_POST['email']) && isset($_POST['email']) &&
    empty($_POST['first_name']) && isset($_POST['first_name']) &&
    empty($_POST['last_name']) && isset($_POST['last_name']) &&
    empty($_POST['password']) && isset($_POST['password']) &&
    empty($_POST['confirm']) && isset($_POST['confirm'])
    )
{
    $errors['fields'] = 'Please fill in all the fields!';
}
else
{
    if( empty($_POST['email']) && isset($_POST['email']) )
    {
        $errors['email'] = 'Email is required!';
    }
    if( empty($_POST['first_name']) && isset($_POST['first_name']) )
    {
        $errors['first_name'] = 'First name is required!';
    }
    if( empty($_POST['last_name']) && isset($_POST['last_name']) )
    {
        $errors['last_name'] = 'Last name is required!';
    }
    if( empty($_POST['password']) && isset($_POST['password']) )
    {
        $errors['password'] = 'Password is required!';
    }
    if( empty($_POST['confirm']) && isset($_POST['confirm']) )
    {
        $errors['confirm'] = 'Confirming your password is required!';
    }
}
if(isset($_POST['email']) && !empty($_POST['email'])) // done
{
    if ( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) )
    {
        $errors['email'] = 'Please enter a valid email address!';
    }
    $query = $pdo->prepare("SELECT * FROM web_blog.users WHERE email = :email");
    $query->execute(['email' => $_POST['email']]);
    $email = $query->fetch();
    if ( $email )
    {
        $errors['email'] = "This email is already registered.";
    }
}
if (!empty($_POST['password']) && isset($_POST['password']) ) // not done
{
    // Define regex patterns for each password requirement
    $minLengthPattern = '/^.{8,}$/'; // At least 8 characters
    $uppercasePattern = '/[A-Z]/';   // At least one uppercase letter
    $lowercasePattern = '/[a-z]/';   // At least one lowercase letter
    $numberPattern = '/\d/';         // At least one digit
    $specialCharPattern = '/[!@#$%^&*]/'; // At least one special character in the ones I've written (not a letter or digit)

    # Check each requirement and add error messages if not met
    if( !preg_match($minLengthPattern, $_POST['password']) )
    {
        $errors['minLength'] = "Password must be at least 8 characters long.\n";
    }
    if ( !preg_match($uppercasePattern, $_POST['password']) )
    {
        $errors['uppercase'] = "Password must contain at least one uppercase letter.\n";
    }
    if ( !preg_match($lowercasePattern, $_POST['password']) )
    {
        $errors['lowercase'] = "Password must contain at least one lowercase letter.\n";
    }
    if ( !preg_match($numberPattern, $_POST['password']) )
    {
        $errors['number'] = "Password must contain at least one number.\n";
    }
    if ( !preg_match($specialCharPattern, $_POST['password']) )
    {
        $errors['specialChar'] = "Password must contain at least one special character. (e.g., !@#$%^&* )\n";
    }
}
if (!empty($_POST['confirm']) && isset($_POST['confirm'])) // done
{
    $password_check = $_POST['password'] === $_POST['confirm'];
    if ( isset($_POST['password']) && empty($_POST['password']) )
    {
        $errors['confirm'] = 'Please enter your password first.\n';
    } else if ( isset($_POST['password']) && !empty($_POST['password']) && !$password_check )
    {
        $errors['confirm'] = "Password and confirm password do not match.\n";
    }
}
if (!empty($errors))
{
    $error = $errors['fields'] ?? NULL;
    $email_error =  $errors['email'] ?? NULL;
    $first_name_error =  $errors['first_name'] ?? NULL;
    $last_name_error =  $errors['last_name'] ?? NULL;
    $password_error =  $errors['password'] ?? NULL;
    $password_error .=  $errors['minLength'] ?? NULL;
    $password_error .=  $errors['uppercase'] ?? NULL;
    $password_error .=  $errors['lowercase'] ?? NULL;
    $password_error .=  $errors['number'] ?? NULL;
    $password_error .=  $errors['specialChar'] ?? NULL;
    $confirm_error =  $errors['confirm'] ?? NULL;
    $errors = [];
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_POST['email'], $_POST['first_name'], $_POST['last_name'], $_POST['password'], $_POST['confirm']) &&
    !empty($_POST['email']) && !empty($_POST['first_name']) && !empty($_POST['last_name']) &&
    !empty($_POST['password']) && !empty($_POST['confirm'])
    )
    {
        if ( empty($errors) && !$email && $password_check )
        {
            // Generate a unique token for the user
            $token = bin2hex(random_bytes(16)); // Or use UUID
            $query = $pdo->prepare("INSERT INTO web_blog.users SET first_name = :first_name, last_name = :last_name, email = :email, password = :password, url_token = :url_token");
            $hased_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $query->execute([
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'email' => $_POST['email'],
                'password' => $hased_password,
                'url_token' => $token
            ]);
            redirect('auth/login.php' . urlencode('You have registered successfully. Please log in.'));
        }
    }

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png+xml" href="<?= assets('assets/images/icons/home.png') ?>" />
    <title>Sign up</title>
    <link rel="stylesheet" href="<?= assets('assets/css/bootstrap.min.css') ?>" media="all" type="text/css">
    <link rel="stylesheet" href="<?= assets('assets/css/style.css') ?>" media="all" type="text/css">
</head>

<body>
    <section id="app">
        <?php require_once '../layouts/top-nav.php' ?>

        <section style="height: 100vh; background-color: #138496;" class="d-flex justify-content-center align-items-center">
            <section style="width: 20rem;">
                <h1 class="bg-warning rounded-top px-2 mb-0 py-3 h5">Create a New Account</h1>
                <section class="bg-light my-0 px-2"><small class="text-danger"><?= $error !== NULL ?  $error : '' ?></small></section>
                <form class="pt-3 pb-1 px-2 bg-light rounded-bottom" action="<?= url('/auth/register.php') ?>" method="post">
                    <section class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="email ..." value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                        <small class="text-danger"><?= $email_error !== NULL ?  $email_error : '' ?></small>
                    </section>
                    <section class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" class="form-control" name="first_name" id="first_name" placeholder="first_name ..." value="<?= isset($_POST['first_name']) ? $_POST['first_name'] : '' ?>">
                        <small class="text-danger"><?= $first_name_error !== NULL ?  $first_name_error : '' ?></small>
                    </section>
                    <section class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" class="form-control" name="last_name" id="last_name" placeholder="last_name ..." value="<?= isset($_POST['last_name']) ? $_POST['last_name'] : '' ?>">
                        <small class="text-danger"><?= $last_name_error !== NULL ?  $last_name_error : '' ?></small>
                    </section>
                    <section class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="password ..." value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
                        <small class="text-danger"><?= $password_error !== NULL ?  $password_error : '' ?></small>
                    </section>
                    <section class="form-group">
                        <label for="confirm">Confirm</label>
                        <input type="password" class="form-control" name="confirm" id="confirm" placeholder="confirm ..." value="<?= isset($_POST['confirm']) && !empty($_POST['password']) ? $_POST['confirm'] : '' ?>">
                        <small class="text-danger"><?= $confirm_error !== NULL ?  $confirm_error : '' ?></small>
                    </section>
                    <section class="mt-4 mb-2 d-flex justify-content-between">
                        <input type="submit" class="btn btn-success btn-sm" value="register">
                        <a class="btn btn-info btn-sm" href="<?= url('auth/login.php') ?>">Already have an account?</a>
                    </section>
                </form>
            </section>
        </section>

    </section>
<script src="<?= assets('assets/js/jquery.min.js') ?>"></script>
<script src="<?= assets('assets/js/bootstrap.min.js') ?>"></script>
</body>

</html>