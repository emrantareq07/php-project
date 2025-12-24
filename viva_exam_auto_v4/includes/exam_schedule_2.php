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

/* ============================================================
   LOAD DATA FOR EDIT MODE (FROM URL PARAMETER)
============================================================ */
$edit_id = $_GET['edit'] ?? '';
$editData = null;
$editRolls = [];

if ($edit_id !== '') {
    // Load schedule
    $stmt = $conn->prepare("SELECT * FROM exam_schedule_tbl WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $editData = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Load linked rolls
    $stmt = $conn->prepare("SELECT roll_no FROM candidates_tbl WHERE exam_schedule_id=?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $rs = $stmt->get_result();
    while ($r = $rs->fetch_assoc()) {
        $editRolls[] = $r['roll_no'];
    }
    $stmt->close();
}

/* ============================================================
   SAVE (INSERT / UPDATE)
============================================================ */

if (isset($_POST['save_schedule'])) {
    $id        = trim($_POST['schedule_id'] ?? '');
    $marks     = $_POST['marks'] ?? 0;
    $committee = $_POST['committe_name'] ?? '';

    $viva_dates   = $_POST['viva_date'] ?? [];
    $times        = $_POST['time'] ?? [];
    $raw_titles   = $_POST['title'] ?? [];
    $roll_numbers = $_POST['roll_no'] ?? [];

    // Build formatted title (only for 1st row, as per your code)
    $formatted_title = '';
    if (!empty($raw_titles[0])) {
        $formatted_title = $raw_titles[0] . " পদে মৌখিক পরীক্ষায় অংশগ্রহনকারী প্রার্থীদের "
                          . $committee . " কর্তৃক প্রদত্ত নম্বর শীট।";
    }

    /* ----------------------------------------
       IF UPDATE
    ---------------------------------------- */
    if ($id !== "") {
        $stmt = $conn->prepare("
            UPDATE exam_schedule_tbl 
            SET date=?, time=?, marks=?, title=?, designation=?, committe_name=?
            WHERE id=?
        ");
        $stmt->bind_param(
            "ssdsssi",
            $viva_dates[0], 
            $times[0],
            $marks,
            $formatted_title,
            $raw_titles[0],
            $committee,
            $id
        );
        $stmt->execute();
        $stmt->close();

        $exam_schedule_id = $id;

        // Delete existing roll numbers
        $del = $conn->prepare("DELETE FROM candidates_tbl WHERE exam_schedule_id=?");
        $del->bind_param("i", $id);
        $del->execute();
        $del->close();
    } else {
        /* ----------------------------------------
           INSERT NEW SCHEDULE
        ---------------------------------------- */
        $stmt = $conn->prepare("
            INSERT INTO exam_schedule_tbl (date, time, marks, title, designation, committe_name)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssdsss",
            $viva_dates[0],
            $times[0],
            $marks,
            $formatted_title,
            $raw_titles[0],
            $committee
        );
        $stmt->execute();
        $exam_schedule_id = $conn->insert_id;
        $stmt->close();
    }

    /* ----------------------------------------
       INSERT LINKED ROLL NUMBERS
    ---------------------------------------- */
    if (!empty($roll_numbers)) {
        foreach ($roll_numbers as $roll) {
            // fetch candidate info
            $stmt = $conn->prepare("
                SELECT roll_no, post_name, dob 
                FROM candidates_tbl_new 
                WHERE roll_no=? LIMIT 1
            ");
            $stmt->bind_param("s", $roll);
            $stmt->execute();
            $res = $stmt->get_result();
            $candidate = $res->fetch_assoc();
            $stmt->close();

            if ($candidate) {
                $ins = $conn->prepare("
                    INSERT INTO candidates_tbl (roll_no, designation, dob, exam_schedule_id)
                    VALUES (?, ?, ?, ?)
                ");
                $ins->bind_param(
                    "sssi",
                    $candidate['roll_no'],
                    $candidate['post_name'],
                    $candidate['dob'],
                    $exam_schedule_id
                );
                $ins->execute();
                $ins->close();
            }
        }
    }

    header("Location: exam_schedule.php");
    exit;
}

/* ============================================================
   DELETE
============================================================ */
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM exam_schedule_tbl WHERE id=$id");
    $conn->query("DELETE FROM candidates_tbl WHERE exam_schedule_id=$id");
    header("Location: exam_schedule.php");
    exit;
}

/* ============================================================
   READ ALL SCHEDULES
============================================================ */
$schedules = $conn->query("SELECT * FROM exam_schedule_tbl ORDER BY id DESC");

// Get all roll numbers and their committee assignments
$allRolls = [];
$committeeAssignments = []; // Store which committee each roll is assigned to

$rollResult = $conn->query("
    SELECT c.roll_no, c.post_name, es.committe_name 
    FROM candidates_tbl_new c 
    LEFT JOIN candidates_tbl ct ON c.roll_no = ct.roll_no 
    LEFT JOIN exam_schedule_tbl es ON ct.exam_schedule_id = es.id 
    ORDER BY c.roll_no
");

while ($rollRow = $rollResult->fetch_assoc()) {
    $allRolls[] = $rollRow;
    if ($rollRow['committe_name']) {
        $committeeAssignments[$rollRow['roll_no']] = $rollRow['committe_name'];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Exam Schedule Manager</title>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Chosen CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

  <!-- Chosen JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

  <style>
      body { font-family: 'Noto Sans Bengali', sans-serif; background: #f9f9f9; }
      .small-note { font-size: 0.9rem; color: #666; }
      .chosen-container-multi .chosen-choices { min-height: 45px; }
      .disabled-roll { color: #999 !important; font-style: italic; }
      .roll-status { font-size: 0.8rem; color: #666; margin-left: 5px; }
  </style>
</head>

<body>
<div class="container py-4">

    <h3 class="mb-4 text-center bg-primary text-white p-2 rounded">
        Exam Schedule Manager
    </h3>

    <!-- ============================== -->
    <!-- FORM START -->
    <!-- ============================== -->

    <form method="POST" class="row g-3 mb-4" id="scheduleForm">
        <input type="hidden" name="schedule_id" id="schedule_id" value="<?= $editData['id'] ?? '' ?>">

        <!-- Viva Marks -->
        <div class="col-md-2">
            <label>Viva Marks</label>
            <input type="number" step="0.01" name="marks" id="marks" class="form-control" 
                   value="<?= $editData['marks'] ?? '20' ?>" required>
        </div>

        <!-- Committee Name -->
        <div class="col-md-4">
            <label>Committee Name</label>
            <select name="committe_name" id="committe_name" class="form-select" required>
                <option value="">Select Committee</option>
                <?php
                $result = mysqli_query($conn, "SELECT DISTINCT committe_name FROM committee_tbl");
                while ($r = mysqli_fetch_assoc($result)) {
                    $selected = ($editData['committe_name'] ?? '') == $r['committe_name'] ? 'selected' : '';
                    echo "<option value='{$r['committe_name']}' $selected>{$r['committe_name']}</option>";
                }
                ?>
            </select>
        </div>

        <!-- Roll Numbers -->
        <div class="col-md-4">
            <label>Candidates List</label>
            <select name="roll_no[]" id="roll_no" class="form-select" multiple required>
                <?php
                foreach ($allRolls as $roll) {
                    $rollNo = htmlspecialchars($roll['roll_no']);
                    $postName = htmlspecialchars($roll['post_name']);
                    $selected = in_array($rollNo, $editRolls) ? 'selected' : '';
                    $assignedCommittee = $committeeAssignments[$rollNo] ?? '';
                    
                    // For edit mode, show all rolls including those assigned to this committee
                    if (isset($editData) && $editData['committe_name'] == $assignedCommittee) {
                        // In edit mode for this committee, show as available
                        $statusText = '';
                    } else {
                        $statusText = $assignedCommittee ? " (Assigned to: $assignedCommittee)" : "";
                    }
                    
                    echo "<option value='{$rollNo}' data-post='{$postName}' data-committee='{$assignedCommittee}' $selected>{$rollNo}{$statusText}</option>";
                }
                ?>
            </select>
            <div class="small-note mt-1">Roll numbers already assigned to other committees will be disabled when you select a committee.</div>
        </div>

        <!-- Add Row Button -->
        <div class="col-md-2 text-end mt-3">
            <button type="button" id="addRow" class="btn btn-success btn-sm">+ Add Row</button>
        </div>

        <!-- TABLE -->
        <div class="col-12">
            <table class="table table-bordered" id="examinerTable">
                <thead class="table-light">
                    <tr>
                        <th width="20%">Viva Date</th>
                        <th width="20%">Time</th>
                        <th>Designation</th>
                        <th width="80px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($editData)): ?>
                    <tr>
                        <td>
                            <input type="date" name="viva_date[]" class="form-control" 
                                   value="<?= $editData['date'] ?? '' ?>" required>
                        </td>
                        <td>
                            <input type="time" name="time[]" class="form-control" 
                                   value="<?= $editData['time'] ?? '' ?>">
                        </td>
                        <td>
                            <?php
                            // Extract designation from title
                            $designationValue = '';
                            if (isset($editData['title'])) {
                                $fullTitle = $editData['title'];
                                $committeeName = $editData['committe_name'] ?? '';
                                $suffix = " পদে মৌখিক পরীক্ষায় অংশগ্রহনকারী প্রার্থীদের " . $committeeName . " কর্তৃক প্রদত্ত নম্বর শীট।";
                                if (strpos($fullTitle, $suffix) !== false) {
                                    $designationValue = str_replace($suffix, '', $fullTitle);
                                } else {
                                    // Try alternative extraction
                                    $parts = explode(" পদে মৌখিক পরীক্ষায় অংশগ্রহনকারী প্রার্থীদের ", $fullTitle);
                                    if (count($parts) > 1) {
                                        $designationValue = $parts[0];
                                    }
                                }
                                $designationValue = trim($designationValue);
                            }
                            ?>
                            <select name="title[]" class="form-select designation-select" required>
                                <option value="">Select Designation</option>
                                <?php
                                if ($designationValue) {
                                    echo "<option value='{$designationValue}' selected>{$designationValue}</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-row-btn">X</button></td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td><input type="date" name="viva_date[]" class="form-control" required></td>
                        <td><input type="time" name="time[]" class="form-control"></td>
                        <td>
                            <select name="title[]" class="form-select designation-select" required>
                                <option value="">Select Designation</option>
                            </select>
                        </td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-row-btn">X</button></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Buttons -->
        <div class="col-md-12 text-end">
            <button type="submit" name="save_schedule" class="btn btn-success">Save Schedule</button>
            <?php if (isset($editData)): ?>
                <a href="exam_schedule.php" class="btn btn-secondary">Cancel Edit</a>
            <?php endif; ?>
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
                <th>Roll No List</th>
                <th>Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($schedules->num_rows > 0): ?>
                <?php while ($row = $schedules->fetch_assoc()): ?>
                    <?php
                    // Fetch all roll numbers for this schedule
                    $sid = $row['id'];
                    $rollList = [];
                    $qr = mysqli_query($conn, "SELECT roll_no FROM candidates_tbl WHERE exam_schedule_id = '$sid'");
                    while ($rr = mysqli_fetch_assoc($qr)) {
                        $rollList[] = $rr['roll_no'];
                    }
                    $rollNoString = !empty($rollList) ? implode(', ', $rollList) : '—';
                    
                    // Extract designation for edit button
                    $designationForEdit = '';
                    if (!empty($row['title'])) {
                        $fullTitle = $row['title'];
                        $committeeName = $row['committe_name'] ?? '';
                        $suffix = " পদে মৌখিক পরীক্ষায় অংশগ্রহনকারী প্রার্থীদের " . $committeeName . " কর্তৃক প্রদত্ত নম্বর শীট।";
                        if (strpos($fullTitle, $suffix) !== false) {
                            $designationForEdit = str_replace($suffix, '', $fullTitle);
                        } else {
                            $parts = explode(" পদে মৌখিক পরীক্ষায় অংশগ্রহনকারী প্রার্থীদের ", $fullTitle);
                            if (count($parts) > 1) {
                                $designationForEdit = $parts[0];
                            } else {
                                $designationForEdit = $row['designation'] ?? '';
                            }
                        }
                        $designationForEdit = trim($designationForEdit);
                    } else {
                        $designationForEdit = $row['designation'] ?? '';
                    }
                    ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['date'] ?></td>
                        <td><?= date("h:i A", strtotime($row['time'])) ?></td>
                        <td><?= $row['marks'] ?></td>
                        <td><?= htmlspecialchars($row['committe_name']) ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars($rollNoString) ?></td>
                        <td><?= $row['created_at'] ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary editBtn" 
                                    data-id="<?= $row['id'] ?>" 
                                    data-date="<?= $row['date'] ?>" 
                                    data-time="<?= $row['time'] ?>" 
                                    data-marks="<?= $row['marks'] ?>" 
                                    data-designation="<?= htmlspecialchars($designationForEdit) ?>"
                                    data-committee="<?= htmlspecialchars($row['committe_name']) ?>"
                                    data-rolls='<?= json_encode($rollList) ?>'>
                                Edit
                            </button>
                            <a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                               onclick="return confirm('Delete this schedule?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        No schedules found.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    // Store all roll numbers with their committee assignments
    var allRollsData = {};
    $("#roll_no option").each(function() {
        var roll = $(this).val();
        var committee = $(this).data('committee') || '';
        allRollsData[roll] = committee;
    });

    // Initialize Chosen for roll numbers
    $("#roll_no").chosen({
        width: "100%",
        placeholder_text_multiple: "Choose Roll Numbers"
    });

    // Function to update roll number availability based on selected committee
    function updateRollAvailability(selectedCommittee) {
        $("#roll_no option").each(function() {
            var $option = $(this);
            var rollCommittee = $option.data('committee') || '';
            var isSelected = $option.prop('selected');
            
            // In edit mode, we want to keep already selected options enabled
            var scheduleId = $('#schedule_id').val();
            var isEditMode = scheduleId !== '';
            
            if (isEditMode && isSelected) {
                // In edit mode, keep selected options enabled regardless of committee assignment
                $option.prop('disabled', false);
                $option.removeClass('disabled-roll');
            } else if (rollCommittee && rollCommittee !== selectedCommittee) {
                // Disable options assigned to other committees
                $option.prop('disabled', true);
                $option.addClass('disabled-roll');
            } else {
                // Enable options not assigned or assigned to the same committee
                $option.prop('disabled', false);
                $option.removeClass('disabled-roll');
            }
        });
        
        // Update Chosen dropdown
        $("#roll_no").trigger("chosen:updated");
    }

    // Function to load post names into designation dropdowns
    function loadPostNames() {
        let posts = [];
        $("#roll_no option:selected").each(function() {
            let post = $(this).data("post");
            if (post && !posts.includes(post)) posts.push(post);
        });

        $(".designation-select").each(function() {
            let sel = $(this);
            let oldValue = sel.val();
            
            // Clear and add placeholder
            sel.empty().append('<option value="">Select Designation</option>');
            
            // Add options from selected rolls
            posts.forEach(p => {
                sel.append(`<option value="${p}">${p}</option>`);
            });
            
            // Restore old value if it exists
            if (oldValue) {
                sel.val(oldValue);
            }
        });
    }

    // Update roll availability when committee changes
    $('#committe_name').on('change', function() {
        var selectedCommittee = $(this).val();
        updateRollAvailability(selectedCommittee);
        
        // Also load designations for this committee
        if (selectedCommittee) {
            $.post('get_designations.php', {committe_name: selectedCommittee}, function(response) {
                $('.designation-select').each(function() {
                    var sel = $(this);
                    var oldVal = sel.val();
                    sel.html('<option value="">Select Designation</option>' + response);
                    if (oldVal) {
                        sel.val(oldVal);
                    }
                });
            });
        }
    });

    // Update designation options when roll numbers change
    $("#roll_no").on("change", loadPostNames);

    // Initialize designation options on page load
    loadPostNames();

    // Add Row functionality
    $("#addRow").click(function() {
        let newRow = `
        <tr>
            <td><input type="date" name="viva_date[]" class="form-control" required></td>
            <td><input type="time" name="time[]" class="form-control"></td>
            <td>
                <select name="title[]" class="form-select designation-select" required>
                    <option value="">Select Designation</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row-btn">X</button></td>
        </tr>
        `;
        $("#examinerTable tbody").append(newRow);
        loadPostNames();
    });

    // Remove Row functionality
    $(document).on("click", ".remove-row-btn", function() {
        if ($("#examinerTable tbody tr").length > 1) {
            $(this).closest("tr").remove();
        } else {
            // If only one row, clear it instead of removing
            $(this).closest("tr").find('input').val('');
            $(this).closest("tr").find('.designation-select').val('');
        }
        loadPostNames();
    });

    // Edit button click handler
    $(document).on('click', '.editBtn', function() {
        var id = $(this).data('id');
        var date = $(this).data('date');
        var time = $(this).data('time');
        var marks = $(this).data('marks');
        var designation = $(this).data('designation') || '';
        var committee = $(this).data('committee');
        var rolls = $(this).data('rolls') || [];

        // Fill form fields
        $('#schedule_id').val(id);
        $('#marks').val(marks);
        $('#committe_name').val(committee);
        
        // Set roll numbers
        $("#roll_no").val(rolls);
        
        // Update roll availability for this committee
        updateRollAvailability(committee);
        
        // Clear table and add one row
        $('#examinerTable tbody').html('');
        var rowHtml = `
            <tr>
                <td><input type="date" name="viva_date[]" class="form-control" value="${date}" required></td>
                <td><input type="time" name="time[]" class="form-control" value="${time}"></td>
                <td>
                    <select name="title[]" class="form-select designation-select" required>
                        <option value="">Select Designation</option>
                        ${designation ? `<option value="${designation}" selected>${designation}</option>` : ''}
                    </select>
                </td>
                <td><button type="button" class="btn btn-danger btn-sm remove-row-btn">X</button></td>
            </tr>
        `;
        $('#examinerTable tbody').append(rowHtml);
        
        // Load post names from selected rolls
        loadPostNames();
        
        // If committee has designations, load them
        if (committee) {
            $.post('get_designations.php', {committe_name: committee}, function(response) {
                $('.designation-select').each(function() {
                    var sel = $(this);
                    var currentHtml = sel.html();
                    sel.html('<option value="">Select Designation</option>' + response);
                    
                    // Add back the designation option if it doesn't exist
                    if (designation && sel.find('option[value="' + designation + '"]').length === 0) {
                        sel.append(`<option value="${designation}">${designation}</option>`);
                    }
                    
                    // Set the value
                    if (designation) {
                        sel.val(designation);
                    }
                });
            });
        } else {
            // If no committee, just ensure designation is in dropdown
            $('.designation-select').each(function() {
                var sel = $(this);
                if (designation && sel.find('option[value="' + designation + '"]').length === 0) {
                    sel.append(`<option value="${designation}" selected>${designation}</option>`);
                    sel.val(designation);
                }
            });
        }
        
        // Scroll to form
        $('html, body').animate({ scrollTop: 0 }, 'slow');
    });

    // If in edit mode via URL parameter (edit=id)
    <?php if (isset($editData) && !empty($editData['committe_name'])): ?>
    $(document).ready(function() {
        var committee = '<?= $editData['committe_name'] ?>';
        if (committee) {
            // Update roll availability for this committee
            updateRollAvailability(committee);
            
            // Load designations for this committee
            $.post('get_designations.php', {committe_name: committee}, function(response) {
                $('.designation-select').each(function() {
                    var sel = $(this);
                    var oldVal = sel.val();
                    sel.html('<option value="">Select Designation</option>' + response);
                    if (oldVal) {
                        sel.val(oldVal);
                    }
                });
            });
        }
    });
    <?php endif; ?>
    
    // Initialize roll availability on page load
    var initialCommittee = $('#committe_name').val();
    if (initialCommittee) {
        updateRollAvailability(initialCommittee);
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>