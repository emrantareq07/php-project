<?php
session_name('bcic_e-library');
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {
    header('location:../index.php');
    exit;
}
// Reset session login value if set
if (isset($_SESSION['login']) && $_SESSION['login'] != '') {
    $_SESSION['login'] = '';
}

// Fetch log data
$sql = "SELECT * FROM log_table ORDER BY login_date_time DESC";
$query = $dbh->prepare($sql);
$query->execute();
$logs = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Log Table</title>
    <!-- Include Bootstrap CSS for styling -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">User Login/Logout Log</h2>
    <button class="btn btn-primary no-print mb-3" onclick="window.print()">🖨️ Print Log</button>
    <a href="sadmin_dashboard.php" class="btn btn-success btn-md mb-3"><i class="fa fa-arrow-left" style="font-size:16px"></i> Back</a>
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>IP Address</th>
                <th>Login Time</th>
                <th>Logout Time</th>
                <th>Code</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($logs): ?>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= htmlspecialchars($log->id) ?></td>
                        <td><?= htmlspecialchars($log->username) ?></td>
                        <td><?= htmlspecialchars($log->user_type) ?></td>
                        <td><?= htmlspecialchars($log->Ip) ?></td>
                        <td><?= htmlspecialchars($log->login_date_time) ?></td>
                        <td><?= htmlspecialchars($log->logout_date_time) ?></td>
                        <td><?= htmlspecialchars($log->code) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7" class="text-center">No log entries found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
