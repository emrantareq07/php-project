<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');

if (!isset($_GET['committee'])) {
    die("Invalid request!");
}
$committee = mysqli_real_escape_string($conn, $_GET['committee']);

// ------------------ Fetch exam info ------------------
$infoQuery = "SELECT * FROM exam_schedule_tbl WHERE committe_name = '$committee' LIMIT 1";
$infoResult = mysqli_query($conn, $infoQuery);
$info = mysqli_fetch_assoc($infoResult);

$title = $info['title'] ?? 'No Title';
$date = isset($info['date']) && $info['date'] ? date('d-m-Y', strtotime($info['date'])) : '-';
$time = isset($info['time']) && $info['time'] ? date('h:i A', strtotime($info['time'])) : '-';

// ------------------ Fetch candidates ------------------
$candidateQuery = "SELECT * FROM candidates_tbl WHERE committe_name = '$committee'";
$candidateResult = mysqli_query($conn, $candidateQuery);

// ------------------ Fetch committee members ------------------
$membersQuery = "SELECT * FROM committee_tbl WHERE committe_name = '$committee'";
$membersResult = mysqli_query($conn, $membersQuery);
$committeeMembers = [];
while ($member = mysqli_fetch_assoc($membersResult)) {
    $committeeMembers[] = $member;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Candidate List - <?= htmlspecialchars($committee) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Varela+Round&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');
@media print { .no-print { display:none } }
body { background:#fff; }
.header { text-align:center;margin-bottom:20px; }
.header h4 { color:#007bff; }
.header p { margin:0; }
table th { background:#e9ecef; }
.signature-box { border-top:1px solid #000;padding-top:10px;margin-top:20px; }
.committee-header { font-size:12px;line-height:1.2;padding:5px; }
/* Font Definitions */
@font-face {
  font-family: 'Nikosh';
  src: url('fonts/Nikosh.ttf') format('truetype'),
       url('fonts/Nikosh.woff') format('woff'),
       url('fonts/Nikosh.woff2') format('woff2');
  font-weight: normal;
  font-style: normal;
  font-display: swap;
}

/* Base Typography */
* {
  font-family: 'Nikosh', 'SolaimanLipi', 'Open Sans', sans-serif;
}

</style>
</head>
<body>
<div class="container mt-4">
    <div class="header">
        <button class="btn btn-primary no-print" onclick="window.print()">🖨️ Print</button>
        <h2>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন(বিসিআইসি)</h2>
        <h3 class="mb-0">বিসিআইসি ভবন, ৩০-৩১, দিলকুশা বা/এ, ঢাকা।</h3>
        
        <h4><?= htmlspecialchars($title) ?></h4>
        <p><b class="float-start">Date:  <?= $date ?></b> |
          <b>Committee:</b> <?= htmlspecialchars($committee) ?> |
          
          <b class="float-end">Time: <?= $time ?></b> 
        </p>
        
        <hr>
    </div>

    <table class="table table-bordered table-sm align-middle text-center">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Roll No</th>
                <th>Name, Father's Name, Mother's Name</th>
                
                <th>District</th>
                <th>DOB</th>
                <!-- <th>Designation</th> -->
                <th>Written Marks</th>
                <th>Image</th>
                <?php foreach($committeeMembers as $member): ?>
                <th class="committee-header">
                    <strong><?= htmlspecialchars($member['name']) ?></strong><br>
                    <small>(<?= htmlspecialchars($member['designation']) ?>)</small><br>
                    <small>(<?= htmlspecialchars($member['division']) ?>)</small><br>
                    <small>(<?= htmlspecialchars($member['type']) ?>)</small>
                </th>
                <?php endforeach; ?>
                <th>Total Viva</th>
                <th>Average Viva</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
<?php 
$i = 1;
while ($row = mysqli_fetch_assoc($candidateResult)) {
    $candidate_id = $row['id'];

    $total_marks = 0;
    $viva_marks_arr = [];

    // Loop through each committee member to fetch individual viva marks
    foreach($committeeMembers as $member) {
        $examinerUsername = mysqli_real_escape_string($conn, $member['name']);
        $markQuery = "SELECT viva_marks FROM viva_marks_tbl WHERE candidate_id=$candidate_id AND committe_name='$committee' AND examiner_username='$examinerUsername' LIMIT 1";
        $markResult = mysqli_query($conn, $markQuery);
        $markRow = mysqli_fetch_assoc($markResult);
        $mark = $markRow['viva_marks'] ?? 'Pending';
        $viva_marks_arr[] = is_numeric($mark) ? $mark : 0; // Pending counted as 0
    }

    $total_marks = array_sum($viva_marks_arr);
    $avg_marks = count($committeeMembers) ? round($total_marks / count($committeeMembers), 2) : 0;
?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($row['roll_no']) ?></td>
    <td>
  <?= htmlspecialchars($row['name']) ?><br>
  <?= htmlspecialchars($row['fathers_name']) ?><br>
  <?= htmlspecialchars($row['mothers_name']) ?>
</td>
    <td><?= htmlspecialchars($row['district']) ?></td>
    <td><?= htmlspecialchars($row['dob']) ?></td>
    <!-- <td><?= htmlspecialchars($row['designation']) ?></td> -->
    <td><?= htmlspecialchars($row['written_marks']) ?></td>
    <td>
        <?php if (!empty($row['image'])): ?>
            <img src="<?= htmlspecialchars($row['image']) ?>" style="height:40px;">
        <?php else: ?>
            <img src="default.png" style="height:40px;">
        <?php endif; ?>
    </td>

    <?php foreach($viva_marks_arr as $vivaMark): ?>
        <td><?= $vivaMark !== 0 ? htmlspecialchars($vivaMark) : 'Pending' ?></td>
    <?php endforeach; ?>

    <td><?= htmlspecialchars($total_marks) ?></td>
    <td><?= htmlspecialchars($avg_marks) ?></td>
    <td><?= htmlspecialchars($row['remarks']) ?></td>
</tr>
<?php } ?>
        </tbody>
    </table>

    <div class="row text-center mt-5">
        <?php foreach($committeeMembers as $sign): ?>
        <div class="col-md-4 mb-4">
            <div class="signature-box">
                <strong><?= htmlspecialchars($sign['name']) ?></strong><br>
                <small>(<?= htmlspecialchars($sign['designation']) ?>)</small><br>
                <small>(<?= htmlspecialchars($sign['division']) ?>)</small><br>
                <small>(<?= htmlspecialchars($sign['type']) ?>)</small>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
