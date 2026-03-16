<?php
session_name('training_certificate_gen_db');
session_start();

// Strong no-cache headers
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: private, no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php"); // Redirect to login if not logged in
    exit();
}

$user_name = $_SESSION['user_name'];
$user_role = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'];
$emp_id = $_SESSION['emp_id'];
$email_id = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body { background-color: #f8f9fa; }
    .card { border-radius: 12px; }
    ul { list-style-type: none; padding: 0; }
    ul li { margin: 8px 0; }
    ul li a {
        text-decoration: none;
        color: #0d6efd;
        font-weight: 500;
    }
    ul li a:hover {
        text-decoration: underline;
        color: #0a58ca;
    }
  </style>
</head>
<body>
<div class="container py-5">
    <div class="card shadow-lg p-4">
        <h3>Welcome, <?= htmlspecialchars($user_name) ?> 👋</h3>
        <p>Your role: <strong class="text-primary"><?= ucfirst($user_role) ?></strong> Emp ID: <?= ucfirst($user_id) ?> Email ID: <?= ucfirst($email_id) ?></p>
        
        <hr>

        <?php if ($user_role === 'sadmin'): ?>
            <!-- Admin Panel -->
            <h4 class="text-danger">Super Admin Panel</h4>
            <ul>
                <li><a href="manage_users.php" style="text-decoration: none;">👥 Manage Users</a></li>
                <li><a href="reports.php" style="text-decoration: none;">📊 View Reports</a></li>
                <li><a href="settings.php" style="text-decoration: none;">⚙️ Training Settings/Setup</a></li>

                <li><a href="certificate_by_batch.php" style="text-decoration: none;">🎓 Certificates</a></li>
                 <li><a href="attendence_by_batch.php" style="text-decoration: none;">📋 Create Attendence Sheet</a></li>
                 <li><a href="exam_set.php" style="text-decoration: none;">📝 SET Exam</a></li>

             <li><a href="result_by_batch.php" style="text-decoration: none;">📑  Batch Wise Result</a></li>

                <li>

                    <!-- <a href="download_db.php" style="text-decoration: none;">⚙️ Download Database</a> -->

                    <form  id="downloadForm" action="download_db.php" method="post">
                   <button class="btn btn-warning" type="submit" name="submit" ><i class="fa fa-download" style="font-size:15px;color:black"></i> Download Database</button>
                 </form>

                </li>
            </ul>

            <?php elseif ($user_role === 'admin'): ?>
            <!-- Admin Panel -->
            <h4 class="text-danger">Admin Panel</h4>
            <ul>
                <li><a href="manage_users.php" style="text-decoration: none;">👥 Manage Users</a></li>
                <li><a href="reports.php" style="text-decoration: none;">📊 View Reports</a></li>
                <li><a href="settings.php" style="text-decoration: none;">⚙️ Training Settings/Setup</a></li>
                <li><a href="certificate_by_batch.php" style="text-decoration: none;"><i class="fa fa-sticky-note-o"></i>  Certificates</a></li>
                <li>                  

                </li>
            </ul>

        <?php elseif ($user_role === 'user'): ?>
            <!-- User Panel -->
            <h4 class="text-success">User Dashboard</h4>
            <ul>
                <li><a href="my_profile.php" style="text-decoration: none;">🙍 My Profile</a></li>
                <li><a href="my_certificates.php?email=<?= urlencode($_SESSION['user_email']); ?>" style="text-decoration: none;">🎓 My Certificates</a></li>
                    <li><a href="my_exams.php?email=<?= urlencode($_SESSION['user_email']); ?>" style="text-decoration: none;">📋 MY Exam</a></li>
                    <li><a href="change_pwd.php" style="text-decoration: none;">🙍 Change Password</a></li>
                
            </ul>
        <?php else: ?>
            <p class="text-muted">No dashboard available for this role.</p>
        <?php endif; ?>

        <hr>
        <a href="logout.php" class="btn btn-outline-danger fw-bold"><i class="fa fa-sign-out"></i> Logout</a>
        <?php
        require_once "includes/footer.php"; 
        ?>
        
    </div>

</div>
</body>
</html>

<script>
// Disable Back & Forward
// history.pushState(null, null, location.href);
// window.onpopstate = function () {
//     history.go(1);
// };



// If page reloads, destroy session and redirect
// Prevent reload
if (performance.getEntriesByType("navigation")[0].type === "reload") {
    window.location.href = "reload_handler.php";
}


// Disable Right Click
document.addEventListener("contextmenu", function (e) {
    e.preventDefault();
});

window.addEventListener("pageshow", function (event) {
    if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
        window.location.href = "../index.php";
    }
});
</script>
