<?php
session_name('viva_exam_db');
session_start();
include('../db/db.php');
date_default_timezone_set('Asia/Dhaka');
// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$username = $_SESSION['username']; // Examiner username
$today_date = date("Y-m-d");

$current_time = date("g:i A");
// CREATE or UPDATE
if (isset($_POST['save_schedule'])) {
    $id = $_POST['schedule_id'] ?? '';
    $date = $_POST['date'];
    $time = $_POST['time'];
    $marks = $_POST['marks'];
    $title = $_POST['title'];
    $committee = $_POST['committe_name'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE exam_schedule_tbl SET date=?, time=?, marks=?, title=?, committe_name=? WHERE id=?");
        $stmt->bind_param("ssdssi", $date, $time, $marks, $title, $committee, $id);
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("INSERT INTO exam_schedule_tbl (date, time, marks, title, committe_name) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdss", $date, $time, $marks, $title, $committee);
        $stmt->execute();
    }
    header("Location: exam_schedule.php");
    exit;
}

// DELETE
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM exam_schedule_tbl WHERE id=$id");
    header("Location: exam_schedule.php");
    exit;
}

// READ
$schedules = $conn->query("SELECT * FROM exam_schedule_tbl ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Exam Schedule Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>
<body class="bg-light">
<div class="container py-4">
  <h3 class="mb-4 text-center bg-primary text-white p-2 rounded">Exam Schedule Manager</h3>

  <!-- Add/Edit Form -->
  <form method="POST" class="row g-3 mb-4">
    <input type="hidden" name="schedule_id" id="schedule_id">
    <div class="col-md-3">
      <label>Date</label>
      <input type="date" name="date" id="date" class="form-control" value="<?php echo date("Y-m-d")?>" required>
    </div>
    <div class="col-md-3">
      <label>Time</label>
      <input type="time" name="time" id="time" class="form-control" required value="<?php echo $current_time; ?>">
    </div>
    <div class="col-md-2">
      <label>Marks</label>
      <input type="number" step="0.01" name="marks" id="marks" class="form-control" required>
    </div>
    <div class="col-md-4">
      <label>Committee Name</label>
      <!-- <input type="text" name="committe_name" id="committe_name" class="form-control" required> -->

      <select name="committe_name" class="form-select">
        <option value="">Select Committee</option>
        <?php 
        $sql_com = "SELECT DISTINCT committe_name FROM committee_tbl";
        $result_committe = mysqli_query($conn, $sql_com);
        if ($result_committe && mysqli_num_rows($result_committe) > 0) {
            while ($row_committe = mysqli_fetch_assoc($result_committe)) {
                $committe_name = htmlspecialchars($row_committe['committe_name']);
                echo "<option value=\"$committe_name\">$committe_name</option>";
            }
        } else {
            echo "<option disabled>No committees found</option>";
        }
        ?>
    </select>
    </div>
    <div class="col-md-12">
      <label>Title</label>
      <textarea name="title" id="title" class="form-control" required></textarea>
    </div>
    <div class="col-md-12 text-end">
      <button type="submit" name="save_schedule" class="btn btn-success">Save Schedule</button>
      <a href="admin_dashboard.php" class="btn btn-primary">Back</a>
    </div>
  </form>

  <!-- Schedule Table -->
  <table class="table table-bordered table-striped table-hover">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Time</th>
        <th>Marks</th>
        <th>Committee</th>
        <th>Title</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $schedules->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['date'] ?></td>
        <td><?= $row['time'] ?></td>
        <td><?= fmod($row['marks'], 1) == 0 ? number_format($row['marks'], 0) : number_format($row['marks'], 2) ?></td>
        <td><?= htmlspecialchars($row['committe_name']) ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= $row['created_at'] ?></td>
        <td>
          <button class="btn btn-sm btn-primary editBtn"
            data-id="<?= $row['id'] ?>"
            data-date="<?= $row['date'] ?>"
            data-time="<?= $row['time'] ?>"
            data-marks="<?= $row['marks'] ?>"
            data-title="<?= htmlspecialchars($row['title']) ?>"
            data-committee="<?= htmlspecialchars($row['committe_name']) ?>"
          >Edit</button>
          <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this schedule?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script>
$(document).ready(function(){
  $('.editBtn').click(function(){
    $('#schedule_id').val($(this).data('id'));
    $('#date').val($(this).data('date'));
    $('#time').val($(this).data('time'));
    const marks = parseFloat($(this).data('marks'));
    $('#marks').val(Number.isInteger(marks) ? marks : marks.toFixed(2));
    $('#title').val($(this).data('title'));
    $('#committe_name').val($(this).data('committee'));
    $('html, body').animate({ scrollTop: 0 }, 'fast');
  });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>