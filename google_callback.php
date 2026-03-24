<?php
session_start();
require_once 'config.php';
require_once 'model/db_connect.php';

// Show error and redirect to login
function oauth_fail($message) {
    $_SESSION['login_error'] = $message;
    header('Location: /sideHustle/view/login.php');
    exit();
}

// Send POST request to Google
function oauth_post($url, $fields) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$status, $response];
}

// Send GET request with Bearer token
function oauth_get($url, $token) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $token]);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return [$status, $response];
}

// Ensure Google OAuth is configured
if (empty(GOOGLE_CLIENT_ID) || empty(GOOGLE_CLIENT_SECRET) || empty(GOOGLE_REDIRECT_URI)) {
    oauth_fail('Google login is not configured.');
}

// Validate callback parameters
if (empty($_GET['code']) || empty($_GET['state']) || empty($_SESSION['oauth_state']) 
    || !hash_equals($_SESSION['oauth_state'], $_GET['state'])) {
    oauth_fail('Invalid login attempt.');
}
unset($_SESSION['oauth_state']);

// Exchange code for access token
list($tokenStatus, $tokenRaw) = oauth_post('https://oauth2.googleapis.com/token', [
    'code' => $_GET['code'],
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'grant_type' => 'authorization_code'
]);
$tokenData = json_decode($tokenRaw, true);
if ($tokenStatus !== 200 || empty($tokenData['access_token'])) {
    oauth_fail('Failed to get access token.');
}

// Fetch user profile from Google
list($profileStatus, $profileRaw) = oauth_get('https://www.googleapis.com/oauth2/v3/userinfo', $tokenData['access_token']);
$profile = json_decode($profileRaw, true);
if ($profileStatus !== 200 || empty($profile['email']) || (isset($profile['email_verified']) && !$profile['email_verified'])) {
    oauth_fail('Failed to fetch verified Google profile.');
}

// Clean data
$email = clean_input($profile['email']);
$fullName = !empty($profile['name']) ? clean_input($profile['name']) : clean_input(explode('@', $email)[0]);

// Check if user exists
$stmt = mysqli_prepare($conn, 'SELECT * FROM users WHERE email = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// If new user, create as client
if (!$user) {
    $password = bin2hex(random_bytes(24)); // random password
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $userType = 'client';

    // Insert into users table
    $stmt = mysqli_prepare($conn, 'INSERT INTO users (email, password, full_name, user_type) VALUES (?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssss', $email, $hash, $fullName, $userType);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Insert into clients table
    $defaultPhone = 'N/A';
    $defaultArea = 'Dhaka';
    $stmt = mysqli_prepare($conn, 'INSERT INTO clients (full_name, email, phone, area_of_work) VALUES (?, ?, ?, ?)');
    mysqli_stmt_bind_param($stmt, 'ssss', $fullName, $email, $defaultPhone, $defaultArea);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Reload user
    $stmt = mysqli_prepare($conn, 'SELECT * FROM users WHERE email = ? LIMIT 1');
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

// Set session
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_name'] = $user['full_name'];
$_SESSION['user_type'] = 'client'; // always client

// Redirect to client dashboard
header('Location: /sideHustle/view/client_dashboard.php');
exit();
?>