<?php
session_name('training_certificate_gen_db');
session_start();
require_once "db.php";
require_once "flash.php";

$user_email = $_SESSION['user_email'] ?? '';



if (empty($user_email)) {
    header("Location: login.php");
    exit;
}

date_default_timezone_set('Asia/Dhaka');

$current_datetime = date('Y-m-d H:i:s');

// Fetch user exams and batch info
$stmt = $conn->prepare("
    SELECT 
        u.id,
        u.batch,
        u.question_all,
        u.answer_all,  
        a.training_title,
        a.exam_date,
        a.start_time,
        a.end_time,
        a.active_exam
    FROM users_tbl u
    INNER JOIN authority_tbl a ON u.batch = a.batch
    WHERE u.email_id = ?
");

$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<!-- <head>
<title>My All Exams</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head> -->
<head>
<title>My All Exams</title>
<meta http-equiv="refresh" content="2">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Font Awesome -->
  <link 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" 
    rel="stylesheet">

</head>
<body class="bg-light">

<div class="container mt-3 bg-white p-4 shadow rounded">

  <!-- Section Title -->
  <h3 class="mb-0 text-uppercase text-muted">
    My All Exam Details
    <!-- Action Buttons -->
  <!-- <div class="d-flex justify-content-end"> -->
    <a href="dashboard.php" class="btn btn-secondary me-2 float-end">
      <i class="fa fa-arrow-left"></i> Back
    </a>
    <a href="logout.php" class="btn btn-outline-danger me-2  float-end">
      <i class="fa fa-sign-out"></i> Logout
    </a>
  <!-- </div> -->
  </h3>

  <!-- Inline Instructions -->
  <p class="small text-muted">
     
    <strong class="btn btn-primary"><i class="fa fa-info-circle me-1"></i>General Instructions:</strong>
    [<span class="ms-2">
      <i class="fa fa-window-minimize text-danger me-1"></i> Don’t minimize the browser
      <i class="fa fa-plus-square text-warning ms-3 me-1"></i> Don’t open a new tab
      <i class="fa fa-refresh text-info ms-3 me-1"></i> Don’t reload the browser
      <i class="fa fa-copy text-secondary ms-3 me-1"></i> Don’t try to copy questions
    </span>]
  </p>

<?php showFlash(); ?>

<table class="table table-bordered">
<thead class="table-dark">
<tr>
<th>Training Title</th>
<th>Exam Date</th>
<th>Start</th>
<th>End</th>
<th>Batch No</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row = $result->fetch_assoc()): 

// Clean time (remove microseconds if exists)
$clean_start_time = explode('.', $row['start_time'])[0];
$clean_end_time   = explode('.', $row['end_time'])[0];

// Format display time
$start = ($clean_start_time == '00:00:00') ? '' : date("h:i A", strtotime($clean_start_time));
$end   = ($clean_end_time == '00:00:00') ? '' : date("h:i A", strtotime($clean_end_time));

// Check if exam already taken
$exam_taken = !empty(trim($row['question_all'])) && !empty(trim($row['answer_all']));

// Create full datetime for exam
$exam_start_datetime = $row['exam_date'] . ' ' . $clean_start_time;
$exam_end_datetime   = $row['exam_date'] . ' ' . $clean_end_time;

// Determine if exam can be given
$can_give = false;

if (
    $row['active_exam'] === 'active' &&
    !$exam_taken &&
    $current_datetime >= $exam_start_datetime &&
    $current_datetime <= $exam_end_datetime
) {
    $can_give = true;
}

?>

<tr>
<td><?= htmlspecialchars($row['training_title']) ?></td>
<td><?= htmlspecialchars($row['exam_date']) ?></td>
<td><?= $start ?></td>
<td><?= $end ?></td>
<td><?= htmlspecialchars($row['batch']) ?></td>
<td>

<?php if ($exam_taken): ?>

    <a href="result.php?id=<?= $row['id'] ?>&batch=<?= urlencode($row['batch']) ?>" 
       class="btn btn-sm btn-success">
       View Result
    </a>

<?php elseif ($can_give): ?>

    <a href="give_exam.php?id=<?= $row['id'] ?>&batch=<?= urlencode($row['batch']) ?>" 
       class="btn btn-sm btn-primary">
       Start Exam
    </a>

<?php else: ?>

    <button class="btn btn-sm btn-secondary" disabled>
        Not Available
    </button>

<?php endif; ?>

</td>
</tr>

<?php endwhile; ?>

</tbody>
</table>

</div>
 <!-- Bootstrap JS (with Popper) -->
  <script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js">
  </script>
<script>

// Disable Back & Forward
// history.pushState(null, null, location.href);
// window.onpopstate = function () {
//     history.go(1);
// };

// If page reloads, destroy session and redirect
// Prevent reload
history.pushState(null, null, location.href);
window.onpopstate = function () {
   window.location.href = "reload_handler.php";
};


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
</body>
</html>

