<?php
require_once '../config.php';
redirect_if_logged_in();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Side Hustle Platform</title>
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
            padding: 50px 40px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        
        h1 {
            color: #333;
            margin-bottom: 10px;
            font-size: 32px;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 40px;
            font-size: 16px;
        }
        
        .user-type-cards {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .user-type-card {
            flex: 1;
            padding: 30px;
            border: 2px solid #ddd;
            border-radius: 10px;
            text-decoration: none;
            transition: all 0.3s;
            background: white;
        }
        
        .user-type-card:hover {
            border-color: #667eea;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .user-type-card .icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .user-type-card h2 {
            color: #333;
            font-size: 22px;
            margin-bottom: 10px;
        }
        
        .user-type-card p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .login-link {
            margin-top: 30px;
            color: #666;
        }
        
        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create Account</h1>
        <p class="subtitle">Choose your account type to register</p>
        
        <div class="user-type-cards">
            <a href="register_client.php" class="user-type-card">
                <div class="icon">👥</div>
                <h2>I'm a Client</h2>
                <p>Looking to hire skilled professionals for your projects</p>
            </a>
            
            <a href="register_professional.php" class="user-type-card">
                <div class="icon">💼</div>
                <h2>I'm a Professional</h2>
                <p>Ready to offer your services and skills to clients</p>
            </a>
        </div>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </div>
</body>
</html>
