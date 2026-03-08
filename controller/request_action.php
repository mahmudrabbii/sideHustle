<?php

require_once '../config.php';
require_once '../model/db_connect.php';

require_login();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = isset($_POST['request_id']) ? intval($_POST['request_id']) : 0;
    $action = isset($_POST['action']) ? clean_input($_POST['action']) : '';
    
    if ($request_id <= 0 || !in_array($action, ['accept', 'decline'])) {
        $_SESSION['error_message'] = "Invalid request.";
        header("Location: /side/view/professional_dashboard.php");
        exit();
    }
   
    $user_email = $_SESSION['user_email'];
  
    $prof_query = "SELECT id FROM professionals WHERE email = ?";
    $prof_stmt = mysqli_prepare($conn, $prof_query);
    mysqli_stmt_bind_param($prof_stmt, "s", $user_email);
    mysqli_stmt_execute($prof_stmt);
    $prof_result = mysqli_stmt_get_result($prof_stmt);
    $prof_data = mysqli_fetch_assoc($prof_result);
    mysqli_stmt_close($prof_stmt);
    
    if (!$prof_data) {
        $_SESSION['error_message'] = "Professional profile not found.";
        header("Location: /side/view/professional_dashboard.php");
        exit();
    }
    
    $prof_id = $prof_data['id'];
    
    $verify_query = "SELECT * FROM service_requests WHERE id = ? AND professional_id = ?";
    $verify_stmt = mysqli_prepare($conn, $verify_query);
    mysqli_stmt_bind_param($verify_stmt, "ii", $request_id, $prof_id);
    mysqli_stmt_execute($verify_stmt);
    $verify_result = mysqli_stmt_get_result($verify_stmt);
    
    if (mysqli_num_rows($verify_result) === 0) {
        $_SESSION['error_message'] = "Unauthorized access.";
        header("Location: /side/view/professional_dashboard.php");
        exit();
    }
    
    mysqli_stmt_close($verify_stmt);
    
  
    $new_status = ($action === 'accept') ? 'Accepted' : 'Declined';
    
    $update_query = "UPDATE service_requests SET status = ? WHERE id = ?";
    $update_stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($update_stmt, "si", $new_status, $request_id);
    
    if (mysqli_stmt_execute($update_stmt)) {
        if ($action === 'accept') {
            $_SESSION['success_message'] = "Service request accepted!";
        } else {
            $_SESSION['success_message'] = "Service request declined.";
        }
    } else {
        $_SESSION['error_message'] = "Failed to update request. Please try again.";
    }
    
    mysqli_stmt_close($update_stmt);
    mysqli_close($conn);
    
    header("Location: /side/view/professional_dashboard.php");
    exit();
    
} else {
    header("Location: /side/view/professional_dashboard.php");
    exit();
}
?>
