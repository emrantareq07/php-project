<?php
// print_transport_request.php
session_name('factory_work_request_db');
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: incoming_w_req_transport.php");
    exit;
}

$transport_id = intval($_GET['id']);

$conn = new mysqli('localhost', 'root', '', 'factory_work_request_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user info
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? 'user';

// Get transport request details
$stmt = $conn->prepare("SELECT * FROM transport_w_req_tbl WHERE id = ?");
$stmt->bind_param("i", $transport_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    header("Location: incoming_w_req_transport.php");
    exit;
}

$transport = $result->fetch_assoc();
$stmt->close();

// Check access permissions
$has_access = false;
if ($user_role === 'admin' || $user_role === 'sadmin'|| $user_role === 'user') {
    $has_access = true;
} elseif (isset($transport['user_id']) && $transport['user_id'] == $user_id) {
    $has_access = true;
}

if (!$has_access) {
    $conn->close();
    header("Location: incoming_w_req_transport.php?error=access_denied");
    exit;
}

$conn->close();

// Get current date in Bengali format (approximate)
function getBanglaDate($date) {
    $bangla_months = [
        'জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন',
        'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'
    ];
    
    $month = date('n', strtotime($date)) - 1;
    $day = date('j', strtotime($date));
    $year = date('Y', strtotime($date));
    
    return $day . ' ' . $bangla_months[$month] . ', ' . $year;
}

// Get time in Bengali format
function getBanglaTime($time) {
    $time_str = date('h:i A', strtotime($time));
    $time_str = str_replace('AM', 'সকাল', $time_str);
    $time_str = str_replace('PM', 'বিকাল', $time_str);
    return $time_str;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Transport Request - TR-<?php echo str_pad($transport_id, 6, '0', STR_PAD_LEFT); ?></title>
    
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        
        body {
            font-family: 'Nikosh', 'SolaimanLipi', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: white;
            color: black;
            font-size: 14pt;
            line-height: 1.4;
        }
        
        .print-container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20px;
            box-sizing: border-box;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 24pt;
            margin: 0 0 5px 0;
            font-weight: bold;
        }
        
        .header h2 {
            font-size: 18pt;
            margin: 0;
            font-weight: bold;
            color: #333;
        }
        
        .form-title {
            text-align: center;
            font-size: 20pt;
            font-weight: bold;
            margin: 20px 0;
            text-decoration: underline;
        }
        
        .section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 5px;
        }
        
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .form-table th {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            background-color: #f0f0f0;
            width: 25%;
        }
        
        .form-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            width: 25%;
        }
        
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 40px;
        }
        
        .signature-table td {
            border: 1px solid #000;
            padding: 15px 10px;
            text-align: center;
            vertical-align: top;
            height: 80px;
        }
        
        .signature-table .signature-line {
            border-top: 1px solid #000;
            width: 80%;
            margin: 10px auto 0px auto;
        }
        
        .section-divider {
            border-top: 2px dashed #000;
            margin: 30px 0;
        }
        
        .security-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        
        .security-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .security-table th, .security-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        
        .security-table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            font-size: 10pt;
            color: #666;
        }
        
        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 5px;
        }
        
        .checked {
            background-color: #000;
        }
        
        .bangla-text {
            font-family: 'Nikosh', 'SolaimanLipi', sans-serif;
        }
        
        .english-text {
            font-family: 'Arial', sans-serif;
        }
        
        .request-id {
            text-align: right;
            font-size: 12pt;
            margin-bottom: 10px;
        }
        
        .status-box {
            float: right;
            border: 2px solid #000;
            padding: 5px 10px;
            font-size: 12pt;
            margin-top: -30px;
        }
        
        @media print {
            body {
                font-size: 12pt;
            }
            
            .no-print {
                display: none !important;
            }
            
            .print-container {
                margin: 0;
                padding: 15mm;
                width: 100%;
            }
            
            .page-break {
                page-break-before: always;
            }
        }
    </style>
    
    <!-- Bengali Fonts -->
    <link href="https://fonts.rabby.ai/css?family=Nikosh" rel="stylesheet">
    <link href="https://fonts.rabby.ai/css?family=SolaimanLipi" rel="stylesheet">
</head>
<body>
    <div class="print-container">
        <!-- Back button for non-print -->
        <div class="no-print" style="text-align: center; margin-bottom: 20px;">
            <button onclick="window.history.back()" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
                ← Back
            </button>
            <button onclick="window.print()" style="padding: 10px 20px; background: #2196F3; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                🖨️ Print
            </button>
        </div>
        
        <!-- Request ID and Status -->
        <div class="request-id">
            Request ID: TR-<?php echo str_pad($transport_id, 6, '0', STR_PAD_LEFT); ?>
        </div>
        
        <div class="status-box">
            Status: 
            <?php 
            $status_text = '';
            $status_class = '';
            switch($transport['approval_status']) {
                case 'approved': 
                    $status_text = 'অনুমোদিত'; 
                    $status_class = 'approved';
                    break;
                case 'rejected': 
                    $status_text = 'প্রত্যাখ্যাত'; 
                    $status_class = 'rejected';
                    break;
                default: 
                    $status_text = 'মুলতুবি'; 
                    $status_class = 'pending';
                    break;
            }
            echo '<span class="' . $status_class . '">' . $status_text . '</span>';
            ?>
        </div>
        
        <!-- Header -->
        <div class="header">
            <h1 class="bangla-text">কারখানা পরিচালনা বিভাগ</h1>
            <h2 class="bangla-text">Factory Management Department</h2>
        </div>
        
        <!-- Form Title -->
        <div class="form-title bangla-text">
            যানবাহন রিকুইজিসন ফরম<br>
            Vehicle Requisition Form
        </div>
        
        <!-- User Information Section -->
        <div class="section">
            <div class="section-title bangla-text">ব্যবহারকারীর তথ্য / User Information</div>
            <table class="form-table">
                <tr>
                    <th class="bangla-text">ব্যবহারকারীর নাম<br>User Name</th>
                    <td class="bangla-text"><?php echo htmlspecialchars($transport['full_name']); ?></td>
                    <th class="bangla-text">পদবী<br>Designation</th>
                    <td class="bangla-text"><?php echo htmlspecialchars($transport['designation']); ?></td>
                </tr>
                <tr>
                    <th class="bangla-text">বিভাগ/শাখা<br>Division/Section</th>
                    <td class="bangla-text"><?php echo htmlspecialchars($transport['division'] ?? 'N/A'); ?></td>
                    <th class="bangla-text">কর্মকর্তা নং (মোবাইল)<br>Contact No (Mobile)</th>
                    <td class="bangla-text"><?php echo htmlspecialchars($transport['contact_no']); ?></td>
                </tr>
                <tr>
                    <th class="bangla-text">যাত্রার তারিখ<br>Departure Date</th>
                    <td class="bangla-text"><?php echo getBanglaDate($transport['departure_date']); ?><br><?php echo date('d/m/Y', strtotime($transport['departure_date'])); ?></td>
                    <th class="bangla-text">যাত্রার সময়<br>Departure Time</th>
                    <td class="bangla-text"><?php echo getBanglaTime($transport['start_time']); ?> - <?php echo getBanglaTime($transport['end_time']); ?><br><?php echo date('h:i A', strtotime($transport['start_time'])) . ' - ' . date('h:i A', strtotime($transport['end_time'])); ?></td>
                </tr>
                <tr>
                    <th class="bangla-text">খেকে<br>From</th>
                    <td class="bangla-text" colspan="3"><?php echo htmlspecialchars($transport['visiting_place']); ?></td>
                </tr>
                <tr>
                    <th class="bangla-text">পর্যন্ত<br>To</th>
                    <td class="bangla-text" colspan="3"><?php echo htmlspecialchars($transport['destination']); ?></td>
                </tr>
                <tr>
                    <th class="bangla-text">যাত্রীর সংখ্যা<br>Number of Passengers</th>
                    <td class="bangla-text"><?php echo $transport['no_of_visitor']; ?> জন / persons</td>
                    <th class="bangla-text">ভ্রমণের উদ্দেশ্য<br>Purpose of Travel</th>
                    <td class="bangla-text"><?php echo htmlspecialchars($transport['purpose'] ?? 'দাপ্তরিক কাজ / Official Work'); ?></td>
                </tr>
                <tr>
                    <th class="bangla-text">রিপোর্টিং স্থান<br>Reporting Place</th>
                    <td class="bangla-text">প্রধান গেট / Main Gate</td>
                    <th class="bangla-text">লাভেরিক হলে<br>If Overtime</th>
                    <td class="bangla-text">
                        <span class="checkbox <?php echo (!empty($transport['overtime']) && $transport['overtime'] == 'Yes') ? 'checked' : ''; ?>"></span> হ্যাঁ / Yes
                        <span style="margin-left: 20px;" class="checkbox <?php echo (empty($transport['overtime']) || $transport['overtime'] == 'No') ? 'checked' : ''; ?>"></span> না / No
                    </td>
                </tr>
            </table>
        </div>
            <table class="signature-table">
                <tr>
                <td style="width: 25%;">
                        <div class="bangla-text">বিভাগীয় প্রধান/Section Head </div>
                        <div class="signature-line"><?php echo ($transport['approval_status']); ?></div>
                        <div class="english-text">Signature & Seal</div>
                    </td>
                    <td style="width: 25%;">
                        <div class="bangla-text">ব্যবহারকারীর স্বাক্ষর <br>(Signed)</div>
                        <div class="signature-line"><?php echo ($transport['full_name']); ?></div>
                        <div class="english-text">Signature</div>
                    </td>

                </tr>
        </table>
        <!-- Approval Section -->
        <div class="section">
            <div class="section-title bangla-text">অনুমোদন / Approval</div>
            <table class="signature-table">
                <tr>
                   
                    <td style="width: 25%;">
                        <div class="bangla-text">
                            গাড়ী সরবরাহ করা যাবে/যাবেনা<br>
                            Vehicle can be provided / cannot be provided
                        </div>
                        <div style="margin-top: 10px;">
                            <span class="checkbox <?php echo ($transport['v_provide_status'] == 'Yes') ? 'checked' : ''; ?>"></span> যাবে / Yes
                            <span style="margin-left: 20px;" class="checkbox <?php echo ($transport['v_provide_status'] == 'No') ? 'checked' : ''; ?>"></span> যাবেনা / No
                        </div>
                    </td>
                    <td style="width: 25%;">
                        <div class="bangla-text">
                            অনুমোদন করা হল/হলনা<br>
                            Approved / Not Approved
                        </div>
                        <div style="margin-top: 10px;">
                            <span class="checkbox <?php echo ($transport['approval_status'] == 'approved') ? 'checked' : ''; ?>"></span> হল / Yes
                            <span style="margin-left: 20px;" class="checkbox <?php echo ($transport['approval_status'] == 'rejected') ? 'checked' : ''; ?>"></span> হলনা / No
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="bangla-text">প্রশাসন বিভাগীয় প্রধান<br>Administration Division Head</div>
                        <div class="signature-line"></div>
                        <div class="english-text">Signature & Seal</div>
                    </td>
                    <td>
                        <div class="bangla-text">ব্যবস্থাপনা পরিচালক<br>Managing Director</div>
                        <div class="signature-line"></div>
                        <div class="english-text">Signature & Seal</div>
                    </td>
                   
                </tr>
            </table>
        </div>
        
        <div class="section-divider"></div>

<!-- transport Section -->
<div class="transport-section" >
    <center>
    <div class="section-title bangla-text">যানবাহন শাখা / Transport Section</div>
    
    <div class="bangla-text">(দপ্তর কর্তৃক পূরণীয়)<br></div></center>
    
    <table class="transport-table" style="margin: 0 auto; ">
        <tr>
            <th class="bangla-text">
                চালকের নাম:
                <?php echo htmlspecialchars($transport['driver_name'] ?? '_______________'); ?>
            </th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th class="bangla-text">
                গাড়ীর নম্বর: 
                 <?php echo htmlspecialchars($transport['vehicle_no'] ?? '_______________'); ?>
            </th>
        </tr>
    </table>
</div>

        <div class="section-divider"></div>
        <!-- Security Section -->
        <div class="security-section">
            <div class="section-title bangla-text">নিরাপত্তা শাখা / Security Section</div>
            <table class="security-table">
                <tr>
                    <th class="bangla-text">চালকের নাম<br>Driver Name</th>
                    <th class="bangla-text">গাড়ীর নম্বর<br>Vehicle Number</th>
                    <th class="bangla-text">ব্যবহারকারীর নাম<br>User Name</th>
                    <th class="bangla-text">পদবী<br>Designation</th>
                    <th class="bangla-text">বাহির হওয়ার সময়<br>Exit Time</th>
                    <th class="bangla-text">ফিরে আসার সময়<br>Return Time</th>
                </tr>
                <tr>
                    <td><?php echo htmlspecialchars($transport['driver_name'] ?? '_______________'); ?></td>
                    <td><?php echo htmlspecialchars($transport['vehicle_no'] ?? '_______________'); ?></td>
                    <td><?php echo htmlspecialchars($transport['full_name']); ?></td>
                    <td><?php echo htmlspecialchars($transport['designation']); ?></td>
                    <td>
                        <?php 
                        if (!empty($transport['vehicle_exit_time'])) {
                            echo date('d/m/Y h:i A', strtotime($transport['vehicle_exit_time']));
                        } else {
                            echo '_______________';
                        }
                        ?>
                    </td>
                    <td>
                        <?php 
                        if (!empty($transport['vehicle_entry_time'])) {
                            echo date('d/m/Y h:i A', strtotime($transport['vehicle_entry_time']));
                        } else {
                            echo '_______________';
                        }
                        ?>
                    </td>
                </tr>
            </table>
            
            <div style="margin-top: 20px;">
                <table style="width: 100%;">
                    <tr>
                        <td style="width: 50%; padding-right: 20px;">
                            <div class="bangla-text">প্রস্থানকালীন নিরাপত্তা প্রহরীর স্বাক্ষর<br>Security Guard Signature (Exit)</div>
                            <div style="border-top: 1px solid #000; margin-top: 40px; width: 80%;"></div>
                            <div class="english-text">Name: ___________________</div>
                        </td>
                        <td style="width: 50%;">
                            <div class="bangla-text">প্রবেশকালীন নিরাপত্তা প্রহরীর স্বাক্ষর<br>Security Guard Signature (Entry)</div>
                            <div style="border-top: 1px solid #000; margin-top: 40px; width: 80%;"></div>
                            <div class="english-text">Name: ___________________</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="bangla-text">
                নোট: ১. এই ফর্মটি যাত্রার কমপক্ষে ২৪ ঘণ্টা পূর্বে জমা দিতে হবে।<br>
                ২. ফেরত আসার পর এই ফর্মটি নিরাপত্তা শাখা কর্তৃক প্রশাসন বিভাগে জমা দিতে হবে।<br>
                ৩. যে কোন জরুরী প্রয়োজনে ০১৭XXXXXXX নম্বরে যোগাযোগ করুন।
            </div>
         <!--    <div class="english-text" style="margin-top: 10px;">
                Note: 1. This form must be submitted at least 24 hours before departure.<br>
                2. After return, this form must be submitted to Administration Department by Security Section.<br>
                3. For any emergency, contact 017XXXXXXX.
            </div> -->
            <div style="margin-top: 20px; font-style: italic;">
                Printed on: <?php echo date('d/m/Y h:i A'); ?> | Request ID: TR-<?php echo str_pad($transport_id, 6, '0', STR_PAD_LEFT); ?>
            </div>
        </div>
    </div>
    
    <script>
        // Auto print when page loads (optional)
        window.onload = function() {
            // Uncomment the line below if you want auto-print
            // window.print();
        };
        
        // Show message after print
        window.onafterprint = function() {
            alert('Print completed! You can now close this window or go back.');
        };
    </script>
</body>
</html>