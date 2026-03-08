# Side Hustle Platform

A simple MVC-based web application for connecting clients with professionals offering side hustle services.

## Project Structure

```
side/
│
├── index.php                    # Landing page
├── config.php                   # Configuration and session management
│
├── model/                       # Database layer
│   ├── db_connect.php          # Database connection
│   └── sidehustle_db.sql       # Database schema
│
├── controller/                  # Business logic
│   ├── login_process.php       # Login logic
│   ├── register_process.php    # Registration logic
│   └── logout.php              # Logout logic
│
└── view/                        # Frontend pages
    ├── login.php               # Login page
    ├── register.php            # Registration page
    └── dashboard.php           # User dashboard
```

## Features

- ✅ User registration (Client & Professional)
- ✅ User login with session management
- ✅ Simple dashboard after successful login
- ✅ MVC architecture (without OOP)
- ✅ Beginner-friendly procedural PHP code

## Setup Instructions

### 1. Database Setup

1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Click on "Import" tab
3. Choose the file: `model/sidehustle_db.sql`
4. Click "Go" to import the database

### 2. Configure Database Connection

Edit `model/db_connect.php` if your database credentials are different:

```php
$db_host = "localhost";
$db_user = "root";          // Your MySQL username
$db_pass = "";              // Your MySQL password
$db_name = "sideHustle_db";
```

### 3. Start Your Server

Make sure XAMPP is running with Apache and MySQL services started.

### 4. Access the Application

Open your browser and go to:
```
http://localhost/side/
```

## Default Test Accounts

### Client Account
- Email: `karim@gmail.com`
- Password: `password123`

### Professional Account
- Email: `rahim@gmail.com`
- Password: `password123`

## How to Use

1. **Home Page**: Visit `index.php` - Landing page with login/register options
2. **Register**: Create a new account as either Client or Professional
3. **Login**: Use your credentials to login
4. **Dashboard**: After successful login, you'll see the dashboard with your info
5. **Logout**: Click the logout button to end your session

## File Descriptions

### Model Layer (Database)
- **db_connect.php**: Creates connection to MySQL database

### Controller Layer (Logic)
- **login_process.php**: Validates login credentials and creates session
- **register_process.php**: Validates and registers new users
- **logout.php**: Destroys session and logs out user

### View Layer (Frontend)
- **login.php**: Login form with styling
- **register.php**: Registration form with dynamic fields based on user type
- **dashboard.php**: Protected page showing user information after login

### Configuration
- **config.php**: Contains session management and helper functions

## Security Notes

⚠️ **For Learning Purposes Only**

This is a beginner-friendly project with basic security. For production use:
- Use `password_hash()` and `password_verify()` for passwords
- Implement CSRF protection
- Add input sanitization with prepared statements (already using prepared statements)
- Use HTTPS
- Add rate limiting for login attempts

## Next Steps

You can extend this project by adding:
- Profile management pages
- Service listing and browsing
- Booking/request system
- Messaging between clients and professionals
- Payment integration
- Admin panel

## Technologies Used

- PHP (Procedural)
- MySQL
- HTML5
- CSS3
- JavaScript (minimal for form interactions)

---

**Created with beginner-friendly, procedural PHP following MVC pattern**
