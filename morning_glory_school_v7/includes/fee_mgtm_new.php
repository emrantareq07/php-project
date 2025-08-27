<?php
session_start();  
require_once("../config/config.php");
require_once("../db/db.php");

if(isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
    include_once(ROOT_PATH.'/libs/function.php');
    $usercredentials = new DB_con();

    // Fetching username from either session or cookies
    $uname = "";
    if (isset($_SESSION["uname"])) {
        $uname = $_SESSION['uname'];
    }
    if (isset($_COOKIE['user_login'])) {
        $uname = $_COOKIE['user_login'];
    } 

    $query = "SELECT * FROM tblusers WHERE Username='$uname'";
    $result = $usercredentials->runBaseQuery($query);
    foreach ($result as $k => $v) {
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
    }

    // Get student details based on registration number
    $reg_no = $_GET['reg_no'] ?? '';
    $student = null;

    if ($reg_no) {
        $stmt = $conn->prepare("SELECT * FROM std_tbl WHERE reg_no = ?");
        $stmt->bind_param("s", $reg_no);
        $stmt->execute();
        $result = $stmt->get_result();
        $student = $result->fetch_assoc();
        $stmt->close();
    }

    if (!$student) {
        die("Student not found!");
    }

    // Database connection for fee management
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "morning_glory_db";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $entry_date = date('Y-m-d H:i:s');
            $reg_no = $_POST['reg_no'] ?? '';
            $name = $_POST['name'] ?? '';
            $class = $_POST['class'] ?? '';
            $roll_no = $_POST['roll_no'] ?? '';
            $section = $_POST['section'] ?? '';
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');

            // Process tuition fees (comma-separated values)
            $tution_months = implode(',', $_POST['months'] ?? []);
            $tution_fee_actual = implode(',', $_POST['tution_fee_actual'] ?? []);
            $tution_fee_pay = implode(',', $_POST['tution_fee_pay'] ?? []);
            $tution_fee_remaining_due = implode(',', $_POST['tution_fee_remaining_due'] ?? []);

            // Process exam fees (comma-separated values)
            $exam_terms = implode(',', $_POST['exam_terms'] ?? []);
            $exam_fee_actual = implode(',', $_POST['exam_fee_actual'] ?? []);
            $exam_fee_pay = implode(',', $_POST['exam_fee_pay'] ?? []);
            $exam_fee_remaining_due = implode(',', $_POST['exam_fee_remaining_due'] ?? []);

            // Process all other fees
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

            // Calculate totals for all fees
            foreach ($fee_fields as $field) {
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

            // Add tuition and exam fees to totals
            $totals['actual'] += $tution_total_actual + $exam_total_actual;
            $totals['pay'] += $tution_total_pay + $exam_total_pay;
            $totals['due'] += $tution_total_remaining_due + $exam_total_remaining_due;

            // Insert into database
            $sql = "INSERT INTO fee_mgtm_tbl (
                entry_date, reg_no, name, class, roll_no, section,
                tution_months, tution_fee_actual, tution_fee_pay, tution_fee_remaining_due,
                tution_total_actual, tution_total_pay, tution_total_remaining_due,
                exam_terms, exam_fee_actual, exam_fee_pay, exam_fee_remaining_due,
                exam_total_actual, exam_total_pay, exam_total_remaining_due,
                admission_fee_actual, admission_fee_pay, admission_fee_due,
                board_reg_fee_actual, board_reg_fee_pay, board_reg_fee_due,
                scholarship_fee_actual, scholarship_fee_pay, scholarship_fee_due,
                transfer_fee_actual, transfer_fee_pay, transfer_fee_due,
                late_fee_actual, late_fee_pay, late_fee_due,
                annual_session_fee_actual, annual_session_fee_pay, annual_session_fee_due,
                books_actual, books_pay, books_due,
                khata_actual, khata_pay, khata_due,
                diary_actual, diary_pay, diary_due,
                stationary_printing_actual, stationary_printing_pay, stationary_printing_due,
                poor_fund_actual, poor_fund_pay, poor_fund_due,
                others_actual, others_pay, others_due,
                total_actual, total_pay, total_due,
                created_at, updated_at
            ) VALUES (
                :entry_date, :reg_no, :name, :class, :roll_no, :section,
                :tution_months, :tution_fee_actual, :tution_fee_pay, :tution_fee_remaining_due,
                :tution_total_actual, :tution_total_pay, :tution_total_remaining_due,
                :exam_terms, :exam_fee_actual, :exam_fee_pay, :exam_fee_remaining_due,
                :exam_total_actual, :exam_total_pay, :exam_total_remaining_due,
                :admission_fee_actual, :admission_fee_pay, :admission_fee_due,
                :board_reg_fee_actual, :board_reg_fee_pay, :board_reg_fee_due,
                :scholarship_fee_actual, :scholarship_fee_pay, :scholarship_fee_due,
                :transfer_fee_actual, :transfer_fee_pay, :transfer_fee_due,
                :late_fee_actual, :late_fee_pay, :late_fee_due,
                :annual_session_fee_actual, :annual_session_fee_pay, :annual_session_fee_due,
                :books_actual, :books_pay, :books_due,
                :khata_actual, :khata_pay, :khata_due,
                :diary_actual, :diary_pay, :diary_due,
                :stationary_printing_actual, :stationary_printing_pay, :stationary_printing_due,
                :poor_fund_actual, :poor_fund_pay, :poor_fund_due,
                :others_actual, :others_pay, :others_due,
                :total_actual, :total_pay, :total_due,
                :created_at, :updated_at
            )";

            $stmt = $conn->prepare($sql);
            
            // Bind parameters
            $stmt->bindParam(':entry_date', $entry_date);
            $stmt->bindParam(':reg_no', $reg_no);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':class', $class);
            $stmt->bindParam(':roll_no', $roll_no);
            $stmt->bindParam(':section', $section);
            
            // Tuition fees
            $stmt->bindParam(':tution_months', $tution_months);
            $stmt->bindParam(':tution_fee_actual', $tution_fee_actual);
            $stmt->bindParam(':tution_fee_pay', $tution_fee_pay);
            $stmt->bindParam(':tution_fee_remaining_due', $tution_fee_remaining_due);
            $stmt->bindParam(':tution_total_actual', $tution_total_actual);
            $stmt->bindParam(':tution_total_pay', $tution_total_pay);
            $stmt->bindParam(':tution_total_remaining_due', $tution_total_remaining_due);
            
            // Exam fees
            $stmt->bindParam(':exam_terms', $exam_terms);
            $stmt->bindParam(':exam_fee_actual', $exam_fee_actual);
            $stmt->bindParam(':exam_fee_pay', $exam_fee_pay);
            $stmt->bindParam(':exam_fee_remaining_due', $exam_fee_remaining_due);
            $stmt->bindParam(':exam_total_actual', $exam_total_actual);
            $stmt->bindParam(':exam_total_pay', $exam_total_pay);
            $stmt->bindParam(':exam_total_remaining_due', $exam_total_remaining_due);
            
            // Other fees
            foreach ($fee_fields as $field) {
                $stmt->bindParam(':'.$field.'_actual', ${$field.'_actual'});
                $stmt->bindParam(':'.$field.'_pay', ${$field.'_pay'});
                $stmt->bindParam(':'.$field.'_due', ${$field.'_due'});
            }
            
            // Totals
            $stmt->bindParam(':total_actual', $totals['actual']);
            $stmt->bindParam(':total_pay', $totals['pay']);
            $stmt->bindParam(':total_due', $totals['due']);
            
            $stmt->bindParam(':created_at', $created_at);
            $stmt->bindParam(':updated_at', $updated_at);
            
            $stmt->execute();

            echo "<div class='alert alert-success text-center mt-2'>Fee record saved successfully!</div>";
        }
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger text-center mt-2'>Error: " . $e->getMessage() . "</div>";
    }

    $conn = null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>New Morning Glory - Fee Management</title>
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
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="card">
        <div class="card-header text-uppercase bg-primary text-white">
            <i class="fa fa-money-bill"></i> Student Fee Collection
        </div>
        <div class="card-body">
            <form method="post" action="" id="feeForm">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="reg_no" class="form-label">Registration Number *</label>
                        <input type="text" class="form-control" name="reg_no" value="<?= htmlspecialchars($student['reg_no']) ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="name" class="form-label">Student Name *</label>
                        <input type="text" class="form-control" name="name" value="<?= htmlspecialchars($student['std_name']) ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label for="class" class="form-label">Class *</label>
                        <input type="text" class="form-control" name="class" value="<?= htmlspecialchars($student['class']) ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label for="roll_no" class="form-label">Roll Number *</label>
                        <input type="text" class="form-control" name="roll_no" value="<?= htmlspecialchars($student['roll_no']) ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label for="section" class="form-label">Section *</label>
                        <input type="text" class="form-control" name="section" value="<?= htmlspecialchars($student['section']) ?>" required>
                    </div>
                </div>

                 <div class="fee-section">
                    <h5><i class="fas fa-user-graduate"></i> Admission Details</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Admission Fee</label>
                            <input type="number" class="form-control" name="admission_fee_actual" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="admission_fee_due" step="0.01" min="0" value="0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="admission_fee_pay" step="0.01" min="0" value="0">
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
                                        <button type="button" class="btn btn-danger btn-sm remove-tution-row" disabled>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
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
                                <tr class="exam-fee-row">
                                    <td>
                                        <select class="form-select exam-term-select" name="exam_terms[]" >
                                            <option value="">Select Term</option>
                                            <option value="1st Term">1st Term</option>
                                            <option value="2nd Term">2nd Term</option>
                                            <option value="3rd Term">3rd Term</option>
                                        </select>
                                        <div class="duplicate-error">This term has already been selected</div>
                                    </td>
                                    <td><input type="number" class="form-control exam-actual-input" name="exam_fee_actual[]" step="0.01" min="0" value="0" ></td>
                                    <td><input type="number" class="form-control exam-payment-input" name="exam_fee_pay[]" step="0.01" min="0" value="0" ></td>
                                    <td><input type="number" class="form-control exam-remaining-due-input" name="exam_fee_remaining_due[]" step="0.01" min="0" value="0" readonly></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-exam-row" disabled>
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="fee-section">
                    <h5><i class="fas fa-file-alt"></i> Registration & Other Fees</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Board Registration Fee</label>
                            <input type="number" class="form-control" name="board_reg_fee_actual" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="board_reg_fee_due" step="0.01" min="0" value="0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="board_reg_fee_pay" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Scholarship Fee</label>
                            <input type="number" class="form-control" name="scholarship_fee_actual" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="scholarship_fee_due" step="0.01" min="0" value="0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="scholarship_fee_pay" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Transfer Fee</label>
                            <input type="number" class="form-control" name="transfer_fee_actual" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="transfer_fee_due" step="0.01" min="0" value="0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="transfer_fee_pay" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Late Fee</label>
                            <input type="number" class="form-control" name="late_fee_actual" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="late_fee_due" step="0.01" min="0" value="0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="late_fee_pay" step="0.01" min="0" value="0">
                        </div>
                    </div>
                </div>

                <div class="fee-section">
                    <h5><i class="fas fa-calendar-alt"></i> Annual Fees</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Annual Session Fee</label>
                            <input type="number" class="form-control" name="annual_session_fee_actual" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="annual_session_fee_due" step="0.01" min="0" value="0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="annual_session_fee_pay" step="0.01" min="0" value="0">
                        </div>
                    </div>
                </div>

                <div class="fee-section">
                    <h5><i class="fas fa-book-open"></i> Books & Stationary</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Books</label>
                            <input type="number" class="form-control" name="books_actual" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="books_due" step="0.01" min="0" value="0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="books_pay" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Khata</label>
                            <input type="number" class="form-control" name="khata_actual" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="khata_due" step="0.01" min="0" value="0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="khata_pay" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Diary</label>
                            <input type="number" class="form-control" name="diary_actual" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="diary_due" step="0.01" min="0" value="0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="diary_pay" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Stationary & Printing</label>
                            <input type="number" class="form-control" name="stationary_printing_actual" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="stationary_printing_due" step="0.01" min="0" value="0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="stationary_printing_pay" step="0.01" min="0" value="0">
                        </div>
                    </div>
                </div>

                <div class="fee-section">
                    <h5><i class="fas fa-hand-holding-heart"></i> Other Fees</h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Poor Fund</label>
                            <input type="number" class="form-control" name="poor_fund_actual" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="poor_fund_due" step="0.01" min="0" value="0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="poor_fund_pay" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Others</label>
                            <input type="number" class="form-control" name="others_actual" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="others_due" step="0.01" min="0" value="0" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="others_pay" step="0.01" min="0" value="0">
                        </div>
                    </div>
                </div>

                <div class="total-section">
                    <div class="row">
                        <div class="col-md-3">
                            <h5>Total Actual Fee: <span id="total_actual_display">0.00</span></h5>
                        </div>
                        <div class="col-md-3">
                            <h5>Total Pay: <span id="total_pay_display">0.00</span></h5>
                        </div>
                        <div class="col-md-3">
                            <h5>Total Remaining Due: <span id="total_remaining_due_display">0.00</span></h5>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Save Fee Record
                    </button>
                    <a href="student_details.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-home"></i> Back to Dashboard
                    </a>
                    <a href="fees_details.php?reg_no=<?= $reg_no ?>" class="btn btn-sm btn-info action-btn">
                                    <i class="fas fa-eye"></i> Details
                                </a>
                    
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    // Calculate due when payment changes for other fees
    $('.payment-input').on('input', function () {
        const fieldPrefix = $(this).attr('name').replace('_pay', '');
        const actual = parseFloat($(`input[name="${fieldPrefix}_actual"]`).val()) || 0;
        const pay = parseFloat($(this).val()) || 0;
        const due = Math.max(0, actual - pay);

        $(`input[name="${fieldPrefix}_due"]`).val(due.toFixed(2));
        calculateTotals();
    });

    // Calculate due when actual fee changes for other fees
    $('input[name$="_actual"]').on('input', function () {
        const fieldPrefix = $(this).attr('name').replace('_actual', '');
        const actual = parseFloat($(this).val()) || 0;
        const pay = parseFloat($(`input[name="${fieldPrefix}_pay"]`).val()) || 0;
        const due = Math.max(0, actual - pay);

        $(`input[name="${fieldPrefix}_due"]`).val(due.toFixed(2));
        calculateTotals();
    });

    // Handle input changes for tuition and exam fees
    $(document).on('input', '.tution-actual-input, .tution-payment-input', function () {
        const row = $(this).closest('.tution-fee-row');
        calculateTutionRowDue(row);
        calculateTotals();
    });

    $(document).on('input', '.exam-actual-input, .exam-payment-input', function () {
        const row = $(this).closest('.exam-fee-row');
        calculateExamRowDue(row);
        calculateTotals();
    });

    // Add new tuition fee row
    $('#add_tution_fee').click(function () {
        const newRow = $('.tution-fee-row:first').clone();
        newRow.find('select, input').val('');
        newRow.find('.duplicate-error').hide();
        newRow.find('.tution-remaining-due-input').val('0');
        newRow.appendTo('#tution_fees_body');
        updateRemoveButtons('.remove-tution-row');
    });

    // Add new exam fee row
    $('#add_exam_fee').click(function () {
        const newRow = $('.exam-fee-row:first').clone();
        newRow.find('select, input').val('');
        newRow.find('.duplicate-error').hide();
        newRow.find('.exam-remaining-due-input').val('0');
        newRow.appendTo('#exam_fees_body');
        updateRemoveButtons('.remove-exam-row');
    });

    // Remove tuition fee row
    $(document).on('click', '.remove-tution-row', function () {
        if ($('.tution-fee-row').length > 1) {
            $(this).closest('.tution-fee-row').remove();
            checkDuplicate('.tution-month-select');
            calculateTotals();
        }
        updateRemoveButtons('.remove-tution-row');
    });

    // Remove exam fee row
    $(document).on('click', '.remove-exam-row', function () {
        if ($('.exam-fee-row').length > 1) {
            $(this).closest('.exam-fee-row').remove();
            checkDuplicate('.exam-term-select');
            calculateTotals();
        }
        updateRemoveButtons('.remove-exam-row');
    });

    // Check for duplicates
    function checkDuplicate(selector) {
        let values = [];
        let hasDuplicate = false;

        $(selector).each(function () {
            const val = $(this).val();
            if (val) {
                if (values.includes(val)) {
                    $(this).next('.duplicate-error').show();
                    hasDuplicate = true;
                } else {
                    values.push(val);
                    $(this).next('.duplicate-error').hide();
                }
            }
        });

        return hasDuplicate;
    }

    // Calculate totals (actual, payment, due)
    function calculateTotals() {
        let totalActual = 0;
        let totalPay = 0;
        let totalRemainingDue = 0;

        // Tuition
        $('.tution-fee-row').each(function () {
            totalActual += parseFloat($(this).find('.tution-actual-input').val()) || 0;
            totalPay += parseFloat($(this).find('.tution-payment-input').val()) || 0;
            totalRemainingDue += parseFloat($(this).find('.tution-remaining-due-input').val()) || 0;
        });

        // Exam
        $('.exam-fee-row').each(function () {
            totalActual += parseFloat($(this).find('.exam-actual-input').val()) || 0;
            totalPay += parseFloat($(this).find('.exam-payment-input').val()) || 0;
            totalRemainingDue += parseFloat($(this).find('.exam-remaining-due-input').val()) || 0;
        });

        // Other fees
        $('input[name$="_actual"]').not('.tution-actual-input, .exam-actual-input').each(function () {
            totalActual += parseFloat($(this).val()) || 0;
        });

        $('.payment-input').not('.tution-payment-input, .exam-payment-input').each(function () {
            totalPay += parseFloat($(this).val()) || 0;
        });

        $('input[name$="_due"]').not('.tution-remaining-due-input, .exam-remaining-due-input').each(function () {
            totalRemainingDue += parseFloat($(this).val()) || 0;
        });

        $('#total_actual_display').text(totalActual.toFixed(2));
        $('#total_pay_display').text(totalPay.toFixed(2));
        $('#total_remaining_due_display').text(totalRemainingDue.toFixed(2));
    }

    // Calculate individual tuition fee row
    function calculateTutionRowDue(row) {
        const actual = parseFloat(row.find('.tution-actual-input').val()) || 0;
        const pay = parseFloat(row.find('.tution-payment-input').val()) || 0;
        const due = Math.max(0, actual - pay);
        row.find('.tution-remaining-due-input').val(due.toFixed(2));
    }

    // Calculate individual exam fee row
    function calculateExamRowDue(row) {
        const actual = parseFloat(row.find('.exam-actual-input').val()) || 0;
        const pay = parseFloat(row.find('.exam-payment-input').val()) || 0;
        const due = Math.max(0, actual - pay);
        row.find('.exam-remaining-due-input').val(due.toFixed(2));
    }

    // Update remove buttons
    function updateRemoveButtons(selector) {
        $(selector).prop('disabled', false);
        if ($(selector).length === 1) {
            $(selector).prop('disabled', true);
        }
    }

    // Check for duplicate month/term on change
    $(document).on('change', '.tution-month-select', function () {
        checkDuplicate('.tution-month-select');
    });

    $(document).on('change', '.exam-term-select', function () {
        checkDuplicate('.exam-term-select');
    });

    // Form validation
    $('#feeForm').on('submit', function (e) {
        const hasTutionDuplicates = checkDuplicate('.tution-month-select');
        const hasExamDuplicates = checkDuplicate('.exam-term-select');

        if (hasTutionDuplicates || hasExamDuplicates) {
            alert('Please fix duplicate months or terms before submitting.');
            e.preventDefault();
            return false;
        }

        let totalPay = 0;
        $('.tution-payment-input, .exam-payment-input, .payment-input').each(function () {
            totalPay += parseFloat($(this).val()) || 0;
        });

        if (totalPay <= 0) {
            alert('Please enter at least one payment amount.');
            e.preventDefault();
            return false;
        }

        return true;
    });

    // Initial setup
    updateRemoveButtons('.remove-tution-row');
    updateRemoveButtons('.remove-exam-row');
});

</script>
</body>
</html>
<?php } else {
    header("Location: index.php");
    exit();
} ?>