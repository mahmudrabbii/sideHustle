<?php
require_once '../config.php';
require_once '../model/db_connect.php';

require_login();

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'client') {
    header("Location: /sideHustle/view/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /sideHustle/view/client_dashboard.php");
    exit();
}

$professional_id = isset($_POST['professional_id']) ? (int)$_POST['professional_id'] : 0;
$service_type = isset($_POST['service_type']) ? clean_input($_POST['service_type']) : '';
$request_description = isset($_POST['request_description']) ? clean_input($_POST['request_description']) : '';

if ($professional_id <= 0 || empty($service_type) || empty($request_description)) {
    $_SESSION['error_message'] = 'Please fill all request fields.';
    header("Location: /sideHustle/view/client_dashboard.php");
    exit();
}

$client_query = "SELECT id FROM clients WHERE email = ?";
$stmt = mysqli_prepare($conn, $client_query);
mysqli_stmt_bind_param($stmt, "s", $_SESSION['user_email']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$client = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$client) {
    $_SESSION['error_message'] = 'Client profile not found.';
    header("Location: /sideHustle/view/client_dashboard.php");
    exit();
}

$professional_query = "SELECT id FROM professionals WHERE id = ?";
$stmt = mysqli_prepare($conn, $professional_query);
mysqli_stmt_bind_param($stmt, "i", $professional_id);
mysqli_stmt_execute($stmt);
$professional_result = mysqli_stmt_get_result($stmt);
$professional = mysqli_fetch_assoc($professional_result);
mysqli_stmt_close($stmt);

if (!$professional) {
    $_SESSION['error_message'] = 'Professional not found.';
    header("Location: /sideHustle/view/client_dashboard.php");
    exit();
}

$insert_query = "
    INSERT INTO service_requests (
        client_id,
        professional_id,
        service_type,
        request_description,
        status
    ) VALUES (?, ?, ?, ?, 'Pending')
";
$stmt = mysqli_prepare($conn, $insert_query);
mysqli_stmt_bind_param($stmt, "iiss", $client['id'], $professional_id, $service_type, $request_description);

if (mysqli_stmt_execute($stmt)) {
    $_SESSION['success_message'] = 'Request sent successfully. The professional can now review it.';
} else {
    $_SESSION['error_message'] = 'Could not send request. Please try again.';
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

header("Location: /sideHustle/view/client_dashboard.php");
exit();
?>
