<?php
require_once '../config.php';

if (empty(GOOGLE_CLIENT_ID) || empty(GOOGLE_REDIRECT_URI)) {
    $_SESSION['login_error'] = 'Google login is not configured. Add your Client ID in config.php.';
    header('Location: /sideHustle/view/login.php');
    exit();
}

$state = bin2hex(random_bytes(16));
$_SESSION['oauth_state'] = $state;

$url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query(array(
    'client_id' => GOOGLE_CLIENT_ID,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'response_type' => 'code',
    'scope' => 'openid email profile',
    'state' => $state,
    'prompt' => 'select_account'
));

header('Location: ' . $url);
exit();
?>
