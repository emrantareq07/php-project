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

// Get innovation ID from URL
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Innovation - <?php echo htmlspecialchars($row['title_of_idea']); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Hind+Siliguri:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Hind Siliguri', sans-serif;
        }
        
        /* Certificate Container */
        .certificate-container {
            max-width: 1100px;
            width: 100%;
            margin: 0 auto;
            animation: fadeInScale 0.8s ease-out;
        }
        
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Certificate Card */
        .certificate-card {
            background: white;
            border-radius: 40px;
            padding: 50px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 50px 100px rgba(0, 0, 0, 0.3);
        }
        
        /* Decorative Elements */
        .certificate-border {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 3px solid #667eea;
            border-radius: 30px;
            pointer-events: none;
            opacity: 0.3;
        }
        
        .certificate-corner {
            position: absolute;
            width: 150px;
            height: 150px;
            border: 5px solid #667eea;
            opacity: 0.2;
        }
        
        .corner-tl {
            top: 30px;
            left: 30px;
            border-right: none;
            border-bottom: none;
            border-radius: 30px 0 0 0;
        }
        
        .corner-tr {
            top: 30px;
            right: 30px;
            border-left: none;
            border-bottom: none;
            border-radius: 0 30px 0 0;
        }
        
        .corner-bl {
            bottom: 30px;
            left: 30px;
            border-right: none;
            border-top: none;
            border-radius: 0 0 0 30px;
        }
        
        .corner-br {
            bottom: 30px;
            right: 30px;
            border-left: none;
            border-top: none;
            border-radius: 0 0 30px 0;
        }
        
        /* Background Pattern */
        .certificate-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                radial-gradient(circle at 30% 40%, rgba(102, 126, 234, 0.03) 0%, transparent 30%),
                radial-gradient(circle at 70% 60%, rgba(118, 75, 162, 0.03) 0%, transparent 30%);
            pointer-events: none;
        }
        
        /* Certificate Header */
        .certificate-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
        }
        
        .certificate-icon {
            font-size: 80px;
            color: #667eea;
            margin-bottom: 20px;
            animation: floatIcon 3s ease-in-out infinite;
        }
        
        @keyframes floatIcon {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        .certificate-title {
            font-size: 60px;
            font-weight: 900;
            font-family: 'Playfair Display', serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }
        
        .certificate-subtitle {
            font-size: 24px;
            color: #4a5568;
            font-weight: 500;
            border-bottom: 2px dashed #e2e8f0;
            padding-bottom: 20px;
        }
        
        /* Certificate Content */
        .certificate-content {
            text-align: center;
            margin: 50px 0;
            position: relative;
        }
        
        .presented-to {
            font-size: 28px;
            color: #4a5568;
            margin-bottom: 20px;
            font-weight: 500;
        }
        
        .recipient-name {
            font-size: 52px;
            font-weight: 800;
            font-family: 'Playfair Display', serif;
            background: linear-gradient(135deg, #1a202c 0%, #4a5568 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 30px;
            line-height: 1.3;
            padding: 0 20px;
        }
        
        .recipient-details {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border-radius: 60px;
            padding: 30px;
            margin: 30px 0;
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .detail-item {
            margin: 15px 0;
            font-size: 22px;
            color: #2d3748;
        }
        
        .detail-label {
            font-weight: 700;
            color: #667eea;
            margin-right: 10px;
        }
        
        .detail-value {
            font-weight: 500;
        }
        
        .innovation-title {
            font-size: 32px;
            font-weight: 700;
            color: #764ba2;
            margin: 30px 0;
            padding: 20px;
            border-left: 5px solid #667eea;
            border-right: 5px solid #667eea;
            background: #faf5ff;
            border-radius: 20px;
        }
        
        .certificate-text {
            font-size: 20px;
            color: #4a5568;
            margin: 40px 0;
            line-height: 1.8;
            padding: 0 30px;
        }
        
        /* Signature Section */
        .signature-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 60px;
            padding: 0 20px;
        }
        
        .signature-box {
            text-align: center;
            flex: 1;
            max-width: 250px;
        }
        
        .signature-line {
            width: 200px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #667eea, transparent);
            margin: 10px auto;
        }
        
        .signature-name {
            font-family: 'Great Vibes', cursive;
            font-size: 32px;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .signature-title {
            font-size: 16px;
            color: #718096;
            font-weight: 500;
        }
        
        .signature-stamp {
            font-size: 60px;
            color: #667eea;
            opacity: 0.3;
            position: relative;
            top: -20px;
        }
        
        /* Date and ID */
        .certificate-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 40px;
            padding: 20px;
            border-top: 2px solid #e2e8f0;
        }
        
        .certificate-date {
            font-size: 18px;
            color: #4a5568;
        }
        
        .certificate-id {
            font-size: 16px;
            color: #a0aec0;
            font-family: monospace;
        }
        
        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 40px;
        }
        
        .btn-download {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .btn-download:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .btn-print {
            background: white;
            color: #667eea;
            padding: 15px 40px;
            border: 2px solid #667eea;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        
        .btn-print:hover {
            background: #f7fafc;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.2);
        }
        
        .btn-back {
            background: #cbd5e0;
            color: #4a5568;
            padding: 15px 40px;
            border: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        
        .btn-back:hover {
            background: #a0aec0;
            color: white;
        }
        
        /* Medal Animation */
        .medal-animation {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 100px;
            opacity: 0.1;
            animation: spin 10s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Print Styles */
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .action-buttons {
                display: none;
            }
            
            .certificate-card {
                box-shadow: none;
                border: 2px solid #e2e8f0;
                page-break-inside: avoid;
            }
            
            .certificate-title {
                -webkit-text-fill-color: #667eea;
                background: none;
            }
            
            .recipient-name {
                -webkit-text-fill-color: #1a202c;
                background: none;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .certificate-card {
                padding: 30px;
            }
            
            .certificate-title {
                font-size: 40px;
            }
            
            .recipient-name {
                font-size: 36px;
            }
            
            .innovation-title {
                font-size: 24px;
            }
            
            .signature-section {
                flex-direction: column;
                align-items: center;
                gap: 40px;
            }
            
            .signature-box {
                max-width: 100%;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .certificate-footer {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-container">
        <div class="certificate-card">
            <!-- Decorative Elements -->
            <div class="certificate-border"></div>
            <div class="certificate-corner corner-tl"></div>
            <div class="certificate-corner corner-tr"></div>
            <div class="certificate-corner corner-bl"></div>
            <div class="certificate-corner corner-br"></div>
            <div class="certificate-pattern"></div>
            
            <!-- Medal Animation -->
            <div class="medal-animation">
                <i class="fas fa-medal"></i>
            </div>
            
            <!-- Certificate Header -->
            <div class="certificate-header">
                <div class="certificate-icon">
                    <i class="fas fa-trophy"></i>
                </div>
                <h1 class="certificate-title">CERTIFICATE OF INNOVATION</h1>
                <h2 class="certificate-subtitle">সার্টিফিকেট অব ইনোভেশন</h2>
            </div>
            
            <!-- Certificate Content -->
            <div class="certificate-content">
                <div class="presented-to">This is proudly presented to</div>
                <div class="recipient-name"><?php echo htmlspecialchars($row['fullname']); ?></div>
                
                <div class="recipient-details">
                    <div class="detail-item">
                        <span class="detail-label">Designation:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($row['designation']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Workplace:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($row['place_of_posting']); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Mobile:</span>
                        <span class="detail-value"><?php echo htmlspecialchars($row['mobile_no']); ?></span>
                    </div>
                </div>
                
                <div class="certificate-text">in recognition of the outstanding innovation titled</div>
                
                <div class="innovation-title">
                    "<?php echo htmlspecialchars($row['title_of_idea']); ?>"
                </div>
                
                <div class="certificate-text">
                    for the fiscal year <?php echo convertToBengaliNumber($row['fiscal_year']); ?>.<br>
                    Your creative contribution to BCIC's innovation journey is highly appreciated.
                </div>
                
                <?php if ($row['prize'] == 'yes' && !empty($row['prize_amount'])): ?>
                <div class="innovation-title" style="background: linear-gradient(135deg, #fef3c7, #fde68a);">
                    <i class="fas fa-star me-2"></i>
                    Prize Amount: ৳<?php echo htmlspecialchars($row['prize_amount']); ?>
                    <?php if (!empty($row['rank'])): ?>
                        | Rank: <?php echo htmlspecialchars($row['rank']); ?>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Signature Section -->
            <div class="signature-section">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">Managing Director</div>
                    <div class="signature-title">BCIC</div>
                </div>
                
                <div class="signature-box">
                    <i class="fas fa-certificate signature-stamp"></i>
                </div>
                
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div class="signature-name">Director</div>
                    <div class="signature-title">Innovation & Research</div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="certificate-footer">
                <div class="certificate-date">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Date: <?php echo $currentDate; ?>
                </div>
                <div class="certificate-id">
                    <i class="fas fa-qrcode me-2"></i>
                    Certificate ID: BCIC-INNOV-<?php echo str_pad($row['id'], 6, '0', STR_PAD_LEFT); ?>-<?php echo $row['fiscal_year']; ?>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            <button onclick="window.print()" class="btn-print">
                <i class="fas fa-print"></i> Print Certificate
            </button>
            
            <a href="generate_certificate_pdf.php?id=<?php echo $row['id']; ?>" class="btn-download" target="_blank">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
            
            <a href="all_innovations.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
    
    <!-- Optional: Add confetti animation on load -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1"></script>
    <script>
        // Trigger confetti animation on load
        window.onload = function() {
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 },
                colors: ['#667eea', '#764ba2', '#fbbf24', '#ef4444']
            });
        }
    </script>
</body>
</html>