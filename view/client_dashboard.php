<?php
require_once '../config.php';
require_once '../model/db_connect.php';
require_login();

if ($_SESSION['user_type'] !== 'client') {
    header("Location: /side/view/professional_dashboard.php");
    exit();
}

$user_email = $_SESSION['user_email'];
$client_query = "SELECT * FROM clients WHERE email = ?";
$stmt = mysqli_prepare($conn, $client_query);
mysqli_stmt_bind_param($stmt, "s", $user_email);
mysqli_stmt_execute($stmt);
$client_result = mysqli_stmt_get_result($stmt);
$current_client = mysqli_fetch_assoc($client_result);
mysqli_stmt_close($stmt);

if (!$current_client) {
    $_SESSION['error_message'] = "Client profile not found.";
    header("Location: /sideHustle/view/login.php");
    exit();
}

$selected_expertise = isset($_GET['expertise']) ? clean_input($_GET['expertise']) : '';

$expertise_list = array();
$expertise_query = "SELECT DISTINCT expertise_area FROM professionals ORDER BY expertise_area ASC";
$expertise_result = mysqli_query($conn, $expertise_query);
while ($row = mysqli_fetch_assoc($expertise_result)) {
    $expertise_list[] = $row['expertise_area'];
}

$professionals = array();
if (!empty($selected_expertise)) {
    $pros_query = "
        SELECT *
        FROM professionals
        WHERE area_of_operation = ? AND expertise_area = ?
        ORDER BY experience_years DESC, hourly_rate ASC
    ";
    $stmt = mysqli_prepare($conn, $pros_query);
    mysqli_stmt_bind_param($stmt, "ss", $current_client['area_of_work'], $selected_expertise);
} else {
    $pros_query = "
        SELECT *
        FROM professionals
        WHERE area_of_operation = ?
        ORDER BY experience_years DESC, hourly_rate ASC
    ";
    $stmt = mysqli_prepare($conn, $pros_query);
    mysqli_stmt_bind_param($stmt, "s", $current_client['area_of_work']);
}

mysqli_stmt_execute($stmt);
$pros_result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($pros_result)) {
    $professionals[] = $row;
}
mysqli_stmt_close($stmt);

$my_requests = array();
$requests_query = "
    SELECT
        sr.id,
        sr.service_type,
        sr.request_description,
        sr.request_date,
        sr.status,
        p.full_name AS professional_name,
        p.email AS professional_email,
        p.phone AS professional_phone
    FROM service_requests sr
    JOIN professionals p ON sr.professional_id = p.id
    WHERE sr.client_id = ?
    ORDER BY sr.request_date DESC
";
$stmt = mysqli_prepare($conn, $requests_query);
mysqli_stmt_bind_param($stmt, "i", $current_client['id']);
mysqli_stmt_execute($stmt);
$req_result = mysqli_stmt_get_result($stmt);
while ($row = mysqli_fetch_assoc($req_result)) {
    $my_requests[] = $row;
}
mysqli_stmt_close($stmt);

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Client Dashboard - Side Hustle</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:Arial,sans-serif;background:#f5f7fa;min-height:100vh;}
.navbar{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;padding:20px 40px;display:flex;justify-content:space-between;align-items:center;box-shadow:0 2px 10px rgba(0,0,0,0.1);}
.navbar h1{font-size:24px;}
.user-info{display:flex;gap:20px;align-items:center;}
.logout-btn{background:rgba(255,255,255,0.2);padding:8px 18px;border-radius:5px;border:1px solid #fff;color:#fff;text-decoration:none;}
.logout-btn:hover{background:#fff;color:#667eea;}
.dashboard{display:grid;grid-template-columns:240px 1fr;gap:25px;max-width:1250px;margin:40px auto;padding:0 20px;}
.sidebar{background:#fff;padding:25px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);height:fit-content;}
.sidebar h3{margin-bottom:15px;color:#667eea;}
.sidebar ul{list-style:none;}
.sidebar li{margin-bottom:10px;}
.sidebar a{display:block;padding:10px;background:#f5f7fa;border-radius:5px;text-decoration:none;color:#333;}
.sidebar a:hover{background:#667eea;color:#fff;}
.content{background:#fff;padding:30px;border-radius:10px;box-shadow:0 2px 10px rgba(0,0,0,0.1);}
.headline{margin-bottom:8px;color:#333;}
.subline{color:#666;margin-bottom:20px;}
.filter-box{background:#f9fafc;padding:20px;border-radius:10px;border-left:5px solid #667eea;margin-bottom:20px;}
.filter-row{display:flex;gap:10px;flex-wrap:wrap;}
select,input,textarea{padding:10px;border:1px solid #ddd;border-radius:6px;font-size:14px;width:100%;}
.btn{background:#667eea;border:none;color:#fff;padding:10px 14px;border-radius:6px;cursor:pointer;font-size:14px;text-decoration:none;display:inline-block;}
.btn:hover{background:#5a67d8;}
.btn-alt{background:#17a2b8;}
.btn-alt:hover{background:#138496;}
.pros-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;margin-top:10px;}
.pro-card{background:#f9fafc;border:1px solid #e8ecf2;border-radius:10px;padding:16px;}
.pro-name{font-size:18px;color:#333;margin-bottom:6px;}
.chip{display:inline-block;padding:4px 10px;border-radius:20px;font-size:12px;font-weight:bold;background:#d1ecf1;color:#0c5460;margin-bottom:10px;}
.pro-meta{font-size:14px;color:#444;line-height:1.7;}
.request-form{margin-top:12px;background:#fff;padding:12px;border-radius:8px;border:1px solid #ececec;}
.request-form label{display:block;font-size:13px;font-weight:bold;color:#444;margin-bottom:5px;margin-top:8px;}
.table-wrap{overflow:auto;margin-top:20px;}
table{width:100%;border-collapse:collapse;min-width:760px;}
thead{background:#667eea;color:#fff;}
th,td{padding:12px;border-bottom:1px solid #ddd;text-align:left;}
tbody tr:hover{background:#f5f7fa;}
.badge{padding:4px 12px;border-radius:20px;font-size:12px;font-weight:bold;display:inline-block;}
.badge-pending{background:#fff3cd;color:#856404;}
.badge-accepted{background:#d4edda;color:#155724;}
.badge-completed{background:#d1ecf1;color:#0c5460;}
.badge-declined{background:#f8d7da;color:#721c24;}
.alert{padding:12px;border-radius:8px;margin-bottom:16px;}
.alert-success{background:#d4edda;color:#155724;border-left:4px solid #155724;}
.alert-error{background:#f8d7da;color:#721c24;border-left:4px solid #721c24;}
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
        <ul>
            <li><a href="client_dashboard.php">Client Dashboard</a></li>
            <li><a href="#nearby">Nearby Professionals</a></li>
            <li><a href="#my-requests">My Requests</a></li>
        </ul>
    </aside>

    <div class="content">
        <h2 class="headline">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
        <p class="subline">Area: <?php echo htmlspecialchars($current_client['area_of_work']); ?> • Find professionals around you and contact by expertise.</p>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['success_message']); ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($_SESSION['error_message']); ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <div class="filter-box" id="nearby">
            <h3 style="margin-bottom:12px;color:#333;">Find professionals around your area</h3>
            <form method="GET" action="client_dashboard.php" class="filter-row">
                <div style="flex:1;min-width:220px;">
                    <select name="expertise">
                        <option value="">All expertise</option>
                        <?php foreach ($expertise_list as $exp): ?>
                            <option value="<?php echo htmlspecialchars($exp); ?>" <?php echo ($selected_expertise === $exp) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($exp); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn">Search</button>
                <a href="client_dashboard.php" class="btn btn-alt">Reset</a>
            </form>
        </div>

        <?php if (count($professionals) > 0): ?>
            <div class="pros-grid">
                <?php foreach ($professionals as $pro): ?>
                    <div class="pro-card">
                        <h4 class="pro-name"><?php echo htmlspecialchars($pro['full_name']); ?></h4>
                        <span class="chip"><?php echo htmlspecialchars($pro['expertise_area']); ?></span>
                        <div class="pro-meta">
                            <div><strong>Profession:</strong> <?php echo htmlspecialchars($pro['first_profession']); ?></div>
                            <div><strong>Experience:</strong> <?php echo (int)$pro['experience_years']; ?> years</div>
                            <div><strong>Rate:</strong> $<?php echo number_format($pro['hourly_rate'], 2); ?>/hour</div>
                            <div><strong>Availability:</strong> <?php echo htmlspecialchars($pro['availability']); ?></div>
                            <div><strong>Phone:</strong> <?php echo htmlspecialchars($pro['phone']); ?></div>
                            <div><strong>Email:</strong> <?php echo htmlspecialchars($pro['email']); ?></div>
                            <div><strong>Area:</strong> <?php echo htmlspecialchars($pro['area_of_operation']); ?></div>
                        </div>

                        <form class="request-form" method="POST" action="../controller/client_request_process.php">
                            <input type="hidden" name="professional_id" value="<?php echo (int)$pro['id']; ?>">
                            <label>Expertise</label>
                            <input type="text" name="service_type" value="<?php echo htmlspecialchars($pro['expertise_area']); ?>" readonly>
                            <label>Describe your request</label>
                            <textarea name="request_description" rows="3" placeholder="Write what you need from this professional" required></textarea>
                            <button type="submit" class="btn" style="margin-top:10px;">Contact Professional</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-error">No professionals found in your area for this expertise.</div>
        <?php endif; ?>

        <h3 id="my-requests" style="margin-top:28px;color:#333;">My Service Requests (<?php echo count($my_requests); ?>)</h3>
        <?php if (count($my_requests) > 0): ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Professional</th>
                            <th>Expertise</th>
                            <th>Contact</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($my_requests as $request): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($request['professional_name']); ?></td>
                                <td><?php echo htmlspecialchars($request['service_type']); ?></td>
                                <td><?php echo htmlspecialchars($request['professional_phone']); ?><br><?php echo htmlspecialchars($request['professional_email']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($request['request_date'])); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo strtolower(str_replace(' ', '', $request['status'])); ?>">
                                        <?php echo htmlspecialchars($request['status']); ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-error">You have not contacted any professional yet.</div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
