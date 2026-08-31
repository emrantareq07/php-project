<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'] ?? 'User';
$is_admin = ($username === 'sadmin' || $role === 'admin'); 


// Session timeout (30 minutes = 1800 seconds)
$timeout_duration = 1800;

if (isset($_SESSION['last_activity'])) {
    $elapsed_time = time() - $_SESSION['last_activity'];
    
    if ($elapsed_time > $timeout_duration) {
        // User inactive - set offline
        $user_id = $_SESSION['user_id'];
        $update_sql = "UPDATE users SET login_status = 0 WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $user_id);
        $update_stmt->execute();
        
        // Destroy session
        session_unset();
        session_destroy();
        
        header("Location: ../index.php?timeout=1");
        exit;
    }
}

// Update last activity time
$_SESSION['last_activity'] = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Super Admin | Man Power Management</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <style>
    body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    
    /* Sidebar Styling */
    .sidebar {
      height: 100vh;
      width: 250px;
      position: fixed;
      left: 0;
      top: 0;
      background: #2c3e50;
      color: white;
      padding-top: 20px;
    }
    .sidebar a {
      padding: 15px 25px;
      display: block;
      color: #bdc3c7;
      text-decoration: none;
      transition: 0.3s;
    }
    .sidebar a:hover { background: #34495e; color: #fff; border-left: 4px solid #3498db; }
    .sidebar .active { background: #34495e; color: #fff; border-left: 4px solid #e74c3c; }

    /* Main Content */
    .main-content { margin-left: 250px; padding: 30px; }
    
    /* Solid Color Cards */
    .card-box { border: none; border-radius: 10px; color: white; padding: 25px; margin-bottom: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .bg-blue { background: #3498db; }
    .bg-green { background: #2ecc71; }
    .bg-orange { background: #f39c12; }
    .bg-purple { background: #9b59b6; }
    
    .card-box i { font-size: 40px; opacity: 0.4; float: right; }
    .card-box h3 { font-size: 28px; font-weight: bold; }
    .card-box p { margin-bottom: 0; font-size: 16px; text-transform: uppercase; }

    @media (max-width: 768px) {
      .sidebar { width: 100%; height: auto; position: relative; }
      .main-content { margin-left: 0; }
    }
  </style>
</head>
<body>

<div class="sidebar">
    <div class="text-center mb-4">
        <h4><i class="fa fa-user-shield"></i> S-ADMIN</h4>
        <hr class="mx-3">
    </div>
    <a href="#" class="active"><i class="fa fa-home me-2"></i> Dashboard</a>
    <a href="manage_users.php"><i class="fa fa-users me-2"></i> Manage Users</a>
    <a href="#"><i class="fa fa-file-invoice me-2"></i> Reports</a>
    <a href="#"><i class="fa fa-cog me-2"></i> Settings</a>
    <a href="logout.php" class="text-danger mt-5"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
</div>

<div class="main-content">
  <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="fw-bold text-dark">Control Panel</h2>
      <span class="badge bg-dark p-2">Welcome, <?php echo htmlspecialchars($username); ?></span>
  </div>

  <div class="row">
    <div class="col-md-3">
      <div class="card-box bg-blue">
        <i class="fa fa-users"></i>
        <h3>1,250</h3>
        <p>Total Manpower</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card-box bg-green">
        <i class="fa fa-check-circle"></i>
        <h3>840</h3>
        <p>Active Staff</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card-box bg-orange">
        <i class="fa fa-user-plus"></i>
        <h3>12</h3>
        <p>New Requests</p>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card-box bg-purple">
        <i class="fa fa-shield-alt"></i>
        <h3>Admin</h3>
        <p>System Status</p>
      </div>
    </div>
  </div>

  <div class="mt-4 p-5 bg-white shadow-sm rounded-4">
    <h4 class="mb-3 border-bottom pb-2">Quick Actions</h4>
    <p>Logged in as: <strong><?php echo $role; ?></strong></p>
    
    <?php if($is_admin): ?>
        <a href="manage_users.php" class="btn btn-primary btn-lg shadow-sm">
            <i class="fa fa-user-cog me-2"></i> Open User Management
        </a>
    <?php endif; ?>
    
    <a href="logout.php" class="btn btn-outline-danger btn-lg shadow-sm ms-2">
        <i class="fa fa-power-off me-2"></i> Secure Logout
    </a>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Send heartbeat every 2 minutes to keep session alive
setInterval(function() {
    fetch('update_heartbeat.php')
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                window.location.href = 'index.php';
            }
        })
        .catch(error => console.log('Heartbeat error:', error));
}, 120000); // 2 minutes
</script>
</body>
</html>