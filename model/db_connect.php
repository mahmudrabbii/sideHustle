<?php
// Database connection file
// This file contains the database connection logic

// Database credentials
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "sideHustle_db";

// Create connection
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($conn, "utf8");
?>
