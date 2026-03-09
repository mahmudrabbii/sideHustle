<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
