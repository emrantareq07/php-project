<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "morning_glory_db";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch the fee record to edit
    if (isset($_GET['id'])) {
        $stmt = $conn->prepare("SELECT * FROM fee_mgtm_tbl WHERE id = :id");
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $fee = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$fee) {
            die("Record not found");
        }
    }

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $update_stmt = $conn->prepare("
            UPDATE fee_mgtm_tbl SET
                entry_date = :entry_date,
                reg_no = :reg_no,
                name = :name,
                class = :class,
                roll_no = :roll_no,
                section = :section,
                admission_fee_actual = :admission_fee_actual,
                admission_fee_due = :admission_fee_due,
                admission_fee_pay = :admission_fee_pay,
                tution_fee_actual = :tution_fee_actual,
                tution_fee_due = :tution_fee_due,
                tution_fee_pay = :tution_fee_pay,
                exam_fee_actual = :exam_fee_actual,
                exam_fee_due = :exam_fee_due,
                exam_fee_pay = :exam_fee_pay,
                exam_term = :exam_term,
                board_reg_fee_actual = :board_reg_fee_actual,
                board_reg_fee_due = :board_reg_fee_due,
                board_reg_fee_pay = :board_reg_fee_pay,
                scholarship_fee_actual = :scholarship_fee_actual,
                scholarship_fee_due = :scholarship_fee_due,
                scholarship_fee_pay = :scholarship_fee_pay,
                transfer_fee_actual = :transfer_fee_actual,
                transfer_fee_due = :transfer_fee_due,
                transfer_fee_pay = :transfer_fee_pay,
                late_fee_actual = :late_fee_actual,
                late_fee_due = :late_fee_due,
                late_fee_pay = :late_fee_pay,
                annual_session_fee_actual = :annual_session_fee_actual,
                annual_session_fee_due = :annual_session_fee_due,
                annual_session_fee_pay = :annual_session_fee_pay,
                books_actual = :books_actual,
                books_due = :books_due,
                books_pay = :books_pay,
                khata_actual = :khata_actual,
                khata_due = :khata_due,
                khata_pay = :khata_pay,
                diary_actual = :diary_actual,
                diary_due = :diary_due,
                diary_pay = :diary_pay,
                stationary_printing_actual = :stationary_printing_actual,
                stationary_printing_due = :stationary_printing_due,
                stationary_printing_pay = :stationary_printing_pay,
                poor_fund_actual = :poor_fund_actual,
                poor_fund_due = :poor_fund_due,
                poor_fund_pay = :poor_fund_pay,
                others_actual = :others_actual,
                others_due = :others_due,
                others_pay = :others_pay,
                total_actual = :total_actual,
                total_due = :total_due,
                total_pay = :total_pay
            WHERE id = :id
        ");

        // Bind all parameters
        $update_stmt->bindParam(':id', $_POST['id']);
        $update_stmt->bindParam(':entry_date', $_POST['entry_date']);
        $update_stmt->bindParam(':reg_no', $_POST['reg_no']);
        $update_stmt->bindParam(':name', $_POST['name']);
        $update_stmt->bindParam(':class', $_POST['class']);
        $update_stmt->bindParam(':roll_no', $_POST['roll_no']);
        $update_stmt->bindParam(':section', $_POST['section']);
        
        // Admission Fee
        $update_stmt->bindParam(':admission_fee_actual', $_POST['admission_fee_actual']);
        $update_stmt->bindParam(':admission_fee_due', $_POST['admission_fee_due']);
        $update_stmt->bindParam(':admission_fee_pay', $_POST['admission_fee_pay']);
        
        // Tuition Fee
        $update_stmt->bindParam(':tution_fee_actual', $_POST['tution_fee_actual']);
        $update_stmt->bindParam(':tution_fee_due', $_POST['tution_fee_due']);
        $update_stmt->bindParam(':tution_fee_pay', $_POST['tution_fee_pay']);
        
        // Exam Fee
        $update_stmt->bindParam(':exam_fee_actual', $_POST['exam_fee_actual']);
        $update_stmt->bindParam(':exam_fee_due', $_POST['exam_fee_due']);
        $update_stmt->bindParam(':exam_fee_pay', $_POST['exam_fee_pay']);
        $update_stmt->bindParam(':exam_term', $_POST['exam_term']);
        
        // Board Reg Fee
        $update_stmt->bindParam(':board_reg_fee_actual', $_POST['board_reg_fee_actual']);
        $update_stmt->bindParam(':board_reg_fee_due', $_POST['board_reg_fee_due']);
        $update_stmt->bindParam(':board_reg_fee_pay', $_POST['board_reg_fee_pay']);
        
        // Scholarship
        $update_stmt->bindParam(':scholarship_fee_actual', $_POST['scholarship_fee_actual']);
        $update_stmt->bindParam(':scholarship_fee_due', $_POST['scholarship_fee_due']);
        $update_stmt->bindParam(':scholarship_fee_pay', $_POST['scholarship_fee_pay']);
        
        // Transfer Fee
        $update_stmt->bindParam(':transfer_fee_actual', $_POST['transfer_fee_actual']);
        $update_stmt->bindParam(':transfer_fee_due', $_POST['transfer_fee_due']);
        $update_stmt->bindParam(':transfer_fee_pay', $_POST['transfer_fee_pay']);
        
        // Late Fee
        $update_stmt->bindParam(':late_fee_actual', $_POST['late_fee_actual']);
        $update_stmt->bindParam(':late_fee_due', $_POST['late_fee_due']);
        $update_stmt->bindParam(':late_fee_pay', $_POST['late_fee_pay']);
        
        // Annual Session
        $update_stmt->bindParam(':annual_session_fee_actual', $_POST['annual_session_fee_actual']);
        $update_stmt->bindParam(':annual_session_fee_due', $_POST['annual_session_fee_due']);
        $update_stmt->bindParam(':annual_session_fee_pay', $_POST['annual_session_fee_pay']);
        
        // Books
        $update_stmt->bindParam(':books_actual', $_POST['books_actual']);
        $update_stmt->bindParam(':books_due', $_POST['books_due']);
        $update_stmt->bindParam(':books_pay', $_POST['books_pay']);
        
        // Khata
        $update_stmt->bindParam(':khata_actual', $_POST['khata_actual']);
        $update_stmt->bindParam(':khata_due', $_POST['khata_due']);
        $update_stmt->bindParam(':khata_pay', $_POST['khata_pay']);
        
        // Diary
        $update_stmt->bindParam(':diary_actual', $_POST['diary_actual']);
        $update_stmt->bindParam(':diary_due', $_POST['diary_due']);
        $update_stmt->bindParam(':diary_pay', $_POST['diary_pay']);
        
        // Stationary
        $update_stmt->bindParam(':stationary_printing_actual', $_POST['stationary_printing_actual']);
        $update_stmt->bindParam(':stationary_printing_due', $_POST['stationary_printing_due']);
        $update_stmt->bindParam(':stationary_printing_pay', $_POST['stationary_printing_pay']);
        
        // Poor Fund
        $update_stmt->bindParam(':poor_fund_actual', $_POST['poor_fund_actual']);
        $update_stmt->bindParam(':poor_fund_due', $_POST['poor_fund_due']);
        $update_stmt->bindParam(':poor_fund_pay', $_POST['poor_fund_pay']);
        
        // Others
        $update_stmt->bindParam(':others_actual', $_POST['others_actual']);
        $update_stmt->bindParam(':others_due', $_POST['others_due']);
        $update_stmt->bindParam(':others_pay', $_POST['others_pay']);
        
        // Totals
        $total_actual = calculateTotal($_POST, '_actual');
        $total_pay = calculateTotal($_POST, '_pay');
        $total_due = calculateTotal($_POST, '_due');
        
        $update_stmt->bindParam(':total_actual', $total_actual);
        $update_stmt->bindParam(':total_due', $total_due);
        $update_stmt->bindParam(':total_pay', $total_pay);
        
        if ($update_stmt->execute()) {
            $success = "Fee record updated successfully!";
            // Refresh the data
            $stmt = $conn->prepare("SELECT * FROM fee_mgtm WHERE id = :id");
            $stmt->bindParam(':id', $_GET['id']);
            $stmt->execute();
            $fee = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error = "Error updating record: " . $update_stmt->errorInfo()[2];
        }
    }

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Helper function to calculate totals
function calculateTotal($data, $suffix) {
    $total = 0;
    foreach ($data as $key => $value) {
        if (str_ends_with($key, $suffix)) {
            $total += is_numeric($value) ? floatval($value) : 0;
        }
    }
    return $total;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Fee Management Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .fee-section {
            background-color: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            border-left: 4px solid #0d6efd;
        }
        .fee-section h5 {
            color: #0d6efd;
        }
        .total-section {
            background-color: #e7f1ff;
            padding: 15px;
            border-radius: 5px;
            font-weight: bold;
        }
        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Fee Management Record</h2>
        <a href="all_fees_mgtm.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="id" value="<?= htmlspecialchars($fee['id']) ?>">

        <!-- Student Information Section -->
        <div class="fee-section">
            <h5><i class="fas fa-user-graduate"></i> Student Information</h5>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="entry_date" class="form-label">Entry Date</label>
                        <input type="date" class="form-control" id="entry_date" name="entry_date" 
                               value="<?= htmlspecialchars($fee['entry_date']) ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="reg_no" class="form-label">Registration No</label>
                        <input type="text" class="form-control" id="reg_no" name="reg_no" 
                               value="<?= htmlspecialchars($fee['reg_no']) ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Student Name</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= htmlspecialchars($fee['name']) ?>" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="class" class="form-label">Class</label>
                        <input type="text" class="form-control" id="class" name="class" 
                               value="<?= htmlspecialchars($fee['class']) ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="roll_no" class="form-label">Roll No</label>
                        <input type="text" class="form-control" id="roll_no" name="roll_no" 
                               value="<?= htmlspecialchars($fee['roll_no']) ?>" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="section" class="form-label">Section</label>
                        <input type="text" class="form-control" id="section" name="section" 
                               value="<?= htmlspecialchars($fee['section']) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="exam_term" class="form-label">Exam Term</label>
                        <input type="text" class="form-control" id="exam_term" name="exam_term" 
                               value="<?= htmlspecialchars($fee['exam_term']) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Fees Section -->
        <div class="fee-section">
            <h5><i class="fas fa-money-bill-wave"></i> Main Fees</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="admission_fee_actual" class="form-label">Admission Fee (Actual)</label>
                        <input type="number" step="0.01" class="form-control" id="admission_fee_actual" name="admission_fee_actual" 
                               value="<?= htmlspecialchars($fee['admission_fee_actual']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="admission_fee_pay" class="form-label">Admission Fee (Paid)</label>
                        <input type="number" step="0.01" class="form-control" id="admission_fee_pay" name="admission_fee_pay" 
                               value="<?= htmlspecialchars($fee['admission_fee_pay']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="admission_fee_due" class="form-label">Admission Fee (Due)</label>
                        <input type="number" step="0.01" class="form-control" id="admission_fee_due" name="admission_fee_due" 
                               value="<?= htmlspecialchars($fee['admission_fee_due']) ?>">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="tution_fee_actual" class="form-label">Tuition Fee (Actual)</label>
                        <input type="number" step="0.01" class="form-control" id="tution_fee_actual" name="tution_fee_actual" 
                               value="<?= htmlspecialchars($fee['tution_fee_actual']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="tution_fee_pay" class="form-label">Tuition Fee (Paid)</label>
                        <input type="number" step="0.01" class="form-control" id="tution_fee_pay" name="tution_fee_pay" 
                               value="<?= htmlspecialchars($fee['tution_fee_pay']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="tution_fee_due" class="form-label">Tuition Fee (Due)</label>
                        <input type="number" step="0.01" class="form-control" id="tution_fee_due" name="tution_fee_due" 
                               value="<?= htmlspecialchars($fee['tution_fee_due']) ?>">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="exam_fee_actual" class="form-label">Exam Fee (Actual)</label>
                        <input type="number" step="0.01" class="form-control" id="exam_fee_actual" name="exam_fee_actual" 
                               value="<?= htmlspecialchars($fee['exam_fee_actual']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="exam_fee_pay" class="form-label">Exam Fee (Paid)</label>
                        <input type="number" step="0.01" class="form-control" id="exam_fee_pay" name="exam_fee_pay" 
                               value="<?= htmlspecialchars($fee['exam_fee_pay']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="exam_fee_due" class="form-label">Exam Fee (Due)</label>
                        <input type="number" step="0.01" class="form-control" id="exam_fee_due" name="exam_fee_due" 
                               value="<?= htmlspecialchars($fee['exam_fee_due']) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Fees Section -->
        <div class="fee-section">
            <h5><i class="fas fa-receipt"></i> Additional Fees</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="board_reg_fee_actual" class="form-label">Board Reg Fee (Actual)</label>
                        <input type="number" step="0.01" class="form-control" id="board_reg_fee_actual" name="board_reg_fee_actual" 
                               value="<?= htmlspecialchars($fee['board_reg_fee_actual']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="board_reg_fee_pay" class="form-label">Board Reg Fee (Paid)</label>
                        <input type="number" step="0.01" class="form-control" id="board_reg_fee_pay" name="board_reg_fee_pay" 
                               value="<?= htmlspecialchars($fee['board_reg_fee_pay']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="board_reg_fee_due" class="form-label">Board Reg Fee (Due)</label>
                        <input type="number" step="0.01" class="form-control" id="board_reg_fee_due" name="board_reg_fee_due" 
                               value="<?= htmlspecialchars($fee['board_reg_fee_due']) ?>">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="scholarship_fee_actual" class="form-label">Scholarship (Actual)</label>
                        <input type="number" step="0.01" class="form-control" id="scholarship_fee_actual" name="scholarship_fee_actual" 
                               value="<?= htmlspecialchars($fee['scholarship_fee_actual']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="scholarship_fee_pay" class="form-label">Scholarship (Paid)</label>
                        <input type="number" step="0.01" class="form-control" id="scholarship_fee_pay" name="scholarship_fee_pay" 
                               value="<?= htmlspecialchars($fee['scholarship_fee_pay']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="scholarship_fee_due" class="form-label">Scholarship (Due)</label>
                        <input type="number" step="0.01" class="form-control" id="scholarship_fee_due" name="scholarship_fee_due" 
                               value="<?= htmlspecialchars($fee['scholarship_fee_due']) ?>">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="transfer_fee_actual" class="form-label">Transfer Fee (Actual)</label>
                        <input type="number" step="0.01" class="form-control" id="transfer_fee_actual" name="transfer_fee_actual" 
                               value="<?= htmlspecialchars($fee['transfer_fee_actual']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="transfer_fee_pay" class="form-label">Transfer Fee (Paid)</label>
                        <input type="number" step="0.01" class="form-control" id="transfer_fee_pay" name="transfer_fee_pay" 
                               value="<?= htmlspecialchars($fee['transfer_fee_pay']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="transfer_fee_due" class="form-label">Transfer Fee (Due)</label>
                        <input type="number" step="0.01" class="form-control" id="transfer_fee_due" name="transfer_fee_due" 
                               value="<?= htmlspecialchars($fee['transfer_fee_due']) ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="late_fee_actual" class="form-label">Late Fee (Actual)</label>
                        <input type="number" step="0.01" class="form-control" id="late_fee_actual" name="late_fee_actual" 
                               value="<?= htmlspecialchars($fee['late_fee_actual']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="late_fee_pay" class="form-label">Late Fee (Paid)</label>
                        <input type="number" step="0.01" class="form-control" id="late_fee_pay" name="late_fee_pay" 
                               value="<?= htmlspecialchars($fee['late_fee_pay']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="late_fee_due" class="form-label">Late Fee (Due)</label>
                        <input type="number" step="0.01" class="form-control" id="late_fee_due" name="late_fee_due" 
                               value="<?= htmlspecialchars($fee['late_fee_due']) ?>">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="annual_session_fee_actual" class="form-label">Annual Session (Actual)</label>
                        <input type="number" step="0.01" class="form-control" id="annual_session_fee_actual" name="annual_session_fee_actual" 
                               value="<?= htmlspecialchars($fee['annual_session_fee_actual']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="annual_session_fee_pay" class="form-label">Annual Session (Paid)</label>
                        <input type="number" step="0.01" class="form-control" id="annual_session_fee_pay" name="annual_session_fee_pay" 
                               value="<?= htmlspecialchars($fee['annual_session_fee_pay']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="annual_session_fee_due" class="form-label">Annual Session (Due)</label>
                        <input type="number" step="0.01" class="form-control" id="annual_session_fee_due" name="annual_session_fee_due" 
                               value="<?= htmlspecialchars($fee['annual_session_fee_due']) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Materials and Other Fees Section -->
        <div class="fee-section">
            <h5><i class="fas fa-book"></i> Materials & Other Fees</h5>
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="books_actual" class="form-label">Books (Actual)</label>
                        <input type="number" step="0.01" class="form-control" id="books_actual" name="books_actual" 
                               value="<?= htmlspecialchars($fee['books_actual']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="books_pay" class="form-label">Books (Paid)</label>
                        <input type="number" step="0.01" class="form-control" id="books_pay" name="books_pay" 
                               value="<?= htmlspecialchars($fee['books_pay']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="books_due" class="form-label">Books (Due)</label>
                        <input type="number" step="0.01" class="form-control" id="books_due" name="books_due" 
                               value="<?= htmlspecialchars($fee['books_due']) ?>">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="khata_actual" class="form-label">Khata (Actual)</label>
                        <input type="number" step="0.01" class="form-control" id="khata_actual" name="khata_actual" 
                               value="<?= htmlspecialchars($fee['khata_actual']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="khata_pay" class="form-label">Khata (Paid)</label>
                        <input type="number" step="0.01" class="form-control" id="khata_pay" name="khata_pay" 
                               value="<?= htmlspecialchars($fee['khata_pay']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="khata_due" class="form-label">Khata (Due)</label>
                        <input type="number" step="0.01" class="form-control" id="khata_due" name="khata_due" 
                               value="<?= htmlspecialchars($fee['khata_due']) ?>">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="diary_actual" class="form-label">Diary (Actual)</label>
                        <input type="number" step="0.01" class="form-control" id="diary_actual" name="diary_actual" 
                               value="<?= htmlspecialchars($fee['diary_actual']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="diary_pay" class="form-label">Diary (Paid)</label>
                        <input type="number" step="0.01" class="form-control" id="diary_pay" name="diary_pay" 
                               value="<?= htmlspecialchars($fee['diary_pay']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="diary_due" class="form-label">Diary (Due)</label>
                        <input type="number" step="0.01" class="form-control" id="diary_due" name="diary_due" 
                               value="<?= htmlspecialchars($fee['diary_due']) ?>">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="stationary_printing_actual" class="form-label">Stationary (Actual)</label>
                        <input type="number" step="0.01" class="form-control" id="stationary_printing_actual" name="stationary_printing_actual" 
                               value="<?= htmlspecialchars($fee['stationary_printing_actual']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="stationary_printing_pay" class="form-label">Stationary (Paid)</label>
                        <input type="number" step="0.01" class="form-control" id="stationary_printing_pay" name="stationary_printing_pay" 
                               value="<?= htmlspecialchars($fee['stationary_printing_pay']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="stationary_printing_due" class="form-label">Stationary (Due)</label>
                        <input type="number" step="0.01" class="form-control" id="stationary_printing_due" name="stationary_printing_due" 
                               value="<?= htmlspecialchars($fee['stationary_printing_due']) ?>">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="poor_fund_actual" class="form-label">Poor Fund (Actual)</label>
                        <input type="number" step="0.01" class="form-control" id="poor_fund_actual" name="poor_fund_actual" 
                               value="<?= htmlspecialchars($fee['poor_fund_actual']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="poor_fund_pay" class="form-label">Poor Fund (Paid)</label>
                        <input type="number" step="0.01" class="form-control" id="poor_fund_pay" name="poor_fund_pay" 
                               value="<?= htmlspecialchars($fee['poor_fund_pay']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="poor_fund_due" class="form-label">Poor Fund (Due)</label>
                        <input type="number" step="0.01" class="form-control" id="poor_fund_due" name="poor_fund_due" 
                               value="<?= htmlspecialchars($fee['poor_fund_due']) ?>">
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="others_actual" class="form-label">Others (Actual)</label>
                        <input type="number" step="0.01" class="form-control" id="others_actual" name="others_actual" 
                               value="<?= htmlspecialchars($fee['others_actual']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="others_pay" class="form-label">Others (Paid)</label>
                        <input type="number" step="0.01" class="form-control" id="others_pay" name="others_pay" 
                               value="<?= htmlspecialchars($fee['others_pay']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="others_due" class="form-label">Others (Due)</label>
                        <input type="number" step="0.01" class="form-control" id="others_due" name="others_due" 
                               value="<?= htmlspecialchars($fee['others_due']) ?>">
                    </div>
                </div>
            </div>
        </div>

        <!-- Totals Section -->
        <div class="total-section">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Total Actual Fees</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($fee['total_actual']) ?>" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Total Payments</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($fee['total_pay']) ?>" readonly>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Total Dues</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($fee['total_due']) ?>" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
            <button type="submit" class="btn btn-primary me-md-2">
                <i class="fas fa-save"></i> Update Record
            </button>
            <button type="reset" class="btn btn-outline-secondary">
                <i class="fas fa-undo"></i> Reset
            </button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Auto-calculate due amounts when actual or paid amounts change
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('change', function() {
        const fieldName = this.name;
        if (fieldName.endsWith('_actual') || fieldName.endsWith('_pay')) {
            const prefix = fieldName.split('_').slice(0, -1).join('_');
            const actual = parseFloat(document.querySelector(`[name="${prefix}_actual"]`).value) || 0;
            const pay = parseFloat(document.querySelector(`[name="${prefix}_pay"]`).value) || 0;
            const due = actual - pay;
            document.querySelector(`[name="${prefix}_due"]`).value = due.toFixed(2);
            
            // Update totals
            updateTotals();
        }
    });
});

function updateTotals() {
    let totalActual = 0;
    let totalPay = 0;
    let totalDue = 0;
    
    // Calculate totals for all fee types
    document.querySelectorAll('input[name$="_actual"]').forEach(input => {
        totalActual += parseFloat(input.value) || 0;
    });
    
    document.querySelectorAll('input[name$="_pay"]').forEach(input => {
        totalPay += parseFloat(input.value) || 0;
    });
    
    document.querySelectorAll('input[name$="_due"]').forEach(input => {
        totalDue += parseFloat(input.value) || 0;
    });
    
    // Update the total fields
    document.querySelector('.total-section input:nth-child(1)').value = totalActual.toFixed(2);
    document.querySelector('.total-section input:nth-child(2)').value = totalPay.toFixed(2);
    document.querySelector('.total-section input:nth-child(3)').value = totalDue.toFixed(2);
}
</script>
</body>
</html>