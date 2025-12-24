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
    $id        = trim($_POST['schedule_id'] ?? '');
    $marks     = $_POST['marks'] ?? 0;
    $committee = $_POST['committe_name'] ?? '';

    $viva_dates   = $_POST['viva_date'] ?? [];
    $times        = $_POST['time'] ?? [];
    $raw_titles   = $_POST['title'] ?? []; // raw designations user selected/typed

    // Build formatted titles
    $titles = [];
    foreach ($raw_titles as $designation) {
        $designation = trim($designation);
        if ($designation === '') {
            $titles[] = $designation;
            continue;
        }
        $titles[] = $designation . " পদে মৌখিক পরীক্ষায় অংশগ্রহনকারী প্রার্থীদের " . $committee . " কর্তৃক প্রদত্ত নম্বর শীট।";
    }

    // Update single row
    if ($id !== "") {
        $vdate  = $viva_dates[0] ?? null;
        $vtime  = $times[0] ?? null;
        $vtitle = $titles[0] ?? '';
        $vdesignation = $raw_titles[0] ?? ''; // <-- add designation

        $stmt = $conn->prepare("UPDATE exam_schedule_tbl 
            SET date=?, time=?, marks=?, title=?, designation=?, committe_name=? 
            WHERE id=?");
        $stmt->bind_param("ssdsssi", $vdate, $vtime, $marks, $vtitle, $vdesignation, $committee, $id);
        $stmt->execute();
        $stmt->close();

    } else {
        // Insert multiple rows
        foreach ($viva_dates as $key => $vdate) {
            $vdate       = $viva_dates[$key];
            $vtime       = $times[$key] ?? null;
            $vtitle      = $titles[$key] ?? '';
            $vdesignation= $raw_titles[$key] ?? ''; // <-- add designation

            if (trim($vdate) === '') continue;

            $stmt = $conn->prepare("INSERT INTO exam_schedule_tbl 
                (date, time, marks, title, designation, committe_name) 
                VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdsss", $vdate, $vtime, $marks, $vtitle, $vdesignation, $committee);
            $stmt->execute();
            $stmt->close();
        }
    }

    header("Location: exam_schedule.php");
    exit;
}

// DELETE
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
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

  <!-- CSS / Fonts -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body{ font-family: 'Noto Sans Bengali', sans-serif; }
    .small-note { font-size:0.9rem; color:#666; }
  </style>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="bg-light">
<div class="container py-4">
  <h3 class="mb-4 text-center bg-primary text-white p-2 rounded">Exam Schedule Manager</h3>

  <!-- Form -->
  <form method="POST" class="row g-3 mb-4" id="scheduleForm">
    <input type="hidden" name="schedule_id" id="schedule_id" value="">

    <div class="col-md-2">
      <label>Viva Marks</label>
      <input type="number" step="0.01" name="marks" id="marks" class="form-control" placeholder="Enter viva marks" value="20" required>
    </div>

    <div class="col-md-4">
      <label>Committee Name</label>
      <select name="committe_name" id="committe_name" class="form-select" required>
        <option value="">Select Committee</option>
        <?php
        // load distinct committees (from committee_tbl or candidates_tbl - choose your source)
        $result_committe = mysqli_query($conn, "SELECT DISTINCT committe_name FROM committee_tbl");
        if (!$result_committe) {
            $result_committe = mysqli_query($conn, "SELECT DISTINCT committe_name FROM candidates_tbl");
        }
        while ($r = mysqli_fetch_assoc($result_committe)) {
            $val = htmlspecialchars($r['committe_name']);
            echo "<option value='{$val}'>{$val}</option>";
        }
        ?>
      </select>
      <div class="small-note mt-1">Change committee to load relevant designations.</div>
    </div>

      <div class="col-md-4">
      <label>Candidates List</label>
      <select name="committe_name" id="committe_name" class="form-select" required>
        <option value="">Select Committee</option>
        <?php
        // load distinct committees (from committee_tbl or candidates_tbl - choose your source)
        $result_committe = mysqli_query($conn, "SELECT DISTINCT committe_name FROM committee_tbl");
        if (!$result_committe) {
            $result_committe = mysqli_query($conn, "SELECT DISTINCT committe_name FROM candidates_tbl");
        }
        while ($r = mysqli_fetch_assoc($result_committe)) {
            $val = htmlspecialchars($r['committe_name']);
            echo "<option value='{$val}'>{$val}</option>";
        }
        ?>
      </select>
      <div class="small-note mt-1">Change committee to load relevant designations.</div>
    </div>

    <div class="col-md-2 text-end mt-3">
      <button type="button" id="addRow" class="btn btn-sm btn-success">+ Add Row</button>
    </div>

    <div class="col-12">
      <table class="table table-bordered" id="examinerTable">
        <thead class="table-light">
          <tr>
            <th style="width:20%;">Viva Date</th>
            <th style="width:20%;">Time</th>
            <th>Designation</th>
            <th style="width:80px;">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type="date" name="viva_date[]" class="form-control" required></td>
            <td><input type="time" name="time[]" class="form-control"></td>
            <td>
              <!-- designation select: use class, not id -->
              <select name="title[]" class="form-select designation-select" required>
                <option value="">Select Designation</option>
                <!-- options loaded by AJAX when committee changes -->
              </select>
            </td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row-btn">X</button></td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="col-md-12 text-end">
      <button type="button" class="btn btn-secondary" id="cancelEdit">Cancel</button>
      <button type="submit" name="save_schedule" class="btn btn-success">Save Schedule</button>
      <a href="admin_dashboard.php" class="btn btn-primary">Back</a>
    </div>
  </form>

  <!-- Schedule Table -->
  <table class="table table-bordered table-striped table-hover mt-4">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Time</th>
        <th>Viva Marks</th>
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
        <td><?= date("h:i A", strtotime($row['time'])) ?></td>
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
$(function(){

  // helper: load designation options for a committee via AJAX
  function loadDesignationsForCommittee(committee, callback) {
    if (!committee) {
      // clear selects
      $('.designation-select').each(function(){
         $(this).html('<option value="">Select Designation</option>');
      });
      if (typeof callback === 'function') callback();
      return;
    }

    $.post('get_designations.php', {committe_name: committee}, function(response){
        // response expected to be list of <option>...</option>
        // apply to all selects
        $('.designation-select').each(function(){
            // preserve current selected value if present
            var cur = $(this).val();
            $(this).html('<option value="">Select Designation</option>' + response);
            // try to restore if option exists
            if (cur) {
                if ($(this).find('option[value="'+cur+'"]').length) {
                    $(this).val(cur);
                } else {
                    $(this).val('');
                }
            }
        });
        updateDesignationOptions();
        if (typeof callback === 'function') callback();
    });
  }

  // Prevent duplicate selections across all designation selects
  function updateDesignationOptions() {
    const selectedValues = $('.designation-select').map(function(){ return $(this).val(); }).get().filter(v=>v && v!=='');
    $('.designation-select').each(function(){
        var self = $(this);
        self.find('option').each(function(){
            var opt = $(this);
            var val = opt.attr('value');
            if (!val) return; // skip placeholder
            // disable option if selected elsewhere (and not the current select's value)
            if (selectedValues.indexOf(val) !== -1 && self.val() !== val) {
                opt.prop('disabled', true);
            } else {
                opt.prop('disabled', false);
            }
        });
    });
  }

  // when committee changes -> reload options for all designation selects
  $('#committe_name').on('change', function(){
    var committee = $(this).val();
    loadDesignationsForCommittee(committee);
  });

  // addRow clones the first row and ensures event wiring
  $('#addRow').on('click', function(){
    var $first = $('#examinerTable tbody tr:first');
    var $clone = $first.clone();
    $clone.find('input').val('');
    // copy current options of first's select into clone select
    // If none loaded yet, ensure default placeholder present
    var $firstSelect = $('#examinerTable tbody tr:first').find('.designation-select');
    var firstOptionsHtml = $firstSelect.length ? $firstSelect.html() : '<option value="">Select Designation</option>';
    $clone.find('.designation-select').each(function(){
        $(this).html(firstOptionsHtml);
        $(this).val('');
    });
    $('#examinerTable tbody').append($clone);
    updateDesignationOptions();
  });

  // remove row
  $(document).on('click', '.remove-row-btn', function(){
    // if only one row left, don't remove - just clear
    if ($('#examinerTable tbody tr').length === 1) {
      var $r = $('#examinerTable tbody tr:first');
      $r.find('input').val('');
      $r.find('.designation-select').val('');
      updateDesignationOptions();
      return;
    }
    $(this).closest('tr').remove();
    updateDesignationOptions();
  });

  // track selection change to update disabling of options
  $(document).on('change', '.designation-select', function(){
    updateDesignationOptions();
  });

  // Edit button: populate form, load committee's designations, set designation value properly
  $('.editBtn').on('click', function(){
    var id = $(this).data('id');
    var date = $(this).data('date');
    var time = $(this).data('time');
    var marks = $(this).data('marks');
    var fullTitle = $(this).data('title') || ''; // full formatted value from DB
    var committee = $(this).data('committee') || '';

    $('#schedule_id').val(id);
    $('#marks').val(marks);
    $('#committe_name').val(committee);

    // clear table and create single row for editing
    $('#examinerTable tbody').html('');
    var rowHtml = '<tr>' +
        '<td><input type="date" name="viva_date[]" class="form-control" required></td>' +
        '<td><input type="time" name="time[]" class="form-control"></td>' +
        '<td><select name="title[]" class="form-select designation-select" required><option value="">Select Designation</option></select></td>' +
        '<td></td>' +
      '</tr>';
    $('#examinerTable tbody').append(rowHtml);

    // set date/time values
    $('#examinerTable tbody tr:first').find('input[name="viva_date[]"]').val(date);
    $('#examinerTable tbody tr:first').find('input[name="time[]"]').val(time);

    // Extract raw designation from stored full title:
    // pattern: "[Designation] পদে মৌখিক পরীক্ষায় অংশগ্রহনকারী প্রার্থীদের [Committee] কর্তৃক প্রদত্ত নম্বর শীট।"
    // build suffix using committee to remove
    var suffix = " পদে মৌখিক পরীক্ষায় অংশগ্রহনকারী প্রার্থীদের " + committee + " কর্তৃক প্রদত্ত নম্বর শীট।";
    var designationOnly = fullTitle;
    if (fullTitle.indexOf(suffix) !== -1) {
      designationOnly = fullTitle.replace(suffix, '');
    } else {
      // fallback: attempt to strip known fixed part (without committee) if needed
      var fallback = " পদে মৌখিক পরীক্ষায় অংশগ্রহনকারী প্রার্থীদের ";
      if (fullTitle.indexOf(fallback) !== -1) {
        designationOnly = fullTitle.split(fallback)[0];
      }
    }
    designationOnly = designationOnly.trim();

    // Load designations for this committee, then set the select to designationOnly
    loadDesignationsForCommittee(committee, function(){
      var $sel = $('#examinerTable tbody tr:first').find('.designation-select');
      // if option exists, set it; otherwise add it as a new option and select (so edit won't lose old custom designations)
      if ($sel.find('option[value="'+designationOnly+'"]').length) {
        $sel.val(designationOnly);
      } else if (designationOnly !== '') {
        // append as option then select
        $sel.append('<option value="'+designationOnly+'">'+designationOnly+'</option>');
        $sel.val(designationOnly);
      }
      updateDesignationOptions();
      // scroll to top so user sees form
      $('html, body').animate({ scrollTop: 0 }, 'fast');
    });
  });

  // Cancel resets the page/form
  $('#cancelEdit').on('click', function(){
    location.href = 'exam_schedule.php';
  });

  // When committee changes while editing/adding, reload designations for ALL selects and clear selected values
  // (we already bound #committe_name change above, but re-bind here to make sure)
  $('#committe_name').on('change', function(){
    var committee = $(this).val();
    loadDesignationsForCommittee(committee);
  });

  // Initial: if a committee is already selected on page load (e.g., kept by browser), load designations
  var initialCommittee = $('#committe_name').val();
  if (initialCommittee) {
    loadDesignationsForCommittee(initialCommittee);
  }

});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
