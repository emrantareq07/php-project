<?php
session_start();
require_once("config/config.php");
require_once("db/db.php");

// Check if user is logged in
// if(!isset($_SESSION["uid"]) && !isset($_COOKIE['user_login'])) {
//     header('location:login.php');
//     exit;
// }

// Database connection
$conn = mysqli_connect('localhost', 'root', '', 'innovation_db');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");

// Get all innovations
$query = "SELECT * FROM innovation_tbl ORDER BY id DESC";
$result = mysqli_query($conn, $query);

// Get statistics
$total_query = "SELECT COUNT(*) as total FROM innovation_tbl";
$total_result = mysqli_query($conn, $total_query);
$total = mysqli_fetch_assoc($total_result)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print All Innovations - BCIC</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Hind Siliguri', sans-serif;
        }
        
        body {
            background: #fff;
            padding: 20px;
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #6f42c1;
        }
        
        .print-header h1 {
            color: #6f42c1;
            font-size: 24px;
            font-weight: 700;
            margin: 10px 0;
        }
        
        .print-header h3 {
            color: #495057;
            font-size: 18px;
            margin: 5px 0;
        }
        
        .print-header p {
            color: #6c757d;
            margin: 5px 0;
        }
        
        .stats-row {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        
        .stat-box {
            text-align: center;
            flex: 1;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 12px;
            margin: 5px 0;
        }
        
        .stat-value {
            color: #6f42c1;
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 12px;
        }
        
        th {
            background: #6f42c1;
            color: white;
            padding: 12px 8px;
            text-align: center;
            font-weight: 600;
        }
        
        td {
            padding: 10px 8px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }
        
        tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        
        .print-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px dashed #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 11px;
        }
        
        .no-print {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        .btn-print {
            background: #6f42c1;
            color: white;
            padding: 12px 25px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 5px 15px rgba(111, 66, 193, 0.3);
            border: none;
            cursor: pointer;
        }
        
        .btn-print:hover {
            background: #5a32a3;
            color: white;
        }
        
        @media print {
            .no-print {
                display: none;
            }
            
            body {
                padding: 0.5in;
            }
            
            th {
                background: #6f42c1 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .badge-success, .badge-warning {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            <i class="fas fa-print me-2"></i> Print / Save PDF
        </button>
        <button onclick="window.close()" class="btn-print" style="background: #6c757d; margin-left: 10px;">
            <i class="fas fa-times me-2"></i> Close
        </button>
    </div>
    
    <div class="print-header">
        <img src="images/bcic_logo.png" alt="BCIC Logo" height="80">
        <h1>বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h1>
        <h3>Bangladesh Chemical Industries Corporation</h3>
        <p>বিসিআইসি ভবন, ৩০-৩১ দিলকুশা বা/এ, ঢাকা-১০০০</p>
        <h2 style="color: #6f42c1; margin-top: 20px;">বাস্তবায়িত উদ্ভাবনী ধারণা, সহজিকৃত ও ডিজিটাইজকৃত সেবার ডাটাবেজ</h2>
    </div>
    
    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-label">মোট উদ্ভাবন</div>
            <div class="stat-value"><?php echo $total; ?></div>
        </div>
        <div class="stat-box">
            <div class="stat-label">প্রতিবেদন তারিখ</div>
            <div class="stat-value"><?php echo date('d-m-Y'); ?></div>
        </div>
        <div class="stat-box">
            <div class="stat-label">সময়</div>
            <div class="stat-value"><?php echo date('h:i A'); ?></div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th width="5%">ক্রমিক</th>
                <th width="8%">অর্থবছর</th>
                <th width="15%">শিরোনাম</th>
                <th width="12%">উদ্ভাবক</th>
                <th width="10%">পদবী</th>
                <th width="10%">কর্মস্থল</th>
                <th width="8%">অবস্থা</th>
                <th width="8%">যোগ্যতা</th>
                <th width="8%">ফলাফল</th>
                <th width="8%">লিংক</th>
                <th width="8%">মন্তব্য</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sl = 1;
            while($row = mysqli_fetch_assoc($result)) { 
                $status_class = ($row['imple_status'] == 'বাস্তবায়িত') ? 'badge-success' : 
                               (($row['imple_status'] == 'চলমান') ? 'badge-warning' : 'badge-secondary');
            ?>
            <tr>
                <td style="text-align: center;"><?php echo $sl++; ?></td>
                <td style="text-align: center;"><?php echo htmlspecialchars($row['fiscal_year']); ?></td>
                <td><?php echo htmlspecialchars($row['title_of_invention']); ?></td>
                <td><?php echo htmlspecialchars($row['inventors_name']); ?></td>
                <td><?php echo htmlspecialchars($row['inventors_designation']); ?></td>
                <td><?php echo htmlspecialchars($row['proposed_workplace']); ?></td>
                <td style="text-align: center;">
                    <span class="<?php echo $status_class; ?>"><?php echo htmlspecialchars($row['imple_status']); ?></span>
                </td>
                <td style="text-align: center;"><?php echo htmlspecialchars($row['replicate_eligibility']); ?></td>
                <td style="text-align: center;"><?php echo htmlspecialchars($row['feedback']); ?></td>
                <td style="text-align: center;">
                    <?php if(!empty($row['service_link'])): ?>
                        <a href="<?php echo htmlspecialchars($row['service_link']); ?>" target="_blank">Link</a>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['remarks']); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    
    <div class="print-footer">
        <p>
            <strong>Design & Developed By:</strong> Md. Tareq Emran, Programmer, ICT Division, BCIC<br>
            <strong>মোট রেকর্ড:</strong> <?php echo ($sl-1); ?> টি | 
            <strong>প্রিন্টের সময়:</strong> <?php echo date('d-m-Y h:i A'); ?> | 
            <strong>পৃষ্ঠা:</strong> 1
        </p>
    </div>
    
    <script>
        // Auto print dialog when page loads
        window.onload = function() {
            // Uncomment the line below if you want auto-print
            // window.print();
        }
    </script>
</body>
</html>
<?php
mysqli_close($conn);
?>