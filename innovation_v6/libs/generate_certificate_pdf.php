<?php
session_name('innovation_db');
session_start();
require_once("../db/db.php");
$role=$_SESSION['role'];

// Check if user is logged in
if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit();
}

// Get innovation ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: all_innovations.php");
    exit();
}

$id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch innovation details
$query = "SELECT * FROM tbl_innovation WHERE id = '$id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: all_innovations.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

// Require TCPDF library
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

// Function to convert to Bengali numbers
function convertToBengaliNumber($number) {
    $english = array('0','1','2','3','4','5','6','7','8','9');
    $bengali = array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
    return str_replace($english, $bengali, $number);
}

// Get current date in Bengali
$bengaliMonths = array(
    'January' => 'জানুয়ারি',
    'February' => 'ফেব্রুয়ারি',
    'March' => 'মার্চ',
    'April' => 'এপ্রিল',
    'May' => 'মে',
    'June' => 'জুন',
    'July' => 'জুলাই',
    'August' => 'আগস্ট',
    'September' => 'সেপ্টেম্বর',
    'October' => 'অক্টোবর',
    'November' => 'নভেম্বর',
    'December' => 'ডিসেম্বর'
);

$currentDate = date('j') . ' ' . $bengaliMonths[date('F')] . ' ' . convertToBengaliNumber(date('Y'));

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator('BCIC Innovation Database');
$pdf->SetAuthor('BCIC');
$pdf->SetTitle('Certificate of Innovation - ' . $row['fullname']);
$pdf->SetSubject('Innovation Certificate');

// Remove default header/footer
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// Set margins
$pdf->SetMargins(15, 15, 15);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 15);

// Add a page
$pdf->AddPage('L', 'A4');

// Set background color
$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(0, 0, $pdf->getPageWidth(), $pdf->getPageHeight(), 'F');

// Draw decorative border
$pdf->SetLineStyle(array('width' => 2, 'color' => array(102, 126, 234)));
$pdf->Rect(10, 10, $pdf->getPageWidth()-20, $pdf->getPageHeight()-20, 'D');

// Draw corners
$pdf->SetLineStyle(array('width' => 3, 'color' => array(102, 126, 234)));
$cornerSize = 20;
// Top-left
$pdf->Line(15, 15, 15+$cornerSize, 15);
$pdf->Line(15, 15, 15, 15+$cornerSize);
// Top-right
$pdf->Line($pdf->getPageWidth()-15, 15, $pdf->getPageWidth()-15-$cornerSize, 15);
$pdf->Line($pdf->getPageWidth()-15, 15, $pdf->getPageWidth()-15, 15+$cornerSize);
// Bottom-left
$pdf->Line(15, $pdf->getPageHeight()-15, 15+$cornerSize, $pdf->getPageHeight()-15);
$pdf->Line(15, $pdf->getPageHeight()-15, 15, $pdf->getPageHeight()-15-$cornerSize);
// Bottom-right
$pdf->Line($pdf->getPageWidth()-15, $pdf->getPageHeight()-15, $pdf->getPageWidth()-15-$cornerSize, $pdf->getPageHeight()-15);
$pdf->Line($pdf->getPageWidth()-15, $pdf->getPageHeight()-15, $pdf->getPageWidth()-15, $pdf->getPageHeight()-15-$cornerSize);

// Set font for Bengali text
$pdf->SetFont('freesans', '', 12);

// Certificate Title
$pdf->SetY(30);
$pdf->SetFont('freesans', 'B', 36);
$pdf->SetTextColor(102, 126, 234);
$pdf->Cell(0, 20, 'CERTIFICATE OF INNOVATION', 0, 1, 'C');

$pdf->SetFont('freesans', '', 24);
$pdf->SetTextColor(118, 75, 162);
$pdf->Cell(0, 15, 'সার্টিফিকেট অব ইনোভেশন', 0, 1, 'C');

// Presented to
$pdf->SetY(80);
$pdf->SetFont('freesans', '', 18);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 10, 'This is proudly presented to', 0, 1, 'C');

// Recipient Name
$pdf->SetFont('freesans', 'B', 32);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(0, 20, $row['fullname'], 0, 1, 'C');

// Details box
$pdf->SetY(130);
$pdf->SetFillColor(247, 250, 252);
$pdf->SetTextColor(45, 55, 72);
$pdf->SetFont('freesans', '', 14);

// Draw details box
$pdf->SetX(40);
$pdf->Cell(0, 40, '', 0, 1, 'C', true);

// Designation
$pdf->SetXY(40, 135);
$pdf->SetFont('freesans', 'B', 14);
$pdf->SetTextColor(102, 126, 234);
$pdf->Cell(50, 10, 'Designation:', 0, 0, 'L');
$pdf->SetFont('freesans', '', 14);
$pdf->SetTextColor(45, 55, 72);
$pdf->Cell(0, 10, $row['designation'], 0, 1, 'L');

// Workplace
$pdf->SetX(40);
$pdf->SetFont('freesans', 'B', 14);
$pdf->SetTextColor(102, 126, 234);
$pdf->Cell(50, 10, 'Workplace:', 0, 0, 'L');
$pdf->SetFont('freesans', '', 14);
$pdf->SetTextColor(45, 55, 72);
$pdf->Cell(0, 10, $row['place_of_posting'], 0, 1, 'L');

// Mobile
$pdf->SetX(40);
$pdf->SetFont('freesans', 'B', 14);
$pdf->SetTextColor(102, 126, 234);
$pdf->Cell(50, 10, 'Mobile:', 0, 0, 'L');
$pdf->SetFont('freesans', '', 14);
$pdf->SetTextColor(45, 55, 72);
$pdf->Cell(0, 10, $row['mobile_no'], 0, 1, 'L');

// Innovation Title
$pdf->SetY(190);
$pdf->SetFont('freesans', '', 16);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 10, 'in recognition of the outstanding innovation titled', 0, 1, 'C');

$pdf->SetFont('freesans', 'B', 22);
$pdf->SetTextColor(118, 75, 162);
$pdf->MultiCell(0, 15, '"' . $row['title_of_idea'] . '"', 0, 'C', false, 1);

// Fiscal Year
$pdf->SetFont('freesans', '', 16);
$pdf->SetTextColor(80, 80, 80);
$pdf->Cell(0, 10, 'for the fiscal year ' . convertToBengaliNumber($row['fiscal_year']), 0, 1, 'C');

// Prize info if exists
if ($row['prize'] == 'yes' && !empty($row['prize_amount'])) {
    $pdf->SetY(260);
    $pdf->SetFillColor(254, 243, 199);
    $pdf->SetTextColor(146, 64, 14);
    $pdf->SetFont('freesans', 'B', 16);
    $pdf->SetX(50);
    $prizeText = 'Prize Amount: ৳' . $row['prize_amount'];
    if (!empty($row['rank'])) {
        $prizeText .= ' | Rank: ' . $row['rank'];
    }
    $pdf->Cell(0, 15, $prizeText, 0, 1, 'C', true);
}

// Signatures
$pdf->SetY(220);

// Left signature
$pdf->SetX(40);
$pdf->SetFont('freesans', '', 12);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(80, 5, '_______________________', 0, 0, 'C');
$pdf->SetX(120);
$pdf->Cell(80, 5, '_______________________', 0, 1, 'C');

$pdf->SetX(40);
$pdf->SetFont('freesans', 'B', 14);
$pdf->Cell(80, 10, 'Managing Director', 0, 0, 'C');
$pdf->SetX(120);
$pdf->Cell(80, 10, 'Director', 0, 1, 'C');

$pdf->SetX(40);
$pdf->SetFont('freesans', '', 12);
$pdf->Cell(80, 5, 'BCIC', 0, 0, 'C');
$pdf->SetX(120);
$pdf->Cell(80, 5, 'Innovation & Research', 0, 1, 'C');

// Footer
$pdf->SetY(260);
$pdf->SetFont('freesans', '', 10);
$pdf->SetTextColor(128, 128, 128);
$pdf->Cell(0, 5, 'Date: ' . $currentDate, 0, 0, 'L');
$certificateId = 'BCIC-INNOV-' . str_pad($row['id'], 6, '0', STR_PAD_LEFT) . '-' . $row['fiscal_year'];
$pdf->Cell(0, 5, 'Certificate ID: ' . $certificateId, 0, 1, 'R');

// Output PDF
$pdf->Output('Certificate_' . $row['fullname'] . '.pdf', 'I');
?>