<?php
session_start();
require_once("../db/db.php");

// Check if user is logged in
if (!isset($_SESSION['emp_id'])) {
    die("Unauthorized access");
}

// Get filter parameters
$filter_fiscal_year = isset($_GET['fiscal_year']) ? mysqli_real_escape_string($conn, $_GET['fiscal_year']) : '';
$filter_status = isset($_GET['idea_status']) ? mysqli_real_escape_string($conn, $_GET['idea_status']) : '';

// Build the WHERE clause based on filters
$where_clause = "WHERE 1=1";

if (!empty($filter_fiscal_year)) {
    $where_clause .= " AND fiscal_year = '$filter_fiscal_year'";
}

if (!empty($filter_status)) {
    if ($filter_status == 'submitted idea' || $filter_status == 'primarily selected' || $filter_status == 'final selected') {
        $where_clause .= " AND status = '$filter_status'";
    } else {
        $where_clause .= " AND imple_status = '$filter_status'";
    }
}

// Get filtered data
$query = "SELECT * FROM tbl_innovation $where_clause ORDER BY id DESC";
$result = mysqli_query($conn, $query);

// Function to convert to Bengali numbers
function convertToBengaliNumber($number) {
    $english = array('0','1','2','3','4','5','6','7','8','9');
    $bengali = array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
    return str_replace($english, $bengali, $number);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtered Innovations Report - BCIC</title>
    <style>
        * {
            font-family: 'Hind Siliguri', Arial, sans-serif;
        }
        body {
            padding: 30px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #667eea;
            margin: 10px 0;
        }
        .filter-info {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 12px;
        }
        th {
            background: #667eea;
            color: white;
            padding: 12px 8px;
            text-align: left;
        }
        td {
            padding: 10px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .status-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #6b7280;
            font-size: 12px;
        }
        @media print {
            body {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="../images/bcic_logo.png" alt="BCIC Logo" height="60">
        <h1>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h1>
        <h3>Bangladesh Chemical Industries Corporation</h3>
        <p>Innovation Database - Filtered Report</p>
    </div>
    
    <div class="filter-info">
        <strong>Applied Filters:</strong><br>
        <?php if (!empty($filter_fiscal_year)): ?>
            <span>Fiscal Year: <?php echo $filter_fiscal_year; ?></span><br>
        <?php endif; ?>
        <?php if (!empty($filter_status)): ?>
            <span>Status: <?php echo $filter_status; ?></span><br>
        <?php endif; ?>
        <?php if (empty($filter_fiscal_year) && empty($filter_status)): ?>
            <span>All Records (No Filters Applied)</span>
        <?php endif; ?>
        <br>
        <strong>Total Records:</strong> <?php echo mysqli_num_rows($result); ?>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Fiscal Year</th>
                <th>Inventor</th>
                <th>Designation</th>
                <th>Mobile</th>
                <th>Status</th>
                <th>Prize</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sl = 1;
            if ($result && mysqli_num_rows($result) > 0): 
                while($row = mysqli_fetch_assoc($result)): 
            ?>
            <tr>
                <td><?php echo convertToBengaliNumber($sl++); ?></td>
                <td><?php echo htmlspecialchars($row['title_of_idea']); ?></td>
                <td><?php echo htmlspecialchars($row['fiscal_year']); ?></td>
                <td><?php echo htmlspecialchars($row['fullname']); ?></td>
                <td><?php echo htmlspecialchars($row['designation']); ?></td>
                <td><?php echo htmlspecialchars($row['mobile_no']); ?></td>
                <td>
                    <?php 
                    $display_status = !empty($row['status']) ? $row['status'] : $row['imple_status'];
                    echo htmlspecialchars($display_status); 
                    ?>
                </td>
                <td><?php echo ($row['prize'] == 'yes') ? 'Yes ('.$row['prize_amount'].')' : 'No'; ?></td>
            </tr>
            <?php 
                endwhile; 
            else: 
            ?>
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px;">
                    No records found matching the selected filters.
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <div class="footer">
        <p>Generated on: <?php echo date('d-m-Y h:i A'); ?></p>
        <p>Design & Developed By: Md. Tareq Emran, Programmer, ICT Division, BCIC</p>
    </div>
    
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>