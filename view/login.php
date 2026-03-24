<?php
require_once '../config.php';
redirect_if_logged_in();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Side Hustle Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
            font-size: 28px;
        }
        
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: bold;
        }
        
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
        }

        .oauth-divider {
            margin: 20px 0;
            text-align: center;
            color: #777;
            position: relative;
            font-size: 13px;
        }

        .oauth-divider::before,
        .oauth-divider::after {
            content: "";
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background: #ddd;
        }

        .oauth-divider::before {
            left: 0;
        }

        .oauth-divider::after {
            right: 0;
        }

        .google-btn {
            display: inline-block;
            width: 100%;
            padding: 11px;
            border: 1px solid #dadce0;
            border-radius: 5px;
            color: #3c4043;
            text-decoration: none;
            text-align: center;
            font-weight: bold;
            background: #fff;
            transition: background-color 0.2s, transform 0.2s;
        }

        .google-btn:hover {
            background: #f8f9fa;
            transform: translateY(-1px);
        }
        
        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
        
        .alert-error {
            background: #fee;
            color: #c33;
            border: 1px solid #fcc;
        }
        
        .alert-success {
            background: #efe;
            color: #3c3;
            border: 1px solid #cfc;
        }
        
        .register-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }
        
        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        
        .register-link a:hover {
            text-decoration: underline;
        }
        
        .test-credentials {
            background: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        
        .test-credentials strong {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        
        .test-credentials p {
            margin: 3px 0;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>sideHustle</h1>
        <p class="subtitle">Login to your Side Hustle account</p>
        
        <?php
        
        if (isset($_SESSION['login_error'])) {
            echo '<div class="alert alert-error">' . $_SESSION['login_error'] . '</div>';
            unset($_SESSION['login_error']);
        }
        
        
        if (isset($_SESSION['register_success'])) {
            echo '<div class="alert alert-success">' . $_SESSION['register_success'] . '</div>';
            unset($_SESSION['register_success']);
        }
        ?>
        <!--  

        <div class="test-credentials">
            <strong>Test Credentials:</strong>
            <p><b>Client:</b> karim@gmail.com / password123</p>
            <p><b>Professional:</b> rahim@gmail.com / password123</p>
        </div>

        -->
        
        <form action="../controller/login_process.php" method="POST">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>

        <div class="oauth-divider">or</div>
        <a class="google-btn" href="../controller/google_login.php">Continue with Google</a>
        
        <div class="register-link">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</body>
</html>
