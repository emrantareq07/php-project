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

// SAVE multiple rows
if (isset($_POST['save_schedule'])) {
    $id = $_POST['schedule_id'];   // <<< FIX ADDED
    $marks = $_POST['marks'];
    $committee = $_POST['committe_name'];

    $viva_dates = $_POST['viva_date'];
    $times      = $_POST['time'];

    // Formatted Titles (Bengali)
    $titles = [];
    foreach($_POST['title'] as $t){
        $titles[] = $t . " পদে মৌখিক পরীক্ষায় অংশগ্রহনকারী প্রার্থীদের " . $committee . " কর্তৃক প্রদত্ত নম্বর শীট।";
    }

    // *** if UPDATE mode ***
    if ($id != "") {

        $vdate = $_POST['viva_date'][0];
        $vtime = $_POST['time'][0];
        $vtitle= $titles[0];

        $stmt = $conn->prepare("UPDATE exam_schedule_tbl SET date=?, time=?, marks=?, title=?, committe_name=? WHERE id=?");
        $stmt->bind_param("ssdssi", $vdate, $vtime, $marks, $vtitle, $committee, $id);
        $stmt->execute();

    } else {

        foreach ($viva_dates as $key => $vdate) {

            $vdate  = $viva_dates[$key];
            $vtime  = $times[$key];
            $vtitle = $titles[$key];

            if($vdate != ""){ // avoid empty
                $stmt = $conn->prepare("INSERT INTO exam_schedule_tbl (date, time, marks, title, committe_name)
                                        VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("ssdss", $vdate, $vtime, $marks, $vtitle, $committee);
                $stmt->execute();
            }
        }
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
  <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Fonts -->
      <link rel="preconnect" href="https://fonts.googleapis.com">
      <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
      <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Varela+Round&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
      <style>
      @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');

    </style>
</head>

<body class="bg-light">
<div class="container py-4">
  <h3 class="mb-4 text-center bg-primary text-white p-2 rounded">Exam Schedule Manager</h3>

  <!-- Form -->
  <form method="POST" class="row g-3 mb-4">
    <input type="hidden" name="schedule_id" id="schedule_id">

    <div class="col-md-2">
      <label>Viva Marks</label>
      <input type="number" step="0.01" name="marks" id="marks" class="form-control" placeholder="Enter viva marks" value="10" required>
    </div>

    <div class="col-md-4">
      <label>Committee Name</label>
      <select name="committe_name" id="committe_name" class="form-select" required>
        <option value="">Select Committee</option>
        <?php 
        //$result_committe = mysqli_query($conn, "SELECT DISTINCT committe_name FROM committee_tbl");
        $result_committe = mysqli_query($conn, "SELECT DISTINCT committe_name FROM candidates_tbl");
        while ($r = mysqli_fetch_assoc($result_committe)) {
            echo "<option value='{$r['committe_name']}'>{$r['committe_name']}</option>";
        }
        ?>
      </select>
    </div>
<div class="col-md-2  text-end mt-3">    
  <button type="button" id="addRow" class="btn btn-sm btn-success">+ Add Row</button>
</div>

    <table class="table table-bordered" id="examinerTable">
      <thead class="table-light">
        <tr>
          <th>Viva Date</th>
          <th>Time</th>
          <th>Designation</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input type="date" name="viva_date[]" class="form-control" required></td>
          <td><input type="time" name="time[]" class="form-control"></td>
          <td>
            <!-- <select name="title[]" id="title" class="form-select" required>
            <option value="">Select Designation</option>
                </select> -->

                <select name="title[]" id="title" class="form-select designation-select" required>
                  <option value="">Select Designation</option>
                </select>
                  <script>
                    document.getElementById('committe_name').addEventListener('change', function () {
                        var committee = this.value;
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'get_designations.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onload = function () {
                            if (this.status == 200) {
                                document.getElementById('title').innerHTML = this.responseText;

                                // Re-run the disabling logic after new options are loaded
                                updateDesignationOptions();
                            }
                        };
                        xhr.send('committe_name=' + encodeURIComponent(committee));
                    });

                    function updateDesignationOptions() {
                        const selects = document.querySelectorAll('.designation-select');
                        const selectedValues = Array.from(selects)
                            .map(select => select.value)
                            .filter(val => val !== '');

                        selects.forEach(select => {
                            Array.from(select.options).forEach(option => {
                                if (option.value === '') return; // Skip placeholder
                                option.disabled = selectedValues.includes(option.value) && select.value !== option.value;
                            });
                        });
                    }

                    // Re-run filter whenever a designation is selected
                    document.addEventListener('change', function (e) {
                        if (e.target.classList.contains('designation-select')) {
                            updateDesignationOptions();
                        }
                    });
                    </script>
          <!-- <input type="text" name="title[]" class="form-control" autocomplete="on"> -->
      </td>
          <td><button type="button" class="btn btn-danger btn-sm remove-row-btn">X</button></td>
        </tr>
      </tbody>
    </table>

    <div class="col-md-12 text-end">
      <button type="button" class="btn btn-secondary" id="cancelEdit">Cancel</button>
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
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $schedules->fetch_assoc()): ?>
      <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['date'] ?></td>
        <td><?= $row['time'] ?></td>
        <td><?= $row['marks'] ?></td>
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
          <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this schedule?')">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<script>
$(document).ready(function(){

  // Edit button functionality
  $('.editBtn').click(function(){

    $('#schedule_id').val($(this).data('id'));
    $('#marks').val($(this).data('marks'));
    $('select[name="committe_name"]').val($(this).data('committee'));

    // clear table rows first
    $('#examinerTable tbody').html('');

    // add single row for edit
    let row = `
    <tr>
      <td><input type="date" name="viva_date[]" class="form-control" value="${$(this).data('date')}" required></td>
      <td><input type="time" name="time[]" class="form-control" value="${$(this).data('time')}"></td>
      <td><input type="text" name="title[]" class="form-control" value="${$(this).data('title')}"></td>
      <td></td>
    </tr>`;
    $('#examinerTable tbody').append(row);

    $('html, body').animate({ scrollTop: 0 }, 'fast');
  });

  // Add new row
  $('#addRow').click(function() {
    const row = $('#examinerTable tbody tr:first').clone();
    row.find('input').val('');
    $('#examinerTable tbody').append(row);
  });

  // Remove row
  $(document).on('click', '.remove-row-btn', function() {
    $(this).closest('tr').remove();
  });

  // Cancel edit
  $('#cancelEdit').click(function(){
    location.href = "exam_schedule.php";
  });

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
