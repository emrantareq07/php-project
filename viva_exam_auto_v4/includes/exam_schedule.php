<?php
// session_name('viva_exam_db');
// session_start();
// include('../db/db.php');
// date_default_timezone_set('Asia/Dhaka');

// Redirect if not logged in
// if (!isset($_SESSION['username'])) {
//     header("Location: ../index.php");
//     exit;
// }

/* ============================================================
   LOAD DATA FOR EDIT MODE (FROM URL PARAMETER)
============================================================ */
// $edit_id = $_GET['edit'] ?? '';
// $editData = null;
// $editRolls = [];

// if ($edit_id !== '') {
//     // Load schedule
//     $stmt = $conn->prepare("SELECT * FROM exam_schedule_tbl WHERE id=? LIMIT 1");
//     $stmt->bind_param("i", $edit_id);
//     $stmt->execute();
//     $editData = $stmt->get_result()->fetch_assoc();
//     $stmt->close();

//     // Load linked rolls
//     $stmt = $conn->prepare("SELECT roll_no FROM candidates_tbl WHERE exam_schedule_id=?");
//     $stmt->bind_param("i", $edit_id);
//     $stmt->execute();
//     $rs = $stmt->get_result();
//     while ($r = $rs->fetch_assoc()) {
//         $editRolls[] = $r['roll_no'];
//     }
//     $stmt->close();
// }

/* ============================================================
   SAVE (INSERT / UPDATE)
============================================================ */

// if (isset($_POST['save_schedule'])) {
//     $id        = trim($_POST['schedule_id'] ?? '');
//     $marks     = $_POST['marks'] ?? 0;
//     $committee = $_POST['committe_name'] ?? '';

//     $viva_dates   = $_POST['viva_date'] ?? [];
//     $times        = $_POST['time'] ?? [];
//     $raw_titles   = $_POST['title'] ?? [];
//     $roll_numbers = $_POST['roll_no'] ?? [];

//     // Build formatted title (only for 1st row, as per your code)
//     $formatted_title = '';
//     if (!empty($raw_titles[0])) {
//         $formatted_title = $raw_titles[0] . " পদে মৌখিক পরীক্ষায় অংশগ্রহনকারী প্রার্থীদের "
//                           . $committee . " কর্তৃক প্রদত্ত নম্বর শীট।";
//     }

    /* ----------------------------------------
       IF UPDATE
    ---------------------------------------- */
//     if ($id !== "") {
//         $stmt = $conn->prepare("
//             UPDATE exam_schedule_tbl 
//             SET date=?, time=?, marks=?, title=?, designation=?, committe_name=?
//             WHERE id=?
//         ");
//         $stmt->bind_param(
//             "ssdsssi",
//             $viva_dates[0], 
//             $times[0],
//             $marks,
//             $formatted_title,
//             $raw_titles[0],
//             $committee,
//             $id
//         );
//         $stmt->execute();
//         $stmt->close();

//         $exam_schedule_id = $id;

//         // Delete existing roll numbers
//         $del = $conn->prepare("DELETE FROM candidates_tbl WHERE exam_schedule_id=?");
//         $del->bind_param("i", $id);
//         $del->execute();
//         $del->close();
//     } else {
//         /* ----------------------------------------
//            INSERT NEW SCHEDULE
//         ---------------------------------------- */
//         $stmt = $conn->prepare("
//             INSERT INTO exam_schedule_tbl (date, time, marks, title, designation, committe_name)
//             VALUES (?, ?, ?, ?, ?, ?)
//         ");
//         $stmt->bind_param(
//             "ssdsss",
//             $viva_dates[0],
//             $times[0],
//             $marks,
//             $formatted_title,
//             $raw_titles[0],
//             $committee
//         );
//         $stmt->execute();
//         $exam_schedule_id = $conn->insert_id;
//         $stmt->close();
//     }

//     /* ----------------------------------------
//        INSERT LINKED ROLL NUMBERS
//     ---------------------------------------- */
//     if (!empty($roll_numbers)) {
//         foreach ($roll_numbers as $roll) {
//             // fetch candidate info
//             $stmt = $conn->prepare("
//                 SELECT roll_no, post_name, dob 
//                 FROM candidates_tbl_new 
//                 WHERE roll_no=? LIMIT 1
//             ");
//             $stmt->bind_param("s", $roll);
//             $stmt->execute();
//             $res = $stmt->get_result();
//             $candidate = $res->fetch_assoc();
//             $stmt->close();

//             if ($candidate) {
//                 $ins = $conn->prepare("
//                     INSERT INTO candidates_tbl (roll_no, designation, dob, exam_schedule_id)
//                     VALUES (?, ?, ?, ?)
//                 ");
//                 $ins->bind_param(
//                     "sssi",
//                     $candidate['roll_no'],
//                     $candidate['post_name'],
//                     $candidate['dob'],
//                     $exam_schedule_id
//                 );
//                 $ins->execute();
//                 $ins->close();
//             }
//         }
//     }

//     header("Location: exam_schedule.php");
//     exit;
// }






/* ============================================================
   DELETE
============================================================ */
// if (isset($_GET['delete'])) {
//     $id = intval($_GET['delete']);
//     $conn->query("DELETE FROM exam_schedule_tbl WHERE id=$id");
//     $conn->query("DELETE FROM candidates_tbl WHERE exam_schedule_id=$id");
//     header("Location: exam_schedule.php");
//     exit;
// }

// /* ============================================================
//    READ ALL SCHEDULES
// ============================================================ */
// $schedules = $conn->query("SELECT * FROM exam_schedule_tbl ORDER BY id DESC");

 ?>

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
   LOAD DATA FOR EDIT MODE
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

    // Build formatted title (only for 1st row)
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

        // 1. Get currently saved roll numbers
        $existingRolls = [];
        $stmt = $conn->prepare("SELECT roll_no FROM candidates_tbl WHERE exam_schedule_id=?");
        $stmt->bind_param("i", $exam_schedule_id);
        $stmt->execute();
        $rs = $stmt->get_result();
        while ($r = $rs->fetch_assoc()) {
            $existingRolls[] = $r['roll_no'];
        }
        $stmt->close();

        // 2. Delete rolls that were removed
        $toDelete = array_diff($existingRolls, $roll_numbers);
        if (!empty($toDelete)) {
            $del = $conn->prepare("DELETE FROM candidates_tbl WHERE exam_schedule_id=? AND roll_no=?");
            foreach ($toDelete as $roll) {
                $del->bind_param("is", $exam_schedule_id, $roll);
                $del->execute();
            }
            $del->close();
        }

        // 3. Insert new rolls
        $toInsert = array_diff($roll_numbers, $existingRolls);
        if (!empty($toInsert)) {
            foreach ($toInsert as $roll) {
                // fetch candidate info from candidates_new_tbl
                $stmt = $conn->prepare("
                    SELECT * 
                    FROM candidates_tbl_new 
                    WHERE roll_no=? LIMIT 1
                ");
                $stmt->bind_param("s", $roll);
                $stmt->execute();
                $res = $stmt->get_result();
                $candidate = $res->fetch_assoc();
                $stmt->close();

                if ($candidate) {
                    // Map fields from candidates_new_tbl to candidates_tbl
                    $ins = $conn->prepare("
                        INSERT INTO candidates_tbl (
                            `exam_schedule_id`,
                            `roll_no`, 
                            `name`, 
                            `fathers_name`, 
                            `mothers_name`, 
                            `district`, 
                            `dob`, 
                            `ssc`, 
                            `hsc`, 
                            `honors`, 
                            `masters`, 
                            `designation`, 
                            `written_marks`, 
                            `viva_marks`, 
                            `committe_name`, 
                            `status`, 
                            `remarks`, 
                            `image`
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    
                    // Mapping logic:
                    // candidates_tbl field => candidates_new_tbl field
                    $candidate_name = $candidate['name'] ?? '';
                    $fathers_name = $candidate['father'] ?? '';
                    $mothers_name = $candidate['mother'] ?? '';
                    $district = $candidate['home_district'] ?? '';
                    $dob = $candidate['dob'] ?? '';
                    $ssc = $candidate['ssc_result'] ?? '';
                    $hsc = $candidate['hsc_result'] ?? '';
                    $honors = $candidate['gra_result'] ?? ''; // Assuming gra_result is honors
                    $masters = $candidate['mas_result'] ?? '';
                    $designation = $candidate['post_name'] ?? $raw_titles[0];
                    $written_marks = $candidate['written_marks'] ?? 0;
                    $viva_marks = $candidate['viva_marks'] ?? 0;
                    $status = 'Pending';
                    $remarks = '';
                    $image = ''; // You might want to add image field in candidates_new_tbl if needed
                    
                    $ins->bind_param(
                        "isssssssssssdsssss",
                        $exam_schedule_id,        // i
                        $roll,                    // s
                        $candidate_name,          // s
                        $fathers_name,            // s
                        $mothers_name,            // s
                        $district,                // s
                        $dob,                     // s
                        $ssc,                     // s
                        $hsc,                     // s
                        $honors,                  // s
                        $masters,                 // s
                        $designation,             // s
                        $written_marks,           // d (double/float)
                        $viva_marks,              // s (but storing as string)
                        $committee,               // s
                        $status,                  // s
                        $remarks,                 // s
                        $image                    // s
                    );
                    
                    if ($ins->execute()) {
                        // Success
                    } else {
                        // Log error for debugging
                        error_log("Failed to insert candidate $roll: " . $ins->error);
                    }
                    $ins->close();
                } else {
                    // If candidate not found in new table, insert basic info
                    $ins = $conn->prepare("
                        INSERT INTO candidates_tbl (
                            `exam_schedule_id`,
                            `roll_no`, 
                            `name`, 
                            `designation`, 
                            `committe_name`,
                            `status`
                        ) VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    
                    $ins->bind_param(
                        "isssss",
                        $exam_schedule_id,
                        $roll,
                        'Candidate ' . $roll,
                        $raw_titles[0],
                        $committee,
                        'Pending'
                    );
                    $ins->execute();
                    $ins->close();
                }
            }
        }

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

        // Insert rolls for new schedule
        if (!empty($roll_numbers)) {
            foreach ($roll_numbers as $roll) {
                // fetch candidate info from candidates_new_tbl
                $stmt = $conn->prepare("
                    SELECT *
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
                        INSERT INTO candidates_tbl (
                            `exam_schedule_id`,
                            `roll_no`, 
                            `name`, 
                            `fathers_name`, 
                            `mothers_name`, 
                            `district`, 
                            `dob`, 
                            `ssc`, 
                            `hsc`, 
                            `honors`, 
                            `masters`, 
                            `designation`, 
                            `written_marks`, 
                            `viva_marks`, 
                            `committe_name`, 
                            `status`, 
                            `remarks`, 
                            `image`
                        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    
                    // Mapping logic
                    $candidate_name = $candidate['name'] ?? '';
                    $fathers_name = $candidate['father'] ?? '';
                    $mothers_name = $candidate['mother'] ?? '';
                    $district = $candidate['home_district'] ?? '';
                    $dob = $candidate['dob'] ?? '';
                    $ssc = $candidate['ssc_result'] ?? '';
                    $hsc = $candidate['hsc_result'] ?? '';
                    $honors = $candidate['gra_result'] ?? '';
                    $masters = $candidate['mas_result'] ?? '';
                    $designation = $candidate['post_name'] ?? $raw_titles[0];
                    $written_marks = $candidate['written_marks'] ?? 0;
                    $viva_marks = $candidate['viva_marks'] ?? 0;
                    $status = 'Pending';
                    $remarks = '';
                    $image = '';
                    
                    $ins->bind_param(
                        "isssssssssssdsssss",
                        $exam_schedule_id,        // i
                        $roll,                    // s
                        $candidate_name,          // s
                        $fathers_name,            // s
                        $mothers_name,            // s
                        $district,                // s
                        $dob,                     // s
                        $ssc,                     // s
                        $hsc,                     // s
                        $honors,                  // s
                        $masters,                 // s
                        $designation,             // s
                        $written_marks,           // d (double/float)
                        $viva_marks,              // s
                        $committee,               // s
                        $status,                  // s
                        $remarks,                 // s
                        $image                    // s
                    );
                    
                    if ($ins->execute()) {
                        // Success
                    } else {
                        error_log("Failed to insert candidate $roll: " . $ins->error);
                    }
                    $ins->close();
                } else {
                    // If candidate not found in new table, insert basic info
                    $ins = $conn->prepare("
                        INSERT INTO candidates_tbl (
                            `exam_schedule_id`,
                            `roll_no`, 
                            `name`, 
                            `designation`, 
                            `committe_name`,
                            `status`
                        ) VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    
                    $ins->bind_param(
                        "isssss",
                        $exam_schedule_id,
                        $roll,
                        'Candidate ' . $roll,
                        $raw_titles[0],
                        $committee,
                        'Pending'
                    );
                    $ins->execute();
                    $ins->close();
                }
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
   Active Exam 
============================================================ */
if (isset($_GET['active_exam'])) {
    $id = intval($_GET['active_exam']);    
    $committe_name = $_GET['committe_name'];
    $conn->query("UPDATE committee_tbl 
            SET exam_schedule_id= $id WHERE committe_name='$committe_name'");
    header("Location: exam_schedule.php");
    exit;
}

/* ============================================================
   READ ALL SCHEDULES
============================================================ */
$schedules = $conn->query("SELECT * FROM exam_schedule_tbl ORDER BY id DESC");
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
  <style>
/*@import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@400;700&display=swap');*/
               
/*body { font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif; background: #f8f9fa; }*/
/*body{font-family:'Noto Sans Bengali',sans-serif;background:#f8f9fa;}*/
      body { font-family: 'Noto Sans Bengali', sans-serif; background: #f9f9f9; }
      .small-note { font-size: 0.9rem; color: #666; }
      .chosen-container-multi .chosen-choices { min-height: 45px; }
/*      * {
  font-family: 'Open Sans', 'Noto Sans Bengali', sans-serif;
}*/
/* Font Definitions */
/*@font-face {
  font-family: 'Nikosh';
  src: url('fonts/Nikosh.ttf') format('truetype'),
       url('fonts/Nikosh.woff') format('woff'),
       url('fonts/Nikosh.woff2') format('woff2');
  font-weight: normal;
  font-style: normal;
  font-display: swap;
}*/
  </style>
</head>

<body>
<div class="container py-1">
    <h3 class="mb-2 text-center bg-primary text-white p-2 rounded">
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

<!-- Roll Numbers  2nd-->
<!-- <div class="col-md-4">
    <label>Candidates List</label>
    <select name="roll_no[]" id="roll_no" class="form-select" multiple required>
        <?php
        //$result = mysqli_query($conn, "SELECT DISTINCT roll_no, name, post_name FROM candidates_tbl_new");
      //  while ($r = mysqli_fetch_assoc($result)) {
          //  $roll = htmlspecialchars($r['roll_no']);
          //  $name = htmlspecialchars($r['name']);
           // $post = htmlspecialchars($r['post_name']);
           // $selected = in_array($roll, $editRolls) ? 'selected' : '';
          //  echo "<option value='{$roll}' data-name='{$name}' data-post='{$post}' $selected>{$roll} - {$name} ({$post})</option>";
        //}
       // ?>
    </select>
    <div class="small-note mt-1">Select multiple roll numbers.</div>
</div> -->

<!-- Candidates List -->
<div class="col-md-4">
    <label>Candidates List</label>
    <select name="roll_no[]" id="roll_no" class="form-select" multiple required>
        <?php
        $result = mysqli_query($conn, "SELECT DISTINCT roll_no, name, post_name FROM candidates_tbl_new");
        while ($r = mysqli_fetch_assoc($result)) {
            $roll = htmlspecialchars($r['roll_no']);
            $name = htmlspecialchars($r['name']);
            $post = htmlspecialchars($r['post_name']);
            $selected = in_array($roll, $editRolls) ? 'selected' : '';
            echo "<option value='{$roll}' data-post='{$post}' $selected>{$roll} - {$name} ({$post})</option>";
        }
        ?>
    </select>
    <div class="small-note mt-1">Select multiple roll numbers (all must have the same designation).</div>
</div>

<script>
$(document).ready(function() {
    let previousSelection = [];

    $('#roll_no').change(function() {
        let selectedOptions = $(this).find('option:selected');
        let selectedPosts = new Set();

        selectedOptions.each(function() {
            selectedPosts.add($(this).data('post'));
        });

        // If more than one designation is selected, revert to previous selection
        if (selectedPosts.size > 1) {
            alert('All selected candidates must have the same designation!');
            $(this).val(previousSelection); // revert to previous valid selection
        } else {
            // Update previousSelection
            previousSelection = selectedOptions.map(function() {
                return $(this).val();
            }).get();
        }
    });
});
</script>
        <!-- Roll Numbers ist -->
<!--         <div class="col-md-4">
            <label>Candidates List</label>
            <select name="roll_no[]" id="roll_no" class="form-select" multiple required>
                <?php
               // $result = mysqli_query($conn, "SELECT DISTINCT roll_no,name post_name FROM candidates_tbl_new");
               /// while ($r = mysqli_fetch_assoc($result)) {
                   // $roll = htmlspecialchars($r['roll_no']);
                    //$post = htmlspecialchars($r['post_name']);
                   // $selected = in_array($roll, $editRolls) ? 'selected' : '';
                    //echo "<option value='{$roll}' data-post='{$post}' $selected>{$roll.name.post_name}</option>";
               // }
                ?>
            </select>
            <div class="small-note mt-1">Select multiple roll numbers.</div>
        </div> --> 

        <!-- TABLE -->
        <div class="col-12">
            <button type="button" id="addRow" class="btn btn-success btn-sm mb-3 float-end">+ Add Row</button>
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
            <button type="submit" name="save_schedule" class="btn btn-success"><i class="fas fa-save me-1"></i>Save Schedule</button>
            <?php if (isset($editData)): ?>
                <a href="exam_schedule.php" class="btn btn-secondary">Cancel Edit</a>
            <?php endif; ?>
            <a href="admin_dashboard.php" class="btn btn-primary"><i class="fas fa-arrow-left me-1"></i>Back</a>
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

                              <a href="?active_exam=<?= $row['id'] ?>&committe_name=<?= $row['committe_name'] ?>" 
                               class="btn btn-success btn-sm"
                               onclick="return confirm('Confirm this?')">
                               Active Exam
                            </a>
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
    // Initialize Chosen for roll numbers
    $("#roll_no").chosen({
        width: "100%",
        placeholder_text_multiple: "Choose Roll Numbers"
    });

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

    // Handle committee change to load designations
    // $('#committe_name').on('change', function() {
    //     var committee = $(this).val();
    //     if (committee) {
    //         $.post('get_designations.php', {committe_name: committee}, function(response) {
    //             $('.designation-select').each(function() {
    //                 var sel = $(this);
    //                 var oldVal = sel.val();
    //                 sel.html('<option value="">Select Designation</option>' + response);
    //                 if (oldVal) {
    //                     sel.val(oldVal);
    //                 }
    //             });
    //         });
    //     }
    // });

    // Edit button click handler
    $(document).on('click', '.editBtn', function() {
        var id = $(this).data('id');
        var date = $(this).data('date');
        var time = $(this).data('time');
        var marks = $(this).data('marks');
        var designation = $(this).data('designation') || '';
        var committee = $(this).data('committee');
        var rolls = $(this).data('rolls') || [];

        console.log('Edit clicked:', {id, date, time, marks, designation, committee, rolls});

        // Fill form fields
        $('#schedule_id').val(id);
        $('#marks').val(marks);
        $('#committe_name').val(committee);
        
        // Set roll numbers
        $("#roll_no").val(rolls);
        $("#roll_no").trigger("chosen:updated");
        
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
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>