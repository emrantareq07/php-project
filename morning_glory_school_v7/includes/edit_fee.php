<?php
session_start();
require_once("../config/config.php");
require_once("../db/db.php");

if(!isset($_SESSION["uid"]) && !isset($_COOKIE['user_login'])) {
    header("Location: index.php");
    exit();
}

// Get fee record ID from URL
$id = $_GET['id'] ?? 0;
if(empty($id)) {
    die("Invalid fee record ID");
}

// Database connection
$conn = new PDO("mysql:host=localhost;dbname=morning_glory_db", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Fetch the fee record
$stmt = $conn->prepare("SELECT f.*, s.std_name, s.fathers_name 
                       FROM fee_mgtm_tbl f 
                       JOIN std_tbl s ON f.reg_no = s.reg_no 
                       WHERE f.id = ?");
$stmt->execute([$id]);
$fee = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$fee) {
    die("Fee record not found");
}

// Process form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Get form data
        $tution_months = implode(',', $_POST['months'] ?? []);
        $tution_fee_actual = implode(',', $_POST['tution_fee_actual'] ?? []);
        $tution_fee_pay = implode(',', $_POST['tution_fee_pay'] ?? []);
        $tution_fee_remaining_due = implode(',', $_POST['tution_fee_remaining_due'] ?? []);
        
        $exam_terms = implode(',', $_POST['exam_terms'] ?? []);
        $exam_fee_actual = implode(',', $_POST['exam_fee_actual'] ?? []);
        $exam_fee_pay = implode(',', $_POST['exam_fee_pay'] ?? []);
        $exam_fee_remaining_due = implode(',', $_POST['exam_fee_remaining_due'] ?? []);

        // Process other fees
        $fee_fields = [
            'admission_fee', 'board_reg_fee', 'scholarship_fee', 
            'transfer_fee', 'late_fee', 'annual_session_fee',
            'books', 'khata', 'diary', 'stationary_printing', 
            'poor_fund', 'others'
        ];

        $totals = [
            'actual' => 0,
            'pay' => 0,
            'due' => 0
        ];

        foreach($fee_fields as $field) {
            ${$field.'_actual'} = $_POST[$field.'_actual'] ?? 0;
            ${$field.'_pay'} = $_POST[$field.'_pay'] ?? 0;
            ${$field.'_due'} = max(0, ${$field.'_actual'} - ${$field.'_pay'});
            
            $totals['actual'] += ${$field.'_actual'};
            $totals['pay'] += ${$field.'_pay'};
            $totals['due'] += ${$field.'_due'};
        }

        // Calculate totals for tuition and exam fees
        $tution_total_actual = array_sum($_POST['tution_fee_actual'] ?? []);
        $tution_total_pay = array_sum($_POST['tution_fee_pay'] ?? []);
        $tution_total_remaining_due = array_sum($_POST['tution_fee_remaining_due'] ?? []);

        $exam_total_actual = array_sum($_POST['exam_fee_actual'] ?? []);
        $exam_total_pay = array_sum($_POST['exam_fee_pay'] ?? []);
        $exam_total_remaining_due = array_sum($_POST['exam_fee_remaining_due'] ?? []);

        // Add to totals
        $totals['actual'] += $tution_total_actual + $exam_total_actual;
        $totals['pay'] += $tution_total_pay + $exam_total_pay;
        $totals['due'] += $tution_total_remaining_due + $exam_total_remaining_due;

        // Update record
        $sql = "UPDATE fee_mgtm_tbl SET
                tution_months = :tution_months,
                tution_fee_actual = :tution_fee_actual,
                tution_fee_pay = :tution_fee_pay,
                tution_fee_remaining_due = :tution_fee_remaining_due,
                tution_total_actual = :tution_total_actual,
                tution_total_pay = :tution_total_pay,
                tution_total_remaining_due = :tution_total_remaining_due,
                exam_terms = :exam_terms,
                exam_fee_actual = :exam_fee_actual,
                exam_fee_pay = :exam_fee_pay,
                exam_fee_remaining_due = :exam_fee_remaining_due,
                exam_total_actual = :exam_total_actual,
                exam_total_pay = :exam_total_pay,
                exam_total_remaining_due = :exam_total_remaining_due,
                admission_fee_actual = :admission_fee_actual,
                admission_fee_pay = :admission_fee_pay,
                admission_fee_due = :admission_fee_due,
                board_reg_fee_actual = :board_reg_fee_actual,
                board_reg_fee_pay = :board_reg_fee_pay,
                board_reg_fee_due = :board_reg_fee_due,
                scholarship_fee_actual = :scholarship_fee_actual,
                scholarship_fee_pay = :scholarship_fee_pay,
                scholarship_fee_due = :scholarship_fee_due,
                transfer_fee_actual = :transfer_fee_actual,
                transfer_fee_pay = :transfer_fee_pay,
                transfer_fee_due = :transfer_fee_due,
                late_fee_actual = :late_fee_actual,
                late_fee_pay = :late_fee_pay,
                late_fee_due = :late_fee_due,
                annual_session_fee_actual = :annual_session_fee_actual,
                annual_session_fee_pay = :annual_session_fee_pay,
                annual_session_fee_due = :annual_session_fee_due,
                books_actual = :books_actual,
                books_pay = :books_pay,
                books_due = :books_due,
                khata_actual = :khata_actual,
                khata_pay = :khata_pay,
                khata_due = :khata_due,
                diary_actual = :diary_actual,
                diary_pay = :diary_pay,
                diary_due = :diary_due,
                stationary_printing_actual = :stationary_printing_actual,
                stationary_printing_pay = :stationary_printing_pay,
                stationary_printing_due = :stationary_printing_due,
                poor_fund_actual = :poor_fund_actual,
                poor_fund_pay = :poor_fund_pay,
                poor_fund_due = :poor_fund_due,
                others_actual = :others_actual,
                others_pay = :others_pay,
                others_due = :others_due,
                total_actual = :total_actual,
                total_pay = :total_pay,
                total_due = :total_due,
                updated_at = NOW()
                WHERE id = :id";

        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':tution_months', $tution_months);
        $stmt->bindParam(':tution_fee_actual', $tution_fee_actual);
        $stmt->bindParam(':tution_fee_pay', $tution_fee_pay);
        $stmt->bindParam(':tution_fee_remaining_due', $tution_fee_remaining_due);
        $stmt->bindParam(':tution_total_actual', $tution_total_actual);
        $stmt->bindParam(':tution_total_pay', $tution_total_pay);
        $stmt->bindParam(':tution_total_remaining_due', $tution_total_remaining_due);
        
        $stmt->bindParam(':exam_terms', $exam_terms);
        $stmt->bindParam(':exam_fee_actual', $exam_fee_actual);
        $stmt->bindParam(':exam_fee_pay', $exam_fee_pay);
        $stmt->bindParam(':exam_fee_remaining_due', $exam_fee_remaining_due);
        $stmt->bindParam(':exam_total_actual', $exam_total_actual);
        $stmt->bindParam(':exam_total_pay', $exam_total_pay);
        $stmt->bindParam(':exam_total_remaining_due', $exam_total_remaining_due);
        
        foreach($fee_fields as $field) {
            $stmt->bindParam(':'.$field.'_actual', ${$field.'_actual'});
            $stmt->bindParam(':'.$field.'_pay', ${$field.'_pay'});
            $stmt->bindParam(':'.$field.'_due', ${$field.'_due'});
        }
        
        $stmt->bindParam(':total_actual', $totals['actual']);
        $stmt->bindParam(':total_pay', $totals['pay']);
        $stmt->bindParam(':total_due', $totals['due']);
        $stmt->bindParam(':id', $id);
        
        $stmt->execute();

        $_SESSION['success'] = "Fee record updated successfully!";
        header("Location: fees_details.php?reg_no=" . $fee['reg_no']);
        exit();

    } catch(PDOException $e) {
        $error = "Error updating record: " . $e->getMessage();
    }
}

// Prepare data for form
$months = !empty($fee['tution_months']) ? explode(',', $fee['tution_months']) : [];
$tution_fee_actual = !empty($fee['tution_fee_actual']) ? explode(',', $fee['tution_fee_actual']) : [];
$tution_fee_pay = !empty($fee['tution_fee_pay']) ? explode(',', $fee['tution_fee_pay']) : [];
$tution_fee_remaining_due = !empty($fee['tution_fee_remaining_due']) ? explode(',', $fee['tution_fee_remaining_due']) : [];

$exam_terms = !empty($fee['exam_terms']) ? explode(',', $fee['exam_terms']) : [];
$exam_fee_actual = !empty($fee['exam_fee_actual']) ? explode(',', $fee['exam_fee_actual']) : [];
$exam_fee_pay = !empty($fee['exam_fee_pay']) ? explode(',', $fee['exam_fee_pay']) : [];
$exam_fee_remaining_due = !empty($fee['exam_fee_remaining_due']) ? explode(',', $fee['exam_fee_remaining_due']) : [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Fee Record - <?= htmlspecialchars($fee['std_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .fee-section {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .fee-section h5 {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .total-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .form-label {
            font-weight: 500;
        }
        .card-header {
            font-weight: bold;
        }
        .duplicate-error {
            color: red;
            font-size: 12px;
            display: none;
        }
        .remove-row-btn {
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="card">
        <div class="card-header text-uppercase bg-primary text-white">
            <i class="fa fa-edit"></i> Edit Fee Record - <?= htmlspecialchars($fee['std_name']) ?>
        </div>
        <div class="card-body">
            <?php if(isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <form method="post" action="" id="feeForm">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="reg_no" class="form-label">Registration No *</label>
                        <input type="text" class="form-control" name="reg_no" value="<?= htmlspecialchars($fee['reg_no']) ?>" readonly>
                    </div>
                    <div class="col-md-3">
                        <label for="name" class="form-label">Student Name *</label>
                        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($fee['std_name']) ?>" readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="class" class="form-label">Class *</label>
                        <input type="text" class="form-control" name="class" value="<?= htmlspecialchars($fee['class']) ?>" readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="roll_no" class="form-label">Roll No *</label>
                        <input type="text" class="form-control" name="roll_no" value="<?= htmlspecialchars($fee['roll_no']) ?>" readonly>
                    </div>
                    <div class="col-md-2">
                        <label for="section" class="form-label">Section *</label>
                        <input type="text" class="form-control" name="section" value="<?= htmlspecialchars($fee['section']) ?>" readonly>
                    </div>
                </div>

                <div class="fee-section">
                    <h5><i class="fas fa-user-graduate"></i> Admission Details</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Admission Fee</label>
                            <input type="number" class="form-control" name="admission_fee_actual" step="0.01" min="0" value="<?= $fee['admission_fee_actual'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="admission_fee_due" step="0.01" min="0" value="<?= $fee['admission_fee_due'] ?? 0 ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="admission_fee_pay" step="0.01" min="0" value="<?= $fee['admission_fee_pay'] ?? 0 ?>">
                        </div>
                    </div>
                </div>

                <div class="fee-section">
                    <h5><i class="fas fa-book"></i> Tuition Fees</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tution_fees_table">
                            <thead>
                                <tr>
                                    <td colspan="5" class="text-end">
                                        <button type="button" id="add_tution_fee" class="btn btn-success btn-sm">
                                            <i class="fa fa-plus"></i> Add Month
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Month</th>
                                    <th>Actual Fee</th>
                                    <th>Pay</th>
                                    <th>Remaining Due</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tution_fees_body">
                                <?php foreach($months as $i => $month): ?>
                                <tr class="tution-fee-row">
                                    <td>
                                        <select class="form-select tution-month-select" name="months[]" required>
                                            <option value="">Select Month</option>
                                            <option value="January" <?= $month == 'January' ? 'selected' : '' ?>>January</option>
                                            <option value="February" <?= $month == 'February' ? 'selected' : '' ?>>February</option>
                                            <option value="March" <?= $month == 'March' ? 'selected' : '' ?>>March</option>
                                            <option value="April" <?= $month == 'April' ? 'selected' : '' ?>>April</option>
                                            <option value="May" <?= $month == 'May' ? 'selected' : '' ?>>May</option>
                                            <option value="June" <?= $month == 'June' ? 'selected' : '' ?>>June</option>
                                            <option value="July" <?= $month == 'July' ? 'selected' : '' ?>>July</option>
                                            <option value="August" <?= $month == 'August' ? 'selected' : '' ?>>August</option>
                                            <option value="September" <?= $month == 'September' ? 'selected' : '' ?>>September</option>
                                            <option value="October" <?= $month == 'October' ? 'selected' : '' ?>>October</option>
                                            <option value="November" <?= $month == 'November' ? 'selected' : '' ?>>November</option>
                                            <option value="December" <?= $month == 'December' ? 'selected' : '' ?>>December</option>
                                        </select>
                                        <div class="duplicate-error">This month has already been selected</div>
                                    </td>
                                    <td><input type="number" class="form-control tution-actual-input" name="tution_fee_actual[]" step="0.01" min="0" value="<?= $tution_fee_actual[$i] ?? 0 ?>" required></td>
                                    <td><input type="number" class="form-control tution-payment-input" name="tution_fee_pay[]" step="0.01" min="0" value="<?= $tution_fee_pay[$i] ?? 0 ?>" required></td>
                                    <td><input type="number" class="form-control tution-remaining-due-input" name="tution_fee_remaining_due[]" step="0.01" min="0" value="<?= $tution_fee_remaining_due[$i] ?? 0 ?>" readonly></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-tution-row">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="fee-section">
                    <h5><i class="fas fa-file-alt"></i> Exam Fees</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered" id="exam_fees_table">
                            <thead>
                                <tr>
                                    <td colspan="5" class="text-end">
                                        <button type="button" id="add_exam_fee" class="btn btn-success btn-sm">
                                            <i class="fa fa-plus"></i> Add Exam Fee
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Exam Term</th>
                                    <th>Actual Fee</th>
                                    <th>Pay</th>
                                    <th>Remaining Due</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="exam_fees_body">
                                <?php foreach($exam_terms as $i => $term): ?>
                                <tr class="exam-fee-row">
                                    <td>
                                        <select class="form-select exam-term-select" name="exam_terms[]">
                                            <option value="">Select Term</option>
                                            <option value="1st Term" <?= $term == '1st Term' ? 'selected' : '' ?>>1st Term</option>
                                            <option value="2nd Term" <?= $term == '2nd Term' ? 'selected' : '' ?>>2nd Term</option>
                                            <option value="3rd Term" <?= $term == '3rd Term' ? 'selected' : '' ?>>3rd Term</option>
                                        </select>
                                        <div class="duplicate-error">This term has already been selected</div>
                                    </td>
                                    <td><input type="number" class="form-control exam-actual-input" name="exam_fee_actual[]" step="0.01" min="0" value="<?= $exam_fee_actual[$i] ?? 0 ?>"></td>
                                    <td><input type="number" class="form-control exam-payment-input" name="exam_fee_pay[]" step="0.01" min="0" value="<?= $exam_fee_pay[$i] ?? 0 ?>"></td>
                                    <td><input type="number" class="form-control exam-remaining-due-input" name="exam_fee_remaining_due[]" step="0.01" min="0" value="<?= $exam_fee_remaining_due[$i] ?? 0 ?>" readonly></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-exam-row">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="fee-section">
                    <h5><i class="fas fa-file-alt"></i> Registration & Other Fees</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Board Registration Fee</label>
                            <input type="number" class="form-control" name="board_reg_fee_actual" step="0.01" min="0" value="<?= $fee['board_reg_fee_actual'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="board_reg_fee_due" step="0.01" min="0" value="<?= $fee['board_reg_fee_due'] ?? 0 ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="board_reg_fee_pay" step="0.01" min="0" value="<?= $fee['board_reg_fee_pay'] ?? 0 ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Scholarship Fee</label>
                            <input type="number" class="form-control" name="scholarship_fee_actual" step="0.01" min="0" value="<?= $fee['scholarship_fee_actual'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="scholarship_fee_due" step="0.01" min="0" value="<?= $fee['scholarship_fee_due'] ?? 0 ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="scholarship_fee_pay" step="0.01" min="0" value="<?= $fee['scholarship_fee_pay'] ?? 0 ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Transfer Fee</label>
                            <input type="number" class="form-control" name="transfer_fee_actual" step="0.01" min="0" value="<?= $fee['transfer_fee_actual'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="transfer_fee_due" step="0.01" min="0" value="<?= $fee['transfer_fee_due'] ?? 0 ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="transfer_fee_pay" step="0.01" min="0" value="<?= $fee['transfer_fee_pay'] ?? 0 ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Late Fee</label>
                            <input type="number" class="form-control" name="late_fee_actual" step="0.01" min="0" value="<?= $fee['late_fee_actual'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="late_fee_due" step="0.01" min="0" value="<?= $fee['late_fee_due'] ?? 0 ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="late_fee_pay" step="0.01" min="0" value="<?= $fee['late_fee_pay'] ?? 0 ?>">
                        </div>
                    </div>
                </div>

                <div class="fee-section">
                    <h5><i class="fas fa-calendar-alt"></i> Annual Fees</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Annual Session Fee</label>
                            <input type="number" class="form-control" name="annual_session_fee_actual" step="0.01" min="0" value="<?= $fee['annual_session_fee_actual'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="annual_session_fee_due" step="0.01" min="0" value="<?= $fee['annual_session_fee_due'] ?? 0 ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="annual_session_fee_pay" step="0.01" min="0" value="<?= $fee['annual_session_fee_pay'] ?? 0 ?>">
                        </div>
                    </div>
                </div>

                <div class="fee-section">
                    <h5><i class="fas fa-book-open"></i> Books & Stationary</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Books</label>
                            <input type="number" class="form-control" name="books_actual" step="0.01" min="0" value="<?= $fee['books_actual'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="books_due" step="0.01" min="0" value="<?= $fee['books_due'] ?? 0 ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="books_pay" step="0.01" min="0" value="<?= $fee['books_pay'] ?? 0 ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Khata</label>
                            <input type="number" class="form-control" name="khata_actual" step="0.01" min="0" value="<?= $fee['khata_actual'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="khata_due" step="0.01" min="0" value="<?= $fee['khata_due'] ?? 0 ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="khata_pay" step="0.01" min="0" value="<?= $fee['khata_pay'] ?? 0 ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Diary</label>
                            <input type="number" class="form-control" name="diary_actual" step="0.01" min="0" value="<?= $fee['diary_actual'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="diary_due" step="0.01" min="0" value="<?= $fee['diary_due'] ?? 0 ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="diary_pay" step="0.01" min="0" value="<?= $fee['diary_pay'] ?? 0 ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Stationary & Printing</label>
                            <input type="number" class="form-control" name="stationary_printing_actual" step="0.01" min="0" value="<?= $fee['stationary_printing_actual'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="stationary_printing_due" step="0.01" min="0" value="<?= $fee['stationary_printing_due'] ?? 0 ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="stationary_printing_pay" step="0.01" min="0" value="<?= $fee['stationary_printing_pay'] ?? 0 ?>">
                        </div>
                    </div>
                </div>

                <div class="fee-section">
                    <h5><i class="fas fa-hand-holding-heart"></i> Other Fees</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Poor Fund</label>
                            <input type="number" class="form-control" name="poor_fund_actual" step="0.01" min="0" value="<?= $fee['poor_fund_actual'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="poor_fund_due" step="0.01" min="0" value="<?= $fee['poor_fund_due'] ?? 0 ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="poor_fund_pay" step="0.01" min="0" value="<?= $fee['poor_fund_pay'] ?? 0 ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Others</label>
                            <input type="number" class="form-control" name="others_actual" step="0.01" min="0" value="<?= $fee['others_actual'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="others_due" step="0.01" min="0" value="<?= $fee['others_due'] ?? 0 ?>" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="others_pay" step="0.01" min="0" value="<?= $fee['others_pay'] ?? 0 ?>">
                        </div>
                    </div>
                </div>

                <div class="total-section">
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Total Actual Fee: <span id="total_actual_display"><?= number_format($fee['total_actual'], 2) ?></span></h5>
                        </div>
                        <div class="col-md-4">
                            <h5>Total Pay: <span id="total_pay_display"><?= number_format($fee['total_pay'], 2) ?></span></h5>
                        </div>
                        <div class="col-md-4">
                            <h5>Total Remaining Due: <span id="total_remaining_due_display"><?= number_format($fee['total_due'], 2) ?></span></h5>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Update Fee Record
                    </button>
                    <a href="fees_details.php?reg_no=<?= $fee['reg_no'] ?>" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Add tuition fee row
    $('#add_tution_fee').click(function() {
        const newRow = `
        <tr class="tution-fee-row">
            <td>
                <select class="form-select tution-month-select" name="months[]" required>
                    <option value="">Select Month</option>
                    <option value="January">January</option>
                    <option value="February">February</option>
                    <option value="March">March</option>
                    <option value="April">April</option>
                    <option value="May">May</option>
                    <option value="June">June</option>
                    <option value="July">July</option>
                    <option value="August">August</option>
                    <option value="September">September</option>
                    <option value="October">October</option>
                    <option value="November">November</option>
                    <option value="December">December</option>
                </select>
                <div class="duplicate-error">This month has already been selected</div>
            </td>
            <td><input type="number" class="form-control tution-actual-input" name="tution_fee_actual[]" step="0.01" min="0" value="0" required></td>
            <td><input type="number" class="form-control tution-payment-input" name="tution_fee_pay[]" step="0.01" min="0" value="0" required></td>
            <td><input type="number" class="form-control tution-remaining-due-input" name="tution_fee_remaining_due[]" step="0.01" min="0" value="0" readonly></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-tution-row">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
        `;
        $('#tution_fees_body').append(newRow);
    });

    // Add exam fee row
    $('#add_exam_fee').click(function() {
        const newRow = `
        <tr class="exam-fee-row">
            <td>
                <select class="form-select exam-term-select" name="exam_terms[]">
                    <option value="">Select Term</option>
                    <option value="1st Term">1st Term</option>
                    <option value="2nd Term">2nd Term</option>
                    <option value="3rd Term">3rd Term</option>
                </select>
                <div class="duplicate-error">This term has already been selected</div>
            </td>
            <td><input type="number" class="form-control exam-actual-input" name="exam_fee_actual[]" step="0.01" min="0" value="0"></td>
            <td><input type="number" class="form-control exam-payment-input" name="exam_fee_pay[]" step="0.01" min="0" value="0"></td>
            <td><input type="number" class="form-control exam-remaining-due-input" name="exam_fee_remaining_due[]" step="0.01" min="0" value="0" readonly></td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm remove-exam-row">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        </tr>
        `;
        $('#exam_fees_body').append(newRow);
    });

    // Remove tuition fee row
    $(document).on('click', '.remove-tution-row', function() {
        if($('.tution-fee-row').length > 1) {
            $(this).closest('tr').remove();
            calculateTotals();
        }
    });

    // Remove exam fee row
    $(document).on('click', '.remove-exam-row', function() {
        if($('.exam-fee-row').length > 1) {
            $(this).closest('tr').remove();
            calculateTotals();
        }
    });

    // Calculate due when payment changes for all fee types
    $('.payment-input').on('input', function() {
        const fieldPrefix = $(this).attr('name').replace('_pay', '');
        const actual = parseFloat($(`input[name="${fieldPrefix}_actual"]`).val()) || 0;
        const pay = parseFloat($(this).val()) || 0;
        const due = Math.max(0, actual - pay);
        
        $(`input[name="${fieldPrefix}_due"]`).val(due.toFixed(2));
        calculateTotals();
    });

    // Calculate due when actual fee changes for all fee types
    $('input[name$="_actual"]').on('input', function() {
        const fieldPrefix = $(this).attr('name').replace('_actual', '');
        const actual = parseFloat($(this).val()) || 0;
        const pay = parseFloat($(`input[name="${fieldPrefix}_pay"]`).val()) || 0;
        const due = Math.max(0, actual - pay);
        
        $(`input[name="${fieldPrefix}_due"]`).val(due.toFixed(2));
        calculateTotals();
    });

    // Calculate remaining due for tuition fees
    $(document).on('input', '.tution-actual-input, .tution-payment-input', function() {
        const row = $(this).closest('.tution-fee-row');
        const actual = parseFloat(row.find('.tution-actual-input').val()) || 0;
        const pay = parseFloat(row.find('.tution-payment-input').val()) || 0;
        const remainingDue = Math.max(0, actual - pay);
        
        row.find('.tution-remaining-due-input').val(remainingDue.toFixed(2));
        calculateTotals();
    });

    // Calculate remaining due for exam fees
    $(document).on('input', '.exam-actual-input, .exam-payment-input', function() {
        const row = $(this).closest('.exam-fee-row');
        const actual = parseFloat(row.find('.exam-actual-input').val()) || 0;
        const pay = parseFloat(row.find('.exam-payment-input').val()) || 0;
        const remainingDue = Math.max(0, actual - pay);
        
        row.find('.exam-remaining-due-input').val(remainingDue.toFixed(2));
        calculateTotals();
    });

    // Check for duplicate months/terms
    $(document).on('change', '.tution-month-select, .exam-term-select', function() {
        const isMonth = $(this).hasClass('tution-month-select');
        const selector = isMonth ? '.tution-month-select' : '.exam-term-select';
        const currentVal = $(this).val();
        let duplicates = 0;
        
        $(selector).each(function() {
            if($(this).val() === currentVal && currentVal !== '') {
                duplicates++;
            }
        });
        
        if(duplicates > 1) {
            $(this).next('.duplicate-error').show();
        } else {
            $(this).next('.duplicate-error').hide();
        }
    });

    // Calculate totals
    function calculateTotals() {
        let totalActual = 0;
        let totalPay = 0;
        let totalDue = 0;
        
        // Calculate tuition fees totals
        $('.tution-fee-row').each(function() {
            totalActual += parseFloat($(this).find('.tution-actual-input').val()) || 0;
            totalPay += parseFloat($(this).find('.tution-payment-input').val()) || 0;
            totalDue += parseFloat($(this).find('.tution-remaining-due-input').val()) || 0;
        });
        
        // Calculate exam fees totals
        $('.exam-fee-row').each(function() {
            totalActual += parseFloat($(this).find('.exam-actual-input').val()) || 0;
            totalPay += parseFloat($(this).find('.exam-payment-input').val()) || 0;
            totalDue += parseFloat($(this).find('.exam-remaining-due-input').val()) || 0;
        });
        
        // Calculate other fees totals
        $('input[name$="_actual"]').not('.tution-actual-input, .exam-actual-input').each(function() {
            totalActual += parseFloat($(this).val()) || 0;
        });
        
        $('.payment-input').not('.tution-payment-input, .exam-payment-input').each(function() {
            totalPay += parseFloat($(this).val()) || 0;
        });
        
        $('input[name$="_due"]').not('.tution-remaining-due-input, .exam-remaining-due-input').each(function() {
            totalDue += parseFloat($(this).val()) || 0;
        });
        
        // Update display
        $('#total_actual_display').text(totalActual.toFixed(2));
        $('#total_pay_display').text(totalPay.toFixed(2));
        $('#total_remaining_due_display').text(totalDue.toFixed(2));
    }

    // Form submission validation
    $('#feeForm').on('submit', function(e) {
        // Check for duplicate months in tuition fees
        let tutionMonths = [];
        let hasDuplicateMonths = false;
        
        $('.tution-month-select').each(function() {
            const month = $(this).val();
            if(month) {
                if(tutionMonths.includes(month)) {
                    $(this).next('.duplicate-error').show();
                    hasDuplicateMonths = true;
                } else {
                    tutionMonths.push(month);
                }
            }
        });
        
        // Check for duplicate terms in exam fees
        let examTerms = [];
        let hasDuplicateTerms = false;
        
        $('.exam-term-select').each(function() {
            const term = $(this).val();
            if(term) {
                if(examTerms.includes(term)) {
                    $(this).next('.duplicate-error').show();
                    hasDuplicateTerms = true;
                } else {
                    examTerms.push(term);
                }
            }
        });
        
        if(hasDuplicateMonths || hasDuplicateTerms) {
            e.preventDefault();
            alert('Please fix duplicate months or terms before submitting.');
            return false;
        }
        
        // Validate at least one payment is made
        let totalPay = 0;
        $('.payment-input, .tution-payment-input, .exam-payment-input').each(function() {
            totalPay += parseFloat($(this).val()) || 0;
        });
        
        if(totalPay <= 0) {
            e.preventDefault();
            alert('Please enter at least one payment amount.');
            return false;
        }
        
        return true;
    });

    // Initialize calculations
    calculateTotals();
});
</script>
</body>
</html>