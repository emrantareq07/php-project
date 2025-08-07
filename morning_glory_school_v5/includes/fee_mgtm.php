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
            $exam_term = $_POST['exam_term'] ?? '';
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');

            $months = $_POST['months'];
        if (count($months) !== count(array_unique($months))) {
            die("<div class='alert alert-danger text-center mt-2'>Error: Duplicate months are not allowed</div>");
        }

        $actual_fees = $_POST['actual_fee'];
        $payments = $_POST['payment'];
        $initial_dues = $_POST['due'];

        $calculated_dues = [];

        for ($i = 0; $i < count($fee_types); $i++) {
            $actual_fee = is_numeric($actual_fees[$i]) ? (float)$actual_fees[$i] : 0;
            $payment = is_numeric($payments[$i]) ? (float)$payments[$i] : 0;
            $initial_due = is_numeric($initial_dues[$i]) ? (float)$initial_dues[$i] : 0;
            $remaining_due = max(0, ($actual_fee + $initial_due) - $payment);
            $calculated_dues[] = $remaining_due;
        }

        $fee_types_str = implode(',', $fee_types);
        $actual_fees_str = implode(',', $actual_fees);
        $payments_str = implode(',', $payments);
        $dues_str = implode(',', $calculated_dues);

            // Calculate totals
            $total_actual = 0;
            $total_pay = 0;
            $total_due = 0;

            // Fee fields with actual, due, pay
            $fee_fields = [
                'admission_fee', 'tution_fee', 'exam_fee', 'board_reg_fee',
                'scholarship_fee', 'transfer_fee', 'late_fee', 'annual_session_fee',
                'books', 'khata', 'diary', 'stationary_printing', 'poor_fund', 'others'
            ];

            foreach ($fee_fields as $field) {
                ${$field.'_actual'} = isset($_POST[$field.'_actual']) ? (float)$_POST[$field.'_actual'] : 0;
                ${$field.'_pay'} = isset($_POST[$field.'_pay']) ? (float)$_POST[$field.'_pay'] : 0;
                ${$field.'_due'} = max(0, ${$field.'_actual'} - ${$field.'_pay'});
                
                $total_actual += ${$field.'_actual'};
                $total_pay += ${$field.'_pay'};
                $total_due += ${$field.'_due'};
            }

            $sql = "INSERT INTO fee_mgtm_tbl (
                entry_date, reg_no, name, class, roll_no, section, exam_term,
                admission_fee_actual, admission_fee_due, admission_fee_pay,
                tution_fee_actual, tution_fee_due, tution_fee_pay,
                exam_fee_actual, exam_fee_due, exam_fee_pay,
                board_reg_fee_actual, board_reg_fee_due, board_reg_fee_pay,
                scholarship_fee_actual, scholarship_fee_due, scholarship_fee_pay,
                transfer_fee_actual, transfer_fee_due, transfer_fee_pay,
                late_fee_actual, late_fee_due, late_fee_pay,
                annual_session_fee_actual, annual_session_fee_due, annual_session_fee_pay,
                books_actual, books_due, books_pay,
                khata_actual, khata_due, khata_pay,
                diary_actual, diary_due, diary_pay,
                stationary_printing_actual, stationary_printing_due, stationary_printing_pay,
                poor_fund_actual, poor_fund_due, poor_fund_pay,
                others_actual, others_due, others_pay,
                total_actual, total_due, total_pay,
                created_at, updated_at
            ) VALUES (
                :entry_date, :reg_no, :name, :class, :roll_no, :section, :exam_term,
                :admission_fee_actual, :admission_fee_due, :admission_fee_pay,
                :tution_fee_actual, :tution_fee_due, :tution_fee_pay,
                :exam_fee_actual, :exam_fee_due, :exam_fee_pay,
                :board_reg_fee_actual, :board_reg_fee_due, :board_reg_fee_pay,
                :scholarship_fee_actual, :scholarship_fee_due, :scholarship_fee_pay,
                :transfer_fee_actual, :transfer_fee_due, :transfer_fee_pay,
                :late_fee_actual, :late_fee_due, :late_fee_pay,
                :annual_session_fee_actual, :annual_session_fee_due, :annual_session_fee_pay,
                :books_actual, :books_due, :books_pay,
                :khata_actual, :khata_due, :khata_pay,
                :diary_actual, :diary_due, :diary_pay,
                :stationary_printing_actual, :stationary_printing_due, :stationary_printing_pay,
                :poor_fund_actual, :poor_fund_due, :poor_fund_pay,
                :others_actual, :others_due, :others_pay,
                :total_actual, :total_due, :total_pay,
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
            $stmt->bindParam(':exam_term', $exam_term);
            
            foreach ($fee_fields as $field) {
                $stmt->bindParam(':'.$field.'_actual', ${$field.'_actual'});
                $stmt->bindParam(':'.$field.'_due', ${$field.'_due'});
                $stmt->bindParam(':'.$field.'_pay', ${$field.'_pay'});
            }
            
            $stmt->bindParam(':total_actual', $total_actual);
            $stmt->bindParam(':total_due', $total_due);
            $stmt->bindParam(':total_pay', $total_pay);
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
                        <input type="text" class="form-control" name="reg_no" value="<?= $student['reg_no'] ?>" required>
                    </div>
                    <div class="col-md-3">
                        <label for="name" class="form-label">Student Name *</label>
                        <input type="text" class="form-control" name="name" value="<?= $student['std_name'] ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label for="class" class="form-label">Class *</label>
                        <input type="text" class="form-control" name="class" value="<?= $student['class'] ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label for="roll_no" class="form-label">Roll Number *</label>
                        <input type="text" class="form-control" name="roll_no" value="<?= $student['roll_no'] ?>" required>
                    </div>
                    <div class="col-md-2">
                        <label for="section" class="form-label">Section *</label>
                        <input type="text" class="form-control" name="section" value="<?= $student['section'] ?>" required>
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
                    <h5><i class="fas fa-book"></i> Tuition & Exam Fees</h5>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dynamic_field">
                        <thead>
                            <tr>
                                <td colspan="5" class="text-end">
                                    <button type="button" name="add" id="add" class="btn btn-success btn-sm">
                                        <i class="fa fa-plus"></i> Add Fee
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th>Months</th>
                                <th>Actual Fee</th>
                                <th>Initial Due</th>
                                <th>Pay</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr id="row1">
                                <td>
                                    <select class="form-select" name="months">
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
                                    <div class="duplicate-error">This fee type has already been selected</div>
                                </td>
                                <td><input type="number" class="form-control" name="tution_fee_actual[]" step="0.01" min="0" value="0"></td>
                                <td><input type="number" class="form-control" name="tution_fee_due[]" step="0.01" min="0" value="0" readonly></td>
                                <td><input type="number" class="form-control payment-input[]" name="tution_fee_pay" step="0.01" min="0" value="0"></td>
                                <td class="text-center">
                                    <button type="button" name="remove" id="1" class="btn btn-danger btn-sm btn_remove" disabled>
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Exam Term</label>
                            <select class="form-select" name="exam_term">
                                <option value="">Select Term</option>
                                <option value="1st Term">1st Term</option>
                                <option value="2nd Term">2nd Term</option>
                                <option value="3rd Term">3rd Term</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Exam Fee</label>
                            <input type="number" class="form-control" name="exam_fee_actual" step="0.01" min="0" value="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Due</label>
                            <input type="number" class="form-control" name="exam_fee_due" step="0.01" min="0" value="0" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Pay</label>
                            <input type="number" class="form-control payment-input" name="exam_fee_pay" step="0.01" min="0" value="0">
                        </div>
                    </div>
                    <!-- <div class="row mt-3">
                        
                    </div> -->
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
                        <div class="col-md-4">
                            <h5>Total Actual Fee: <span id="total_actual_display">0.00</span></h5>
                            <input type="hidden" name="total_actual" id="total_actual" value="0">
                        </div>
                        <div class="col-md-4">
                            <h5>Total Due: <span id="total_due_display">0.00</span></h5>
                            <input type="hidden" name="total_due" id="total_due" value="0">
                        </div>
                        <div class="col-md-4">
                            <h5>Total Pay: <span id="total_pay_display">0.00</span></h5>
                            <input type="hidden" name="total_pay" id="total_pay" value="0">
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save"></i> Save Fee Record
                    </button>
                    <a href="../dashboard.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-home"></i> Back to Dashboard
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
    // Calculate due when payment changes
    $('.payment-input').on('change', function() {
        const fieldPrefix = $(this).attr('name').replace('_pay', '');
        const actual = parseFloat($(`input[name="${fieldPrefix}_actual"]`).val()) || 0;
        const pay = parseFloat($(this).val()) || 0;
        const due = Math.max(0, actual - pay);
        
        $(`input[name="${fieldPrefix}_due"]`).val(due.toFixed(2));
        calculateTotals();
    });

    // Calculate due when actual fee changes
    $('input[name$="_actual"]').on('change', function() {
        const fieldPrefix = $(this).attr('name').replace('_actual', '');
        const actual = parseFloat($(this).val()) || 0;
        const pay = parseFloat($(`input[name="${fieldPrefix}_pay"]`).val()) || 0;
        const due = Math.max(0, actual - pay);
        
        $(`input[name="${fieldPrefix}_due"]`).val(due.toFixed(2));
        calculateTotals();
    });

    // Calculate totals
    function calculateTotals() {
        let totalActual = 0;
        let totalPay = 0;
        let totalDue = 0;
        
        // Calculate actual fees total
        $('input[name$="_actual"]').each(function() {
            totalActual += parseFloat($(this).val()) || 0;
        });
        
        // Calculate payments total
        $('.payment-input').each(function() {
            totalPay += parseFloat($(this).val()) || 0;
        });
        
        // Calculate due total
        $('input[name$="_due"]').each(function() {
            totalDue += parseFloat($(this).val()) || 0;
        });
        
        // Update display
        $('#total_actual_display').text(totalActual.toFixed(2));
        $('#total_pay_display').text(totalPay.toFixed(2));
        $('#total_due_display').text(totalDue.toFixed(2));
        
        // Update hidden fields
        $('#total_actual').val(totalActual.toFixed(2));
        $('#total_pay').val(totalPay.toFixed(2));
        $('#total_due').val(totalDue.toFixed(2));
    }
});
</script>
</body>
</html>
<?php } else {
    header("Location: index.php");
    exit();
} ?>