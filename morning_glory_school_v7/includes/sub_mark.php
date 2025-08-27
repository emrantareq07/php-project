<?php
session_start();  
require_once("../config/config.php");
require_once("../db/db.php");

if (isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
    include_once(ROOT_PATH.'/libs/function.php');
    $usercredentials = new DB_con();

    // Fetch username
    $uname = $_SESSION["uname"] ?? ($_COOKIE['user_login'] ?? ''); 

    $query = "SELECT * FROM tblusers WHERE Username='$uname'";
    $result = $usercredentials->runBaseQuery($query);

    foreach ($result as $k => $v) {
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
    }

    // Get student details
    $reg_no = $_GET['reg_no'] ?? '';
    $student = null;
    $table_name = '';

    if ($reg_no) {
        // PDO connection for all queries
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "morning_glory_db";

        try {
            $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare("SELECT * FROM std_tbl WHERE reg_no = ?");
            $stmt->execute([$reg_no]);
            $student = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($student) {
                $class_db = $student['class'];
                $class = $class_db;
                $table_name = strtolower(trim($class . '_tbl')); // normalize
            }

        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    if (!$student) {
        die("Student not found!");
    }

    try {
        // Map table names to subject codes
        $sub_code_map = [
            'play_tbl'    => 0,
            'nursery_tbl' => 1,
            'one_tbl'     => 11,
            'two_tbl'     => 22,
            'three_tbl'     => 33,
            'four_tbl'     => 44,
            'five_tbl'     => 55,
            // Add more mappings here if needed
        ];

        if (isset($sub_code_map[$table_name])) {
            $sql = "SELECT * FROM subjects WHERE sub_code = :sub_code";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['sub_code' => $sub_code_map[$table_name]]);
        } else {
            // No match -> show all subjects
            $stmt = $pdo->prepare("SELECT * FROM subjects");
            $stmt->execute();
        }

        $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch existing marks
        $marks_stmt = $pdo->prepare("SELECT * FROM $table_name WHERE reg_no = ?");
        $marks_stmt->execute([$reg_no]);
        $existing_marks = $marks_stmt->fetchAll(PDO::FETCH_ASSOC);

        // Prepare arrays for form pre-fill
        $subjects_arr    = [];
        $first_term_arr  = [];
        $second_term_arr = [];
        $third_term_arr  = [];

        if (!empty($existing_marks)) {
            $row = $existing_marks[0];
            $subjects_arr    = !empty($row['subject'])     ? explode(',', $row['subject'])     : [];
            $first_term_arr  = !empty($row['first_term'])  ? explode(',', $row['first_term'])  : [];
            $second_term_arr = !empty($row['second_term']) ? explode(',', $row['second_term']) : [];
            $third_term_arr  = !empty($row['third_term'])  ? explode(',', $row['third_term'])  : [];
        }

        $message = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_marks'])) {
            $reg_no = $_POST['reg_no'];
            $subjects_post = $_POST['subjects'] ?? [];
            $first_term    = $_POST['1st_term'] ?? [];
            $second_term   = $_POST['2nd_term'] ?? [];
            $third_term    = $_POST['3rd_term'] ?? [];

            // Validate arrays have same length
            if (count($subjects_post) !== count($first_term) || 
                count($subjects_post) !== count($second_term) || 
                count($subjects_post) !== count($third_term)) {
                $message = "Error: Form data is invalid!";
            } else {
                // Convert arrays to comma separated strings
                $subjects_str    = implode(',', $subjects_post);
                $first_term_str  = implode(',', $first_term);
                $second_term_str = implode(',', $second_term);
                $third_term_str  = implode(',', $third_term);

                $update_std = $pdo->prepare("UPDATE $table_name 
                    SET subject=?, first_term=?, second_term=?, third_term=? 
                    WHERE reg_no=?");

                if ($update_std->execute([
                    $subjects_str,
                    $first_term_str,
                    $second_term_str,
                    $third_term_str,
                    $reg_no
                ])) {
                    $message = "Marks saved successfully!";
                    // Refresh pre-fill arrays with updated data
                    $subjects_arr    = $subjects_post;
                    $first_term_arr  = $first_term;
                    $second_term_arr = $second_term;
                    $third_term_arr  = $third_term;
                } else {
                    $message = "Error saving marks!";
                }
            }
        }

    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Student Subject Marks</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
<style>
    .duplicate-error { color: red; font-size: 0.8rem; display: none; }
</style>
</head>
<body>
<div class="container mt-3">
    <h2 class="text-center mb-0 text-uppercase text-muted">New Morning Glory</h2>
    <h5><i class="fas fa-book"></i> Subject Marks for <?= htmlspecialchars($student['name'] ?? '') ?> (Reg No: <?= htmlspecialchars($reg_no) ?> Class: <?= htmlspecialchars($class)?> Section: <?= htmlspecialchars($student['section'])?>)</h5>
    
    <?php if ($message): ?>
        <div class="alert alert-<?= strpos($message, 'Error') !== false ? 'danger' : 'success' ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" id="marks-form">
        <input type="hidden" name="reg_no" value="<?= htmlspecialchars($reg_no) ?>">
        <div class="table-responsive">
            <table class="table table-bordered" id="sub-mark">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>1st Term</th>
                        <th>2nd Term</th>
                        <th>3rd Term</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
<?php if (!empty($subjects_arr)): ?>
    <?php for ($i = 0; $i < count($subjects_arr); $i++): ?>
        <tr class="sub-mark-row">
            <td>
                <select class="form-select tution-month-select" name="subjects[]" required>
                    <option value="">Select Subject</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['id'] ?>" <?= ($subjects_arr[$i] == $subject['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($subject['subject']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="duplicate-error">This subject has already been selected</div>
            </td>
            <td><input type="number" class="form-control" name="1st_term[]" min="0" max="100" value="<?= htmlspecialchars($first_term_arr[$i] ?? 0) ?>" required></td>
            <td><input type="number" class="form-control" name="2nd_term[]" min="0" max="100" value="<?= htmlspecialchars($second_term_arr[$i] ?? 0) ?>"></td>
            <td><input type="number" class="form-control" name="3rd_term[]" min="0" max="100" value="<?= htmlspecialchars($third_term_arr[$i] ?? 0) ?>"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger remove-row-btn">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </td>
        </tr>
    <?php endfor; ?>
<?php else: ?>
    <?php foreach ($subjects as $subject): ?>
        <tr class="sub-mark-row">
            <td>
                <select class="form-select tution-month-select" name="subjects[]" required>
                    <option value="">Select Subject</option>
                    <?php foreach ($subjects as $sub): ?>
                        <option value="<?= $sub['id'] ?>" <?= ($sub['id'] == $subject['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($sub['subject']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <div class="duplicate-error">This subject has already been selected</div>
            </td>
            <td><input type="number" class="form-control" name="1st_term[]" min="0" max="100" value="0" required></td>
            <td><input type="number" class="form-control" name="2nd_term[]" min="0" max="100" value="0"></td>
            <td><input type="number" class="form-control" name="3rd_term[]" min="0" max="100" value="0"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger remove-row-btn">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>

                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <button type="button" id="add-row-btn" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Subject
            </button>
            <button type="submit" name="save_marks" class="btn btn-success">
                <i class="fas fa-save"></i> Save Marks
            </button>
            <a href="student_details.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#add-row-btn').click(function() {
        const newRow = `
        <tr class="sub-mark-row">
            <td>
                <select class="form-select tution-month-select" name="subjects[]" required>
                    <option value="">Select Subject</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['id'] ?>"><?= htmlspecialchars($subject['subject']) ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="duplicate-error">This subject has already been selected</div>
            </td>
            <td><input type="number" class="form-control" name="1st_term[]" min="0" max="100" value="0" required></td>
            <td><input type="number" class="form-control" name="2nd_term[]" min="0" max="100" value="0"></td>
            <td><input type="number" class="form-control" name="3rd_term[]" min="0" max="100" value="0"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger remove-row-btn">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </td>
        </tr>`;
        $('#sub-mark tbody').append(newRow);
        checkDuplicateSubjects();
    });

    $(document).on('click', '.remove-row-btn', function() {
        if ($('#sub-mark tbody tr').length > 1) {
            $(this).closest('tr').remove();
            checkDuplicateSubjects();
        } else {
            alert('At least one subject is required!');
        }
    });

    $(document).on('change', '.tution-month-select', function() {
        checkDuplicateSubjects();
    });

    function checkDuplicateSubjects() {
        const selected = [];
        let hasDup = false;
        $('.duplicate-error').hide();
        
        $('.tution-month-select').each(function() {
            const val = $(this).val();
            if (val) {
                if (selected.includes(val)) {
                    $(this).next('.duplicate-error').show();
                    hasDup = true;
                } else {
                    selected.push(val);
                }
            }
        });
        return hasDup;
    }

    $('#marks-form').submit(function(e) {
        let hasEmpty = false;
        $('.tution-month-select').each(function() {
            if (!$(this).val()) {
                hasEmpty = true;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (hasEmpty) {
            e.preventDefault();
            alert('Please select a subject for all rows.');
            return false;
        }
        
        if (checkDuplicateSubjects()) {
            e.preventDefault();
            alert('Please fix duplicate subjects before submitting.');
            return false;
        }
        return true;
    });
});
</script>
</body>
</html>
