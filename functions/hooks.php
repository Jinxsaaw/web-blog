<?php 
if (!defined('APP_GUARD'))
{
    http_response_code(401);
}

// config
define("DOMAIN", 'http://localhost/web-blog');

// Helpers AKA Hooks
function redirect($url)
{
    header("Location: " . trim(DOMAIN, "/ ") . "/" . trim($url, "/ "));
    exit;
}

function assets($files)
{
    return trim(DOMAIN, "/ ") . "/" . trim($files, "/ ");
}

function url($url)
{
    return trim(DOMAIN, "/ ") . "/" . trim($url, "/ ");
}

function dd($var)
{
    echo "<pre>";
    var_dump($var);
    die();
}

# Generate a CSRF token for a specific form
function generateCsrfToken($formId)
{
    if (session_status() === PHP_SESSION_NONE)
    {
        session_start();
    }
    $token = bin2hex(random_bytes(32));
    $_SESSION['csrf_tokens'][$formId] = $token;
    return $token;
}

# Validate a CSRF token for a specific form
function verifyCsrfToken($formId, $token)
{
    if (!isset($_SESSION['csrf_tokens'][$formId]) || !is_string($token) )
    {
        return false;
    }
    $isValid = hash_equals($_SESSION['csrf_tokens'][$formId], $token);
    unset($_SESSION['csrf_tokens'][$formId]); // Token can be used only once
    return $isValid;
}

# XSS Protection
// function sanitizeOutput($buffer)
// {
//     $search = [
//         '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
//         '/[^\S ]+\</s',     // strip whitespaces before tags, except space
//         '/(\s)+/s',         // shorten multiple whitespace sequences
//         '/<!--(.|\s)*?-->/' // Remove HTML comments
//     ];
//     $replace = [
//         '>',
//         '<',
//         '\\1',
//         ''
//     ];
//     $buffer = preg_replace($search, $replace, $buffer);
//     return $buffer;
// }

function sanitizeInput($data)
{
    $data = trim($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    $data = stripslashes($data);
    return $data;
}






?>