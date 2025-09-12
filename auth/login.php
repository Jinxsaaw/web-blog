<?php
define('APP_GUARD', true);
# We no longer use sessions for authentication but we do use it for CSRF protection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../functions/hooks.php';
require_once __DIR__ . '/../functions/pdo_connection.php';
require_once __DIR__ . '/../functions/jwt_config.php';
GLOBAL $pdo;
GLOBAL $secretKey;
use Firebase\JWT\JWT;
$error = '';
$email_error = '';
$password_error = '';
$errors = [];
if ( empty($_POST['email']) && empty($_POST['password']) && isset($_POST['email'], $_POST['password']) )
{
    $errors['fields'] = 'Please fill in all the fields!';
}
else
{
    if ( empty($_POST['email'])  && isset($_POST['email']) )
    {
        $errors['email'] = 'Email is required!';
    }
    if ( empty($_POST['password'])  && isset($_POST['password']) )
    {
        $errors['password'] = 'Password is required!';
    }
}
if ( !empty($errors) )
{
    $error = $errors['fields'] ?? NULL;
    $email_error = $errors['email'] ?? NULL;
    $password_error = $errors['password'] ?? NULL;
    $errors = [];
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_POST['email'], $_POST['password']) &&
    !empty($_POST['email']) && !empty($_POST['password'])
    )
{
    $Input_email = sanitizeInput($_POST['email']);
    $Input_password = sanitizeInput($_POST['password']);
    if ( !isset($_POST['csrf_token']) || !verifyCsrfToken('login-form', $_POST['csrf_token']) )
    {
        unset($_SESSION['csrf_tokens']);
        // Stop further processing
        die('Invalid CSRF token!');
    }

    $query = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $query->execute([
        'email' => $Input_email
    ]);
    $user = $query->fetch();
    if ( $user )
    {
        if ( password_verify($Input_password, $user->password) )
        {
            # Create JWT Payload
            $payload = [
                'iss' => 'web-blog',          // Issuer
                'sub' => $user->user_id,      // Subject (user identifier)
                'iat' => time(),              // Issued at
                'exp' => time() + 3600,       // Expires in 1 hour
                'data' => [                   // Custom data
                    'url_token' => $user->url_token,
                    'role' => 'admin',
                    'email' => $user->email
                ]
            ];
            // Generate JWT
            $jwt = JWT::encode($payload, $secretKey, 'HS256');
            setcookie('jwt_token', $jwt, [
                'expires' => time() + 3600, // Match token exp
                'path' => '/',
                'secure' => true, // HTTPS only
                'httponly' => true, // No JS access
                'samesite' => 'Strict' // CSRF protection
            ]);
            $url_token = $user->url_token;
            redirect('panel?token=' . urldecode($url_token));
            // redirect('panel' . $token);
        }
        else
        {
            $error = 'Email or password is incorrect!';
        }
    }
    else
    {
        $error = 'Email or password is incorrect!';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png+xml" href="<?= htmlspecialchars(assets('assets/images/icons/home.png')) ?>" />
    <title>Admin Login</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(assets('assets/css/bootstrap.min.css')) ?>" media="all" type="text/css">
    <link rel="stylesheet" href="<?= htmlspecialchars(assets('assets/css/style.css')) ?>" media="all" type="text/css">
</head>

<body>
    <section id="app">
        <?php require_once '../layouts/top-nav.php' ?>

        <section style="height: 100vh; background-color: #138496;" class="d-flex justify-content-center align-items-center">
            <section style="width: 20rem;">
                <h1 class="bg-warning rounded-top px-2 mb-0 py-3 h5">Admin login</h1>
                <section class="bg-light my-0 px-2"><small class="text-danger"><?= $error !== '' ? $error : '' ?></small></section>
                <form class="pt-3 pb-1 px-2 bg-light rounded-bottom" action="<?= htmlspecialchars(url('auth/login.php')) ?>" method="post">
                    <input type="hidden" name="csrf_token" id="csrf_token" value="<?= htmlspecialchars(generateCsrfToken('login-form')) ?>">
                    <section class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="email ..." value="<?= isset($_POST['email']) ? $_POST['email'] : '' ?>">
                        <small class="text-danger"><?= $email_error !== '' ? $email_error : '' ?></small>
                    </section>
                    <section class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="password ..." value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>">
                        <small class="text-danger"><?= $password_error !== '' ? $password_error : '' ?></small>
                    </section>
                    <section class="mt-4 mb-2 d-flex justify-content-between">
                        <input type="submit" class="btn btn-success btn-sm" value="Log In">
                        <a class="btn btn-info btn-sm" href="<?= htmlspecialchars(url('auth/register.php')) ?>">Create a New Account</a>
                    </section>
                </form>
            </section>
        </section>

    </section>
    <script src="<?= htmlspecialchars(assets('assets/js/jquery.min.js')) ?>"></script>
    <script src="<?= htmlspecialchars(assets('assets/js/bootstrap.min.js')) ?>"></script>
</body>

</html>