<?php
require_once '../config.php';
redirect_if_logged_in();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Registration - Side Hustle Platform</title>
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
            max-width: 500px;
            margin: 20px 0;
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
        
        .user-type-badge {
            background: #fff3cd;
            color: #856404;
            padding: 10px 20px;
            border-radius: 20px;
            display: inline-block;
            font-weight: bold;
            margin-bottom: 20px;
        }
        
        .badge-container {
            text-align: center;
            margin-bottom: 20px;
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
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }
        
        input:focus, select:focus, textarea:focus {
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
        
        .login-link {
            text-align: center;
            margin-top: 20px;
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
        
        .half-width {
            display: flex;
            gap: 10px;
        }
        
        .half-width .form-group {
            flex: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Professional Registration</h1>
        <p class="subtitle">Create your professional account</p>
        
        <div class="badge-container">
            <span class="user-type-badge">I'm a Professional</span>
        </div>
        
        <?php
        // Display error message
        if (isset($_SESSION['register_error'])) {
            echo '<div class="alert alert-error">' . $_SESSION['register_error'] . '</div>';
            unset($_SESSION['register_error']);
        }
        ?>
        
        <form action="../controller/register_process.php" method="POST">
            
            <input type="hidden" name="user_type" value="professional">
            
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" placeholder="Enter your full name" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            
            <div class="half-width">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="At least 6 characters" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter password" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="phone">Phone Number</label>
                <input type="text" id="phone" name="phone" placeholder="Enter your phone number" required>
            </div>
            
            <div class="form-group">
                <label for="first_profession">Primary Profession</label>
                <input type="text" id="first_profession" name="first_profession" placeholder="e.g., Software Developer" required>
            </div>
            
            <div class="form-group">
                <label for="expertise_area">Expertise Area (Side Hustle)</label>
                <input type="text" id="expertise_area" name="expertise_area" placeholder="e.g., Web Development" required>
            </div>
            
            <div class="half-width">
                <div class="form-group">
                    <label for="experience_years">Years of Experience</label>
                    <input type="number" id="experience_years" name="experience_years" placeholder="0" min="0" value="0" required>
                </div>
                
                <div class="form-group">
                    <label for="hourly_rate">Hourly Rate ($)</label>
                    <input type="number" id="hourly_rate" name="hourly_rate" placeholder="15.00" step="0.01" min="0" value="15.00" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="availability">Availability</label>
                <select id="availability" name="availability" required>
                    <option value="">Select availability</option>
                    <option value="Morning">Morning</option>
                    <option value="Evening">Evening</option>
                    <option value="Weekend">Weekend</option>
                    <option value="Flexible">Flexible</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="area_of_operation">Area of Operation</label>
                <input type="text" id="area_of_operation" name="area_of_operation" placeholder="e.g., Dhaka" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Brief description of your services" required></textarea>
            </div>
            
            <button type="submit">Register as Professional</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="login.php">Login here</a><br>
            Want to register as Client? <a href="register_client.php">Click here</a>
        </div>
    </div>
</body>
</html>
