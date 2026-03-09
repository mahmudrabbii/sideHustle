<?php
// Logout controller
// This file handles user logout

require_once '../config.php';

// Destroy session
session_destroy();

// Redirect to login page
header("Location: /sideHustle/view/login.php");
exit();
?>
