<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

date_default_timezone_set("Asia/Dhaka");

// Get committee name from URL or fallback to first
$committe_name = isset($_GET['committe_name']) ? trim($_GET['committe_name']) : '';
if (empty($committe_name)) {
    $query_first = mysqli_query($conn, "SELECT committe_name FROM committee_tbl LIMIT 1");
    if ($query_first && mysqli_num_rows($query_first) > 0) {
        $first = mysqli_fetch_assoc($query_first);
        $committe_name = $first['committe_name'];
    } else {
        echo "<div class='alert alert-danger text-center mt-4'>No committee found in database.</div>";
        exit;
    }
}

// Fetch exam schedule info
$schedule_query = "SELECT * FROM exam_schedule_tbl WHERE committe_name='$committe_name' LIMIT 1";
$schedule_result = mysqli_query($conn, $schedule_query);
$schedule = mysqli_fetch_assoc($schedule_result);

// Fetch all committee members
$committee_query = "SELECT * FROM committee_tbl WHERE committe_name='$committe_name'";
$committee_result = mysqli_query($conn, $committee_query);

// Fetch all candidates for this committee
$candidate_query = "SELECT * FROM candidates_tbl WHERE committe_name='$committe_name'";
$candidate_result = mysqli_query($conn, $candidate_query);

// If no candidates found
if (!$candidate_result || mysqli_num_rows($candidate_result) == 0) {
    echo "<div class='alert alert-warning text-center mt-4'>No candidates found for committee: <strong>$committe_name</strong></div>";
    exit;
}

// Calculate totals
$totalCandidates = mysqli_num_rows($candidate_result);
$totalMarks = 0;
$avgMarks = 0;
$candidates = [];
while ($row = mysqli_fetch_assoc($candidate_result)) {
    $totalMarks += $row['viva_marks'];
    $candidates[] = $row;
}
if ($totalCandidates > 0) {
    $avgMarks = $totalMarks / $totalCandidates;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Print Candidates - <?= htmlspecialchars($committe_name) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { -webkit-print-color-adjust: exact !important; }
        }
        .signature-box {
            border-top: 1px solid #000;
            text-align: center;
            padding-top: 5px;
            font-weight: bold;
        }
        .table thead th {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h4 class="text-primary fw-bold">Committee: <?= htmlspecialchars($committe_name) ?></h4>
        <button class="btn btn-success" onclick="window.print()">🖨️ Print</button>
    </div>

    <!-- Exam Info -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold text-center text-primary">Exam Schedule Details</h5>
            <table class="table table-bordered text-center mb-0">
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Remarks</th>
                </tr>
                <tr>
                    <td><?= $schedule['title'] ?? 'N/A' ?></td>
                    <td><?= $schedule['date'] ?? 'N/A' ?></td>
                    <td><?= $schedule['time'] ?? 'N/A' ?></td>
                    <td><?= $schedule['remarks'] ?? 'N/A' ?></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Candidates Table -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold text-center text-success mb-3">Candidates List</h5>
            <table class="table table-bordered table-striped align-middle text-center">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th>Father's Name</th>
                        <th>Mother's Name</th>
                        <th>District</th>
                        <th>DOB</th>
                        <th>Written Marks</th>
                        <th>Viva Marks</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=1; foreach ($candidates as $row): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $row['roll_no'] ?></td>
                        <td><?= $row['name'] ?></td>
                        <td><?= $row['fathers_name'] ?></td>
                        <td><?= $row['mothers_name'] ?></td>
                        <td><?= $row['district'] ?></td>
                        <td><?= $row['dob'] ?></td>
                        <td><?= $row['written_marks'] ?></td>
                        <td><?= $row['viva_marks'] ?></td>
                        <td><?= $row['status'] ?></td>
                        <td><?= $row['remarks'] ?></td>
                        <td>
                            <?php if (!empty($row['image'])): ?>
                                <img src="<?= $row['image'] ?>" alt="Image" style="height:50px;width:50px;object-fit:cover;">
                            <?php else: ?>
                                <img src="default.png" alt="No Image" style="height:50px;width:50px;object-fit:cover;">
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="mt-3">
                <p><strong>Total Candidates:</strong> <?= $totalCandidates ?></p>
                <p><strong>Total Viva Marks:</strong> <?= number_format($totalMarks, 2) ?></p>
                <p><strong>Average Viva Marks:</strong> <?= number_format($avgMarks, 2) ?></p>
            </div>
        </div>
    </div>

    <!-- Committee Members -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5 class="fw-bold text-center text-danger mb-3">Committee Members</h5>
            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Office</th>
                        <th>Division</th>
                        <th>Type</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($member = mysqli_fetch_assoc($committee_result)) { ?>
                        <tr>
                            <td><?= $member['name'] ?></td>
                            <td><?= $member['designation'] ?></td>
                            <td><?= $member['office'] ?></td>
                            <td><?= $member['division'] ?></td>
                            <td><?= $member['type'] ?></td>
                            <td><?= $member['remarks'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Signature Section -->
            <div class="row text-center mt-5">
                <?php
                mysqli_data_seek($committee_result, 0); // rewind to reuse data
                $members_for_sign = mysqli_query($conn, "SELECT name,type FROM committee_tbl WHERE committe_name='$committe_name'");
                while ($sign = mysqli_fetch_assoc($members_for_sign)) {
                    echo "<div class='col-md-4 mb-4'>
                            <div class='signature-box'>{$sign['name']}<br><small>({$sign['type']})</small></div>
                          </div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
