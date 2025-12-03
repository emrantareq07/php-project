<?php
// session_name('viva_exam_db');
// session_start();
// include('../db/db.php');

// // Redirect if not logged in
// if (!isset($_SESSION['username'])) {
//     header("Location: ../index.php");
//     exit;
// }
// date_default_timezone_set("Asia/Dhaka");

// // Fetch distinct committee names that exist in all 3 tables
// $query = "
//     SELECT DISTINCT e.committe_name, e.date, e.time 
//     FROM exam_schedule_tbl e
//     INNER JOIN committee_tbl c ON e.committe_name = c.committe_name
//     INNER JOIN candidates_tbl ca ON e.committe_name = ca.committe_name
//     ORDER BY e.date DESC, e.time ASC
// ";

// $result = mysqli_query($conn, $query);
?>


<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

date_default_timezone_set("Asia/Dhaka");

// ✅ Combine all committees from all tables (unique only)
// $query = "
//     SELECT committe_name, MAX(date) AS date, MAX(time) AS time 
//     FROM (
//         SELECT committe_name, date, time FROM exam_schedule_tbl
//         UNION ALL
//         SELECT committe_name, NULL AS date, NULL AS time FROM committee_tbl
//         UNION ALL
//         SELECT committe_name, NULL AS date, NULL AS time FROM candidates_tbl
//     ) AS all_committees
//     WHERE committe_name IS NOT NULL AND committe_name != ''
//     GROUP BY committe_name
//     ORDER BY date DESC, committe_name ASC
// ";

// $result = mysqli_query($conn, $query);




// $query = "
//     SELECT DISTINCT
//         c.committe_name,
//         c.designation,
//         e.date,
//         e.time
//     FROM candidates_tbl AS c
//     LEFT JOIN exam_schedule_tbl AS e
//            ON c.committe_name = e.committe_name
//     ORDER BY c.committe_name, c.designation
// ";

$query = "SELECT DISTINCT
    B.committe_name,
    A.designation,
    B.date,
    B.time
FROM 
    exam_schedule_tbl B
LEFT JOIN 
    candidates_tbl A ON B.committe_name = A.committe_name
WHERE 
    A.designation IS NOT NULL
ORDER BY 
    B.committe_name, A.designation";

$result = mysqli_query($conn, $query);



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Committee Exam Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f8f9fa; }
        h3 { background: linear-gradient(45deg, #007bff, #6610f2); color: #fff; padding: 10px; border-radius: 6px; }
        .table-hover tbody tr:hover { background-color: #f1f1f1; }
        .view-link { text-decoration: none; }
        .view-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container mt-4">
    <h3 class="text-center mb-4">Committee Exam Schedule</h3>
    <a href="admin_dashboard.php" class="btn btn-primary">Back</a>
    <div class="card shadow border-0">
        <div class="card-body">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-primary text-center">
                    <tr>
                        <th>#</th>
                        <th>Committee Name</th>
                        <th>Designation</th> 
                        <th>Exam Date</th>
                        <th>Exam Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                if (mysqli_num_rows($result) > 0) { 
                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class="text-center">
                            <td><?= $i++ ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($row['committe_name']) ?></td>
                            <td class="fw-bold"><?= htmlspecialchars($row['designation']) ?></td> 
                            <td><?= date('d-m-Y', strtotime($row['date'])) ?></td>
                            <td><?= date('h:i A', strtotime($row['time'])) ?></td>
                            <td>
                                <a href="print_candidates.php?committee=<?= urlencode($row['committe_name']) ?>" 
                                   target="_blank" 
                                   class="btn btn-sm btn-success view-link">
                                   <i class="bi bi-printer"></i> View & Print Candidates
                                </a>
                            </td>
                        </tr>
                <?php } 
                } else { ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">No committee schedules found.</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bootstrap Icons & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
