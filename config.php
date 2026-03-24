<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function load_env_file($filePath) {
    if (!file_exists($filePath)) {
        return;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return;
    }

    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || strpos($trimmed, '#') === 0) {
            continue;
        }

        $parts = explode('=', $trimmed, 2);
        if (count($parts) !== 2) {
            continue;
        }

        $name = trim($parts[0]);
        $value = trim($parts[1]);
        $value = rtrim($value, ';');

        if ($value !== '' && (($value[0] === '"' && substr($value, -1) === '"') || ($value[0] === "'" && substr($value, -1) === "'"))) {
            $value = substr($value, 1, -1);
        }

        if (getenv($name) === false) {
            putenv($name . '=' . $value);
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

function env($key, $default = null) {
    $value = getenv($key);
    return $value === false ? $default : $value;
}

load_env_file(__DIR__ . '/.env');

// ─── Google OAuth ────────────────────────────────────────────────────────────


/////OAuth credentials Client ID and Client Secret should be here

define('GOOGLE_CLIENT_ID',     env('GOOGLE_CLIENT_ID', ''));
define('GOOGLE_CLIENT_SECRET', env('GOOGLE_CLIENT_SECRET', ''));

// Keep this path in sync with the folder name inside htdocs.
define('APP_BASE_PATH', env('APP_BASE_PATH', '/sideHustle'));

function get_app_base_url() {
    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    $scheme = $isHttps ? 'https' : 'http';
    $host = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
    return $scheme . '://' . $host . APP_BASE_PATH;
}

define('GOOGLE_REDIRECT_URI', env('GOOGLE_REDIRECT_URI', get_app_base_url() . '/google_callback.php'));
// ─────────────────────────────────────────────────────────────────────────────

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /sideHustle/view/login.php");
        exit();
    }
}


function redirect_if_logged_in() {
    if (isset($_SESSION['user_id'])) {
        if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'professional') {
            header("Location: /sideHustle/view/professional_dashboard.php");
        } else {
            header("Location: /sideHustle/view/client_dashboard.php");
        }
        exit();
    }
}

function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
