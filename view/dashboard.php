<?php
require_once '../config.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Side Hustle Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar h1 {
            font-size: 24px;
        }
        
        .navbar .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 10px 20px;
            border: 1px solid white;
            border-radius: 5px;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .logout-btn:hover {
            background: white;
            color: #667eea;
        }
        
        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .welcome-card {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .welcome-card h2 {
            color: #333;
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .welcome-card p {
            color: #666;
            font-size: 16px;
        }
        
        .status-success {
            display: inline-block;
            background: #d4edda;
            color: #155724;
            padding: 15px 30px;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            border: 1px solid #c3e6cb;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .info-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .info-card h3 {
            color: #667eea;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        
        .info-card p {
            color: #333;
            font-size: 18px;
            font-weight: bold;
        }
        
        .user-type-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            text-transform: capitalize;
        }
        
        .badge-client {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .badge-professional {
            background: #fff3cd;
            color: #856404;
        }
        
        .note {
            background: #e7f3ff;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
            border-left: 4px solid #667eea;
        }
        
        .note h4 {
            color: #333;
            margin-bottom: 10px;
        }
        
        .note p {
            color: #666;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Side Hustle Platform</h1>
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="../controller/logout.php" class="logout-btn">Logout</a>
        </div>
    </nav>
    
    <div class="container">
        <div class="welcome-card">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! 🎉</h2>
            <p>You have successfully logged in to the Side Hustle Platform</p>
            
            <div class="status-success">
                 Login Successful
            </div>
            <!--
            <div class="info-grid">
                <div class="info-card">
                    <h3>User Type</h3>
                    <p>
                        <span class="user-type-badge badge-<?php echo $_SESSION['user_type']; ?>">
                            <?php echo ucfirst($_SESSION['user_type']); ?>
                        </span>
                    </p>
                </div>
                
                <div class="info-card">
                    <h3>Email</h3>
                    <p><?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
                </div>
                
                <div class="info-card">
                    <h3>Login Time</h3>
                    <p><?php echo $_SESSION['login_time']; ?></p>
                </div>
-->
                
                <div class="info-card">
                    <h3>User ID</h3>
                    <p>#<?php echo $_SESSION['user_id']; ?></p>
                </div>
            </div>
        </div>
        
       
    </div>
</body>
</html>
