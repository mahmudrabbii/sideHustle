<?php
require_once '../config.php';
require_once '../model/db_connect.php';
require_login();

if ($_SESSION['user_type'] !== 'professional') {
    header("Location: /sideHustle/view/dashboard.php");
    exit();
}

$user_email = $_SESSION['user_email'];
$professional_query = "SELECT * FROM professionals WHERE email = ?";
$stmt = mysqli_prepare($conn, $professional_query);
mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);
$professional_result = mysqli_stmt_get_result($stmt);
$current_professional = mysqli_fetch_assoc($professional_result);
mysqli_stmt_close($stmt);

$service_requests = array();
if ($current_professional) {
    $prof_id = $current_professional['id'];
    $requests_query = "
        SELECT 
            sr.id,
            sr.service_type,
            sr.request_description,
            sr.request_date,
            sr.status,
            c.full_name as client_name,
            c.email as client_email
        FROM service_requests sr
        JOIN clients c ON sr.client_id = c.id
        WHERE sr.professional_id = ?
        ORDER BY sr.request_date DESC
    ";
    $stmt = mysqli_prepare($conn, $requests_query);
    mysqli_stmt_bind_param($stmt, "i", $prof_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($row = mysqli_fetch_assoc($result)) {
        $service_requests[] = $row;
    }
    mysqli_stmt_close($stmt);
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Professional Dashboard - Side Hustle</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{
font-family:Arial, sans-serif;
background:#f5f7fa;
min-height:100vh;
}



.navbar{
background:linear-gradient(135deg,#667eea,#764ba2);
color:white;
padding:20px 40px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

.navbar h1{
font-size:24px;
}

.user-info{
display:flex;
gap:20px;
align-items:center;
}

.logout-btn{
background:rgba(255,255,255,0.2);
padding:8px 18px;
border-radius:5px;
border:1px solid white;
color:white;
text-decoration:none;
}

.logout-btn:hover{
background:white;
color:#667eea;
}



.dashboard{
display:grid;
grid-template-columns:220px 1fr;
gap:25px;
max-width:1200px;
margin:40px auto;
padding:0 20px;
}



.sidebar{
background:white;
padding:25px;
border-radius:10px;
box-shadow:0 2px 10px rgba(0,0,0,0.1);
height:fit-content;
}

.sidebar h3{
margin-bottom:15px;
color:#667eea;
}

.sidebar-menu{
list-style:none;
}

.sidebar-menu li{
margin-bottom:10px;
}

.sidebar-menu a{
display:block;
padding:10px;
background:#f5f7fa;
border-radius:5px;
text-decoration:none;
color:#333;
}

.sidebar-menu a:hover{
background:#667eea;
color:white;
}



.content{
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

.card{
background:#f9fafc;
padding:25px;
border-radius:10px;
border-left:5px solid #667eea;
margin-bottom:25px;
}

.card-header{
font-size:18px;
font-weight:bold;
margin-bottom:15px;
}

.card p{
margin-bottom:10px;
line-height:1.6;
}


table{
width:100%;
border-collapse:collapse;
margin-top:15px;
}

thead{
background:#667eea;
color:white;
}

th,td{
padding:12px;
border-bottom:1px solid #ddd;
text-align:left;
}

tbody tr:hover{
background:#f5f7fa;
}


.badge{
padding:4px 12px;
border-radius:20px;
font-size:12px;
font-weight:bold;
display:inline-block;
}

.badge-pending{
background:#fff3cd;
color:#856404;
}

.badge-accepted{
background:#d4edda;
color:#155724;
}

.badge-completed{
background:#d1ecf1;
color:#0c5460;
}


.btn{
background:#667eea;
border:none;
color:white;
padding:6px 12px;
border-radius:5px;
cursor:pointer;
font-size:13px;
margin-right:5px;
}

.btn:hover{
background:#5a67d8;
}

.btn-accept{
background:#28a745;
}

.btn-accept:hover{
background:#218838;
}

.btn-decline{
background:#dc3545;
}

.btn-decline:hover{
background:#c82333;
}

.action-buttons{
display:flex;
gap:5px;
}


.alert{
background:#f8d7da;
color:#721c24;
padding:15px;
border-radius:6px;
margin-top:20px;
border-left:4px solid #721c24;
}

.alert-info{
background:#d1ecf1;
color:#0c5460;
border-left:4px solid #0c5460;
}

h2{
margin-bottom:20px;
color:#333;
}

h3{
margin-bottom:15px;
color:#333;
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


<div class="dashboard">



<aside class="sidebar">

<h3>Menu</h3>

<ul class="sidebar-menu">
<li><a href="professional_dashboard.php">Dashboard</a></li>
<!--
<li><a href="profile.php">Profile</a></li>
-->
</ul>

</aside>



<div class="content">

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>

<?php if(isset($_SESSION['success_message'])): ?>
<div class="alert alert-info" style="background:#d4edda; color:#155724; border-left:4px solid #155724;">
<?php echo htmlspecialchars($_SESSION['success_message']); ?>
</div>
<?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if(isset($_SESSION['error_message'])): ?>
<div class="alert" style="background:#f8d7da; color:#721c24; border-left:4px solid #721c24;">
<?php echo htmlspecialchars($_SESSION['error_message']); ?>
</div>
<?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<?php if($current_professional): ?>

<div class="card">

<div class="card-header">Your Profile</div>

<p><strong>Email:</strong> <?php echo htmlspecialchars($current_professional['email']); ?></p>

<p><strong>Phone:</strong> <?php echo htmlspecialchars($current_professional['phone']); ?></p>

<p><strong>Primary Profession:</strong> <?php echo htmlspecialchars($current_professional['first_profession']); ?></p>

<p><strong>Expertise Area:</strong> <?php echo htmlspecialchars($current_professional['expertise_area']); ?></p>

<p><strong>Experience:</strong> <?php echo $current_professional['experience_years']; ?> years</p>

<p><strong>Hourly Rate:</strong> $<?php echo number_format($current_professional['hourly_rate'], 2); ?> /hour</p>

<p><strong>Availability:</strong> <?php echo htmlspecialchars($current_professional['availability']); ?></p>

<p><strong>Area of Operation:</strong> <?php echo htmlspecialchars($current_professional['area_of_operation']); ?></p>

<p><strong>About Your Services:</strong></p>
<p style="background:#fff; padding:10px; border-radius:5px;"><?php echo nl2br(htmlspecialchars($current_professional['description'])); ?></p>

</div>

<h3>Service Requests (<?php echo count($service_requests); ?>)</h3>

<?php if(count($service_requests) > 0): ?>

<table>

<thead>

<tr>
<th>Client Name</th>
<th>Service Type</th>
<th>Request Date</th>
<th>Status</th>
<th>Description</th>
<th>Action</th>
</tr>

</thead>

<tbody>

<?php foreach($service_requests as $req): ?>

<tr>

<td><?php echo htmlspecialchars($req['client_name']); ?></td>

<td><?php echo htmlspecialchars($req['service_type']); ?></td>

<td><?php echo date('M d, Y', strtotime($req['request_date'])); ?></td>

<td>
<span class="badge badge-<?php echo strtolower(str_replace(' ', '', $req['status'])); ?>">
<?php echo htmlspecialchars($req['status']); ?>
</span>
</td>

<td><?php echo htmlspecialchars(substr($req['request_description'], 0, 50)) . (strlen($req['request_description']) > 50 ? '...' : ''); ?></td>

<td>
<div class="action-buttons">
<?php if($req['status'] === 'Pending'): ?>
<form method="POST" action="../controller/request_action.php" style="display:inline;">
<input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
<input type="hidden" name="action" value="accept">
<button type="submit" class="btn btn-accept">Accept</button>
</form>
<form method="POST" action="../controller/request_action.php" style="display:inline;">
<input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
<input type="hidden" name="action" value="decline">
<button type="submit" class="btn btn-decline">Decline</button>
</form>
<?php else: ?>
<span class="badge badge-<?php echo strtolower(str_replace(' ', '', $req['status'])); ?>">No action</span>
<?php endif; ?>
</div>
</td>

<?php endforeach; ?>

</tbody>

</table>

<?php else: ?>

<div class="alert alert-info">
No service requests yet. Keep your profile updated to attract clients!
</div>

<?php endif; ?>

<?php else: ?>

<div class="alert">
Error: Professional profile not found. Please contact support.
</div>

<?php endif; ?>

</div>

</div>

</body>
</html>
