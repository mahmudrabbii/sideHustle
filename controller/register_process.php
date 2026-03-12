<?php
// Registration process controller
// This file handles user registration for both clients and professionals

require_once '../config.php';
require_once '../model/db_connect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get form data
    $full_name = clean_input($_POST['full_name']);
    $email = clean_input($_POST['email']);
    $password = clean_input($_POST['password']);
    $confirm_password = clean_input($_POST['confirm_password']);
    $phone = clean_input($_POST['phone']);
    $user_type = clean_input($_POST['user_type']);
    
    // Validation
    $errors = array();
    
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }
    
    if ($password != $confirm_password) {
        $errors[] = "Passwords do not match";
    
    }

     $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($user_type) || !in_array($user_type, ['client', 'professional'])) {
        $errors[] = "Please select a valid user type";
    }
    
    // Check if email already exists
    if (empty($errors)) {
        $check_query = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $check_result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            $errors[] = "Email already exists";
        }
        mysqli_stmt_close($stmt);
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        
        // Insert into users table
        $insert_user_query = "INSERT INTO users (email, password, full_name, user_type) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_user_query);
        mysqli_stmt_bind_param($stmt, "ssss", $email, $hashedPassword, $full_name, $user_type);
        
        if (mysqli_stmt_execute($stmt)) {
            $user_id = mysqli_insert_id($conn);
            
            // Insert into respective table based on user type
            if ($user_type == 'client') {
                // Insert into clients table
                $area_of_work = clean_input($_POST['area_of_work']);
                $insert_client_query = "INSERT INTO clients (full_name, email, phone, area_of_work) VALUES (?, ?, ?, ?)";
                $stmt2 = mysqli_prepare($conn, $insert_client_query);
                mysqli_stmt_bind_param($stmt2, "ssss", $full_name, $email, $phone, $area_of_work);
                mysqli_stmt_execute($stmt2);
                mysqli_stmt_close($stmt2);
                
            } elseif ($user_type == 'professional') {
                // Insert into professionals table
                $first_profession = clean_input($_POST['first_profession']);
                $expertise_area = clean_input($_POST['expertise_area']);
                $experience_years = clean_input($_POST['experience_years']);
                $description = clean_input($_POST['description']);
                $hourly_rate = clean_input($_POST['hourly_rate']);
                $availability = clean_input($_POST['availability']);
                $area_of_operation = clean_input($_POST['area_of_operation']);
                
                $insert_prof_query = "INSERT INTO professionals (full_name, email, phone, first_profession, expertise_area, experience_years, description, hourly_rate, availability, area_of_operation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt2 = mysqli_prepare($conn, $insert_prof_query);
                mysqli_stmt_bind_param($stmt2, "sssssissss", $full_name, $email, $phone, $first_profession, $expertise_area, $experience_years, $description, $hourly_rate, $availability, $area_of_operation);
                mysqli_stmt_execute($stmt2);
                mysqli_stmt_close($stmt2);
            }
            
            // Set success message
            $_SESSION['register_success'] = "Registration successful! Please login.";
            header("Location: /sideHustle/view/login.php");
            exit();
            
        } else {
            $_SESSION['register_error'] = "Registration failed. Please try again.";
            header("Location: /sideHustle/view/register.php");
            exit();
        }
        
        mysqli_stmt_close($stmt);
        
    } else {
        // Store errors in session
        $_SESSION['register_error'] = implode("<br>", $errors);
        header("Location: /sideHustle/view/register.php");
        exit();
    }
    
    mysqli_close($conn);
    
} else {
    // If not POST request, redirect to register page
    header("Location: /sideHustle/view/register.php");
    exit();
}
?>
