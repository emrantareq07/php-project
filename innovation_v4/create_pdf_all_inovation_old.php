<?php
session_start();
require_once("config/config.php");
require_once("db/db.php");

// Check if user is logged in
// if(!isset($_SESSION["uid"]) && !isset($_COOKIE['user_login'])) {
//     header('location:login.php');
//     exit;
// }

// Include MPDF library
require_once __DIR__ . '/vendor/autoload.php';

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

$implemented_query = "SELECT COUNT(*) as total FROM innovation_tbl WHERE imple_status='বাস্তবায়িত'";
$implemented_result = mysqli_query($conn, $implemented_query);
$implemented = mysqli_fetch_assoc($implemented_result)['total'];

$ongoing_query = "SELECT COUNT(*) as total FROM innovation_tbl WHERE imple_status='চলমান'";
$ongoing_result = mysqli_query($conn, $ongoing_query);
$ongoing = mysqli_fetch_assoc($ongoing_result)['total'];

// Create PDF with proper Bengali font support
$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => 'A4-L',
    'margin_left' => 10,
    'margin_right' => 10,
    'margin_top' => 35,
    'margin_bottom' => 25,
    'default_font' => 'nikosh', // FreeSerif supports Bengali
    'tempDir' => __DIR__ . '/tmp'
]);

// Set header
$mpdf->SetHTMLHeader('
<div style="border-bottom: 2px solid #6f42c1; padding: 5px 0;">
    <table width="100%" style="border-collapse: collapse;">
        <tr>
            <td width="15%" style="text-align: left;">
                <img src="images/bcic_logo.png" width="70">
            </td>
            <td width="85%" style="text-align: center;">
                <h2 style="color: #6f42c1; margin: 0; font-size: 18px;">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h2>
                <h4 style="color: #495057; margin: 5px 0; font-size: 14px;">Bangladesh Chemical Industries Corporation</h4>
                <p style="color: #6c757d; margin: 0; font-size: 11px;">বিসিআইসি ভবন, ৩০-৩১ দিলকুশা বা/এ, ঢাকা-১০০০</p>
            </td>
        </tr>
    </table>
</div>
');

// Set footer
$mpdf->SetHTMLFooter('
<div style="border-top: 1px solid #dee2e6; padding-top: 5px; font-size: 9pt; color: #6c757d;">
    <table width="100%">
        <tr>
            <td width="33%">প্রতিবেদন তৈরি: ' . date('d-m-Y h:i A') . '</td>
            <td width="33%" align="center">পৃষ্ঠা {PAGENO} / {nbpg}</td>
            <td width="34%" align="right">বিসিআইসি আইসিটি বিভাগ</td>
        </tr>
    </table>
</div>
');

// Build HTML content with proper table structure
$html = '
<style>
    body {
        font-family: nikosh, "Noto Sans Bengali", sans-serif;
        line-height: 1.4;
    }
    h2 {
        color: #6f42c1;
        text-align: center;
        margin: 15px 0;
        font-size: 18px;
    }
   .stats-container {
    margin: 15px 0;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    display: flex;                 /* flex layout */
    justify-content: space-around; /* spread evenly */
    align-items: center;           /* vertical alignment */
}

.stats-box {
    flex: 1;                       /* each box takes equal space */
    margin: 0 10px;                /* spacing between boxes */
    padding: 10px 20px;
    border: 1px solid #ccc;        /* optional styling */
    text-align: center;
    border-radius: 6px;            /* optional rounded corners */
}

    .stats-label {
        color: #495057;
        font-size: 11px;
        margin: 3px 0;
    }
    .stats-value {
        color: #6f42c1;
        font-size: 22px;
        font-weight: bold;
        margin: 0;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
        font-size: 9px;
        table-layout: fixed;
    }
    th {
        background: #6f42c1;
        color: white;
        padding: 8px 3px;
        text-align: center;
        font-weight: 600;
        font-size: 9px;
        word-wrap: break-word;
    }
    td {
        padding: 6px 3px;
        border: 1px solid #dee2e6;
        vertical-align: top;
        word-wrap: break-word;
    }
    tr:nth-child(even) {
        background: #f8f9fa;
    }
    .status-badge {
        padding: 2px 5px;
        border-radius: 10px;
        font-size: 8px;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }
    .status-implemented {
        background: #d4edda;
        color: #155724;
    }
    .status-ongoing {
        background: #fff3cd;
        color: #856404;
    }
    .serial-no {
        text-align: center;
        font-weight: bold;
    }
    .text-center {
        text-align: center;
    }
    .text-left {
        text-align: left;
    }
</style>

<h2>বাস্তবায়িত উদ্ভাবনী ধারণা, সহজিকৃত ও ডিজিটাইজকৃত সেবার ডাটাবেজ</h2>

<!-- Statistics -->
<div class="stats-container">
    <div class="stats-box">
        <div class="stats-label">মোট উদ্ভাবন : ' . $total . ' বাস্তবায়িত : ' . $implemented . ' চলমান : ' . $ongoing . '</div>
        
    </div>

</div>

<!-- Innovations Table -->
<table>
    <thead>
        <tr>
            <th width="4%">ক্রমিক</th>
            <th width="8%">অর্থবছর</th>
            <th width="14%">শিরোনাম</th>
            <th width="10%">উদ্ভাবক</th>
            <th width="10%">পদবী</th>
            <th width="10%">কর্মস্থল</th>
            <th width="8%">অবস্থা</th>
            <th width="8%">যোগ্যতা</th>
            <th width="8%">ফলাফল</th>
            <th width="10%">বর্ণনা</th>
            <th width="5%">লিংক</th>
            <th width="5%">মন্তব্য</th>
        </tr>
    </thead>
    <tbody>';

$sl = 1;
while ($row = mysqli_fetch_assoc($result)) {
    
    // Determine status class
    $status_class = ($row['imple_status'] == 'বাস্তবায়িত') ? 'status-implemented' : 'status-ongoing';
    
    // Truncate long text
    $title = mb_substr($row['title_of_invention'], 0, 35) . (mb_strlen($row['title_of_invention']) > 35 ? '...' : '');
    $inventor = mb_substr($row['inventors_name'], 0, 20) . (mb_strlen($row['inventors_name']) > 20 ? '...' : '');
    $designation = mb_substr($row['inventors_designation'], 0, 20) . (mb_strlen($row['inventors_designation']) > 20 ? '...' : '');
    $description = mb_substr($row['des_of_invention'], 0, 40) . (mb_strlen($row['des_of_invention']) > 40 ? '...' : '');
    
    $html .= '<tr>
        <td class="serial-no">' . $sl++ . '</td>
        <td class="text-center">' . htmlspecialchars($row['fiscal_year']) . '</td>
        <td class="text-left">' . htmlspecialchars($title) . '</td>
        <td class="text-left">' . htmlspecialchars($inventor) . '</td>
        <td class="text-left">' . htmlspecialchars($designation) . '</td>
        <td class="text-left">' . htmlspecialchars($row['proposed_workplace']) . '</td>
        <td class="text-center">
            <span class="status-badge ' . $status_class . '">' . htmlspecialchars($row['imple_status']) . '</span>
        </td>
        <td class="text-center">' . htmlspecialchars($row['replicate_eligibility']) . '</td>
        <td class="text-center">' . htmlspecialchars($row['feedback']) . '</td>
        <td class="text-left">' . htmlspecialchars($description) . '</td>
        <td class="text-center">';
    
    if (!empty($row['service_link'])) {
        $html .= '<a href="' . htmlspecialchars($row['service_link']) . '" style="color: #6f42c1; text-decoration: none;">Link</a>';
    } else {
        $html .= '-';
    }
    
    $html .= '</td>
        <td class="text-left">' . htmlspecialchars($row['remarks']) . '</td>
    </tr>';
}

$html .= '</tbody></table>';

// Summary row
$html .= '<div style="text-align: right; margin-top: 10px; font-size: 10px; font-weight: bold;">
    মোট রেকর্ড: ' . ($sl-1) . ' টি
</div>';

// Footer note
$html .= '<div style="margin-top: 20px; padding: 8px; font-size: 8px; color: #6c757d; text-align: center; border-top: 1px dashed #dee2e6;">
    <strong>Design & Developed By:</strong> Md. Tareq Emran, Programmer, ICT Division, BCIC<br>
    <strong>প্রতিবেদনের ধরন:</strong> সকল উদ্ভাবনের তালিকা | <strong>প্রিন্টের সময়:</strong> ' . date('d-m-Y h:i A') . '
</div>';

// Write HTML to PDF
$mpdf->WriteHTML($html);

// Set PDF metadata
$mpdf->SetTitle('BCIC Innovation Database - All Innovations');
$mpdf->SetAuthor('Md. Tareq Emran, ICT Division, BCIC');
$mpdf->SetCreator('BCIC Innovation Database');
$mpdf->SetDisplayMode('fullpage');

// Output PDF
$mpdf->Output('BCIC_Innovations_All_' . date('Y-m-d') . '.pdf', 'I');

mysqli_close($conn);
?>