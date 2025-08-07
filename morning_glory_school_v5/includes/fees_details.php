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

    // Get registration number from URL
    $reg_no = $_GET['reg_no'] ?? '';
    if (empty($reg_no)) {
        die("Registration number not provided!");
    }

    // Get student details
    $student = null;
    $stmt = $conn->prepare("SELECT * FROM std_tbl WHERE reg_no = ?");
    $stmt->bind_param("s", $reg_no);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();

    if (!$student) {
        die("Student not found!");
    }

    // Get fee details for this student
    $fee_details = [];
    try {
        $conn = new PDO("mysql:host=localhost;dbname=morning_glory_db", "root", "");
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM fee_mgtm_tbl WHERE reg_no = ? ORDER BY entry_date DESC");
        $stmt->execute([$reg_no]);
        $fee_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>New Morning Glory - Fee Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .card-header {
            font-weight: bold;
        }
        .fee-table th {
            background-color: #f8f9fa;
        }
        .total-row {
            font-weight: bold;
            background-color: #e9ecef;
        }
        .month-list, .term-list {
            list-style-type: none;
            padding-left: 0;
        }
        .month-list li, .term-list li {
            padding: 5px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-section {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
        }
        .detail-section h5 {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="card">
        <div class="card-header text-uppercase bg-primary text-white">
            <i class="fa fa-money-bill"></i> Fee Details for <?= htmlspecialchars($student['std_name']) ?>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Registration No:</label>
                        <p class="form-control-static"><?= htmlspecialchars($student['reg_no']) ?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Class:</label>
                        <p class="form-control-static"><?= htmlspecialchars($student['class']) ?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Roll No:</label>
                        <p class="form-control-static"><?= htmlspecialchars($student['roll_no']) ?></p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">Section:</label>
                        <p class="form-control-static"><?= htmlspecialchars($student['section']) ?></p>
                    </div>
                </div>
            </div>

            <?php if (empty($fee_details)): ?>
                <div class="alert alert-info">
                    No fee records found for this student.
                </div>
            <?php else: ?>
                <?php foreach ($fee_details as $fee): ?>
                    <div class="detail-section">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h5>Entry Date: <?= date('d M Y', strtotime($fee['entry_date'])) ?></h5>
                            </div>
                            <div class="col-md-6 text-end">
                                <a href="../dashboard.php" class="btn btn-secondary btn-sm">
                    <i class="fas fa-home"></i> Back to Dashboard
                </a>
                                <a href="print_receipt.php?id=<?= $fee['id'] ?>" class="btn btn-sm btn-primary" target="_blank">
                                    <i class="fas fa-print"></i> Print Receipt
                                </a>
                                <a href="edit_fee.php?id=<?= $fee['id'] ?>" class="btn btn-sm btn-primary">
                                    <i class="fas fa-edit"></i> Edit Fee
                                </a>
                            </div>
                        </div>
                        <!-- Tuition Fees -->
                        <?php if (!empty($fee['tution_months'])): ?>
                            <div class="mb-4">
                                <h5><i class="fas fa-book"></i> Tuition Fees</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="month-list">
                                            <?php 
                                            $months = explode(',', $fee['tution_months']);
                                            $actuals = !empty($fee['tution_fee_actual']) ? explode(',', $fee['tution_fee_actual']) : array_fill(0, count($months), 0);
                                            $pays = !empty($fee['tution_fee_pay']) ? explode(',', $fee['tution_fee_pay']) : array_fill(0, count($months), 0);
                                            $dues = !empty($fee['tution_fee_remaining_due']) ? explode(',', $fee['tution_fee_remaining_due']) : array_fill(0, count($months), 0);
                                            
                                            for ($i = 0; $i < count($months); $i++): 
                                                if (!empty($months[$i])): ?>
                                                    <li>
                                                        <strong><?= htmlspecialchars($months[$i]) ?>:</strong>
                                                        Actual: ৳<?= number_format($actuals[$i] ?? 0, 2) ?> | 
                                                        Paid: ৳<?= number_format($pays[$i] ?? 0, 2) ?> | 
                                                        Due: ৳<?= number_format($dues[$i] ?? 0, 2) ?>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="total-row p-2">
                                            <strong>Tuition Total:</strong>
                                            Actual: ৳<?= number_format($fee['tution_total_actual'] ?? 0, 2) ?> | 
                                            Paid: ৳<?= number_format($fee['tution_total_pay'] ?? 0, 2) ?> | 
                                            Due: ৳<?= number_format($fee['tution_total_remaining_due'] ?? 0, 2) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Exam Fees -->
                        <?php if (!empty($fee['exam_terms'])): ?>
                            <div class="mb-4">
                                <h5><i class="fas fa-file-alt"></i> Exam Fees</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <ul class="term-list">
                                            <?php 
                                            $terms = explode(',', $fee['exam_terms']);
                                            $actuals = !empty($fee['exam_fee_actual']) ? explode(',', $fee['exam_fee_actual']) : array_fill(0, count($terms), 0);
                                            $pays = !empty($fee['exam_fee_pay']) ? explode(',', $fee['exam_fee_pay']) : array_fill(0, count($terms), 0);
                                            $dues = !empty($fee['exam_fee_remaining_due']) ? explode(',', $fee['exam_fee_remaining_due']) : array_fill(0, count($terms), 0);
                                            
                                            for ($i = 0; $i < count($terms); $i++): 
                                                if (!empty($terms[$i])): ?>
                                                    <li>
                                                        <strong><?= htmlspecialchars($terms[$i]) ?>:</strong>
                                                        Actual: ৳<?= number_format($actuals[$i] ?? 0, 2) ?> | 
                                                        Paid: ৳<?= number_format($pays[$i] ?? 0, 2) ?> | 
                                                        Due: ৳<?= number_format($dues[$i] ?? 0, 2) ?>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="total-row p-2">
                                            <strong>Exam Total:</strong>
                                            Actual: ৳<?= number_format($fee['exam_total_actual'] ?? 0, 2) ?> | 
                                            Paid: ৳<?= number_format($fee['exam_total_pay'] ?? 0, 2) ?> | 
                                            Due: ৳<?= number_format($fee['exam_total_remaining_due'] ?? 0, 2) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>



                        <!-- Other Fees -->
                        <div class="row">
                            <div class="col-md-6">
                                <h5><i class="fas fa-file-alt"></i> Other Fees</h5>
                                <table class="table table-bordered fee-table">
                                    <thead>
                                        <tr>
                                            <th>Fee Type</th>
                                            <th>Actual</th>
                                            <th>Paid</th>
                                            <th>Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($fee['admission_fee_actual'] > 0): ?>
                                        <tr>
                                            <td>Admission Fee</td>
                                            <td>৳<?= number_format($fee['admission_fee_actual'], 2) ?></td>
                                            <td>৳<?= number_format($fee['admission_fee_pay'], 2) ?></td>
                                            <td>৳<?= number_format($fee['admission_fee_due'], 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        
                                        <?php if ($fee['board_reg_fee_actual'] > 0): ?>
                                        <tr>
                                            <td>Board Registration Fee</td>
                                            <td>৳<?= number_format($fee['board_reg_fee_actual'], 2) ?></td>
                                            <td>৳<?= number_format($fee['board_reg_fee_pay'], 2) ?></td>
                                            <td>৳<?= number_format($fee['board_reg_fee_due'], 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        
                                        <?php if ($fee['scholarship_fee_actual'] > 0): ?>
                                        <tr>
                                            <td>Scholarship Fee</td>
                                            <td>৳<?= number_format($fee['scholarship_fee_actual'], 2) ?></td>
                                            <td>৳<?= number_format($fee['scholarship_fee_pay'], 2) ?></td>
                                            <td>৳<?= number_format($fee['scholarship_fee_due'], 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        
                                        <?php if ($fee['transfer_fee_actual'] > 0): ?>
                                        <tr>
                                            <td>Transfer Fee</td>
                                            <td>৳<?= number_format($fee['transfer_fee_actual'], 2) ?></td>
                                            <td>৳<?= number_format($fee['transfer_fee_pay'], 2) ?></td>
                                            <td>৳<?= number_format($fee['transfer_fee_due'], 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        
                                        <?php if ($fee['late_fee_actual'] > 0): ?>
                                        <tr>
                                            <td>Late Fee</td>
                                            <td>৳<?= number_format($fee['late_fee_actual'], 2) ?></td>
                                            <td>৳<?= number_format($fee['late_fee_pay'], 2) ?></td>
                                            <td>৳<?= number_format($fee['late_fee_due'], 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        
                                        <?php if ($fee['annual_session_fee_actual'] > 0): ?>
                                        <tr>
                                            <td>Annual Session Fee</td>
                                            <td>৳<?= number_format($fee['annual_session_fee_actual'], 2) ?></td>
                                            <td>৳<?= number_format($fee['annual_session_fee_pay'], 2) ?></td>
                                            <td>৳<?= number_format($fee['annual_session_fee_due'], 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="col-md-6">
                                <h5><i class="fas fa-book-open"></i> Books & Stationary</h5>
                                <table class="table table-bordered fee-table">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Actual</th>
                                            <th>Paid</th>
                                            <th>Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($fee['books_actual'] > 0): ?>
                                        <tr>
                                            <td>Books</td>
                                            <td>৳<?= number_format($fee['books_actual'], 2) ?></td>
                                            <td>৳<?= number_format($fee['books_pay'], 2) ?></td>
                                            <td>৳<?= number_format($fee['books_due'], 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        
                                        <?php if ($fee['khata_actual'] > 0): ?>
                                        <tr>
                                            <td>Khata</td>
                                            <td>৳<?= number_format($fee['khata_actual'], 2) ?></td>
                                            <td>৳<?= number_format($fee['khata_pay'], 2) ?></td>
                                            <td>৳<?= number_format($fee['khata_due'], 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        
                                        <?php if ($fee['diary_actual'] > 0): ?>
                                        <tr>
                                            <td>Diary</td>
                                            <td>৳<?= number_format($fee['diary_actual'], 2) ?></td>
                                            <td>৳<?= number_format($fee['diary_pay'], 2) ?></td>
                                            <td>৳<?= number_format($fee['diary_due'], 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        
                                        <?php if ($fee['stationary_printing_actual'] > 0): ?>
                                        <tr>
                                            <td>Stationary & Printing</td>
                                            <td>৳<?= number_format($fee['stationary_printing_actual'], 2) ?></td>
                                            <td>৳<?= number_format($fee['stationary_printing_pay'], 2) ?></td>
                                            <td>৳<?= number_format($fee['stationary_printing_due'], 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        
                                        <?php if ($fee['poor_fund_actual'] > 0): ?>
                                        <tr>
                                            <td>Poor Fund</td>
                                            <td>৳<?= number_format($fee['poor_fund_actual'], 2) ?></td>
                                            <td>৳<?= number_format($fee['poor_fund_pay'], 2) ?></td>
                                            <td>৳<?= number_format($fee['poor_fund_due'], 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        
                                        <?php if ($fee['others_actual'] > 0): ?>
                                        <tr>
                                            <td>Others</td>
                                            <td>৳<?= number_format($fee['others_actual'], 2) ?></td>
                                            <td>৳<?= number_format($fee['others_pay'], 2) ?></td>
                                            <td>৳<?= number_format($fee['others_due'], 2) ?></td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Totals -->
                        <div class="total-section mt-4 p-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <h5>Total Actual Fee: ৳<?= number_format($fee['total_actual'], 2) ?></h5>
                                </div>
                                <div class="col-md-4">
                                    <h5>Total Paid: ৳<?= number_format($fee['total_pay'], 2) ?></h5>
                                </div>
                                <div class="col-md-4">
                                    <h5>Total Due: ৳<?= number_format($fee['total_due'], 2) ?></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                <?php endforeach; ?>
            <?php endif; ?>

            <div class="text-center mt-4">
                
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
} else {
    header("Location: index.php");
    exit();
}
?>