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
    die("Invalid receipt ID");
}

try {
    // Connect to database
    $conn = new PDO("mysql:host=localhost;dbname=morning_glory_db", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Get fee details
    $stmt = $conn->prepare("SELECT f.*, s.std_name, s.fathers_name, s.mothers_name, s.address 
                           FROM fee_mgtm_tbl f 
                           JOIN std_tbl s ON f.reg_no = s.reg_no 
                           WHERE f.id = ?");
    $stmt->execute([$id]);
    $fee = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$fee) {
        die("Fee record not found");
    }

    // Format dates
    $entry_date = date('d M Y', strtotime($fee['entry_date']));
    $receipt_no = str_pad($fee['id'], 6, '0', STR_PAD_LEFT);

    // Prepare data for display
    $student_info = [
        'Name' => $fee['std_name'],
        'Father' => $fee['fathers_name'],
        'Mother' => $fee['mothers_name'],
        'Address' => $fee['address'],
        'Class' => $fee['class'],
        'Section' => $fee['section'],
        'Roll No' => $fee['roll_no']
    ];

    // Prepare fee breakdown
    $fee_breakdown = [];

    // Tuition Fees
    if(!empty($fee['tution_months'])) {
        $months = explode(',', $fee['tution_months']);
        $actuals = explode(',', $fee['tution_fee_actual']);
        $pays = explode(',', $fee['tution_fee_pay']);
        
        foreach($months as $i => $month) {
            if(!empty($month) && ($pays[$i] ?? 0) > 0) {
                $fee_breakdown[] = [
                    'description' => "Tuition Fee - " . $month,
                    'amount' => $pays[$i] ?? 0
                ];
            }
        }
    }

    // Exam Fees
    if(!empty($fee['exam_terms'])) {
        $terms = explode(',', $fee['exam_terms']);
        $pays = explode(',', $fee['exam_fee_pay']);
        
        foreach($terms as $i => $term) {
            if(!empty($term) && ($pays[$i] ?? 0) > 0) {
                $fee_breakdown[] = [
                    'description' => "Exam Fee - " . $term,
                    'amount' => $pays[$i] ?? 0
                ];
            }
        }
    }

    // Other Fees
    $other_fees = [
        'Admission Fee' => $fee['admission_fee_pay'],
        'Board Registration' => $fee['board_reg_fee_pay'],
        'Scholarship Fee' => $fee['scholarship_fee_pay'],
        'Transfer Fee' => $fee['transfer_fee_pay'],
        'Late Fee' => $fee['late_fee_pay'],
        'Annual Session' => $fee['annual_session_fee_pay'],
        'Books' => $fee['books_pay'],
        'Khata' => $fee['khata_pay'],
        'Diary' => $fee['diary_pay'],
        'Stationary' => $fee['stationary_printing_pay'],
        'Poor Fund' => $fee['poor_fund_pay'],
        'Others' => $fee['others_pay']
    ];

    foreach($other_fees as $desc => $amount) {
        if($amount > 0) {
            $fee_breakdown[] = [
                'description' => $desc,
                'amount' => $amount
            ];
        }
    }

    // Calculate total paid
    $total_paid = $fee['total_pay'];

} catch(PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Receipt - <?= $receipt_no ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .receipt-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .school-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .receipt-title {
            font-size: 20px;
            margin: 10px 0;
        }
        .receipt-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .student-info {
            margin-bottom: 20px;
        }
        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }
        .student-info td {
            padding: 5px;
            vertical-align: top;
        }
        .fee-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .fee-table th, .fee-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .fee-table th {
            background-color: #f2f2f2;
        }
        .total-row {
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            text-align: center;
            width: 200px;
            border-top: 1px dashed #333;
            padding-top: 5px;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
            .receipt-container {
                border: none;
                box-shadow: none;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <div class="school-name">NEW MORNING GLORY SCHOOL</div>
            <div>123 School Road, City, State - 123456</div>
            <div>Phone: +91 9876543210 | Email: info@morninggloryschool.com</div>
            <div class="receipt-title">FEE PAYMENT RECEIPT</div>
        </div>

        <div class="receipt-info">
            <div><strong>Receipt No:</strong> <?= $receipt_no ?></div>
            <div><strong>Date:</strong> <?= $entry_date ?></div>
        </div>

        <div class="student-info">
            <table>
                <tr>
                    <td><strong>Student Name:</strong> <?= htmlspecialchars($fee['name']) ?></td>
                    <td><strong>Registration No:</strong> <?= htmlspecialchars($fee['reg_no']) ?></td>
                </tr>
                <tr>
                    <td><strong>Father's Name:</strong> <?= htmlspecialchars($fee['fathers_name']) ?></td>
                    <td><strong>Class:</strong> <?= htmlspecialchars($fee['class']) ?> (<?= htmlspecialchars($fee['section']) ?>)</td>
                </tr>
                <tr>
                    <td><strong>Mother's Name:</strong> <?= htmlspecialchars($fee['mothers_name']) ?></td>
                    <td><strong>Roll No:</strong> <?= htmlspecialchars($fee['roll_no']) ?></td>
                </tr>
                <tr>
                    <td colspan="2"><strong>Address:</strong> <?= htmlspecialchars($fee['address']) ?></td>
                </tr>
            </table>
        </div>

        <table class="fee-table">
            <thead>
                <tr>
                    <th width="70%">Description</th>
                    <th width="30%">Amount (৳)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($fee_breakdown as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['description']) ?></td>
                    <td><?= number_format($item['amount'], 2) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td><strong>TOTAL PAID</strong></td>
                    <td><strong>৳<?= number_format($total_paid, 2) ?></strong></td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <div>
                <strong>Payment Mode:</strong> Cash<br>
                <strong>Received By:</strong> School Office
            </div>
            <div class="signature">
                Authorized Signature
            </div>
        </div>

        <div class="no-print" style="margin-top: 20px; text-align: center;">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Receipt
            </button>
            <button onclick="window.close()" class="btn btn-secondary" style="margin-left: 10px;">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        // Auto-print when page loads (optional)
        window.onload = function() {
            // Uncomment next line to auto-print
            // window.print();
        };
    </script>
</body>
</html>