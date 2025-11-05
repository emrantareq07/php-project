<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

date_default_timezone_set("Asia/Dhaka");

// Fetch unique committees
$query = "
    SELECT committe_name, MAX(date) AS date, MAX(time) AS time 
    FROM (
        SELECT committe_name, date, time FROM exam_schedule_tbl
        UNION ALL
        SELECT committe_name, NULL AS date, NULL AS time FROM committee_tbl
        UNION ALL
        SELECT committe_name, NULL AS date, NULL AS time FROM candidates_tbl
    ) AS all_committees
    WHERE committe_name IS NOT NULL AND committe_name != ''
    GROUP BY committe_name
    ORDER BY date DESC, committe_name ASC
";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Result Sheet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        h3 { background: linear-gradient(45deg, #007bff, #6610f2); color: #fff; padding: 10px; border-radius: 6px; }
        .table-hover tbody tr:hover { background-color: #f1f1f1; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h3 class="text-center mb-4">Result Sheet</h3>
    <a href="admin_dashboard.php" class="btn btn-primary mb-3">← Back</a>
    <div class="card shadow border-0">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Committee Name</th>
                        <th>Exam Date</th>
                        <th>Exam Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (mysqli_num_rows($result) > 0) { 
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) { 
                            $committee = htmlspecialchars($row['committe_name']);
                            $date = $row['date'] ? date('d-m-Y', strtotime($row['date'])) : '-';
                            $time = $row['time'] ? date('h:i A', strtotime($row['time'])) : '-';
                    ?>
                        <tr class="text-center">
                            <td><?= $i++ ?></td>
                            <td class="fw-bold"><?= $committee ?></td>
                            <td><?= $date ?></td>
                            <td><?= $time ?></td>
                            <td>
                                <a href="print_result.php?committee=<?= urlencode($committee) ?>" 
                                   target="_blank" 
                                   class="btn btn-sm btn-success">
                                   <i class="bi bi-printer"></i> View & Print Result
                                </a>
                            </td>
                        </tr>
                    <?php } } else { ?>
                        <tr><td colspan="5" class="text-center text-muted">No committees found.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
