<?php

require_once '../config.php';
require_once '../model/db_connect.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['password']);

    $errors = array();

    if (empty($email)) {
        $errors[] = "Email is required";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (empty($errors)) {

        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {

            $user = mysqli_fetch_assoc($result);

            /*
            if ($password == $user['password']) {
            */

            if (password_verify($password, $user['password'])) {

                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['full_name'];
               /*  $_SESSION['user_type'] = $user['user_type'];
               $_SESSION['login_time'] = date('Y-m-d H:i:s'); */
                

                if ($user['user_type'] === 'professional') {
                    header("Location: /sideHustle/view/professional_dashboard.php");
                } else {
                    header("Location: /sideHustle/view/client_dashboard.php");
                }

                exit();

            } else {

                $_SESSION['login_error'] = "Invalid email or password";
                header("Location: /sideHustle/view/login.php");
                exit();

            }

        } else {

            $_SESSION['login_error'] = "Invalid email or password";
            header("Location: /sideHustle/view/login.php");
            exit();

        }

        mysqli_stmt_close($stmt);

    } else {

        $_SESSION['login_error'] = implode("<br>", $errors);
        header("Location: /sideHustle/view/login.php");
        exit();

    }

    mysqli_close($conn);

} else {

    header("Location: /sideHustle/view/login.php");
    exit();

}
?>