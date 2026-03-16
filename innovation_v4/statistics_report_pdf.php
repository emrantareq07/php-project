<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
  <title>Statistics Report - BCIC Innovation Database</title>
 
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  
  <style>
    /* Bengali Font Stack */
    @font-face {
      font-family: 'bangla';
      src: url('fonts/Nikosh.ttf') format('truetype');
      font-weight: normal;
      font-style: normal;
    }
    
    html, body, div, table, th, td, p, h1, h2, h3, h4, h5, h6 {
      font-family: 'bangla', 'Noto Sans Bengali', 'Nikosh', 'SolaimanLipi', Arial, sans-serif;
    }
    
    body {
      background-color: #f8f9fa;
      padding: 20px;
    }
    
    /* Print Container */
    .print-container {
      max-width: 1200px;
      margin: 0 auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    
    /* Header Styles */
    .company-header {
      text-align: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px solid #0d6efd;
    }
    
    .logo-container {
      display: flex;
      justify-content: center;
      margin-bottom: 15px;
    }
    
    .logo-container img {
      width: 90px;
      height: auto;
    }
    
    .company-name-bn {
      font-size: 1.8rem;
      font-weight: bold;
      color: #2c3e50;
      margin: 10px 0 5px;
      text-transform: uppercase;
    }
    
    .company-name-en {
      font-size: 1.4rem;
      color: #0d6efd;
      margin: 5px 0;
      text-transform: uppercase;
      font-weight: 600;
    }
    
    .company-address {
      font-size: 1.1rem;
      color: #6c757d;
      margin: 5px 0;
    }
    
    .department-info {
      font-size: 1.1rem;
      color: #28a745;
      margin: 10px 0;
      font-weight: 500;
    }
    
    .report-title {
      text-align: center;
      font-size: 1.8rem;
      font-weight: bold;
      color: #0d6efd;
      margin: 30px 0 20px;
      text-transform: uppercase;
      text-decoration: underline;
    }
    
    /* Table Styles */
    .table-container {
      margin: 20px 0;
      overflow-x: auto;
    }
    
    table {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
      font-size: 1rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    th {
      background-color: #343a40;
      color: white;
      font-weight: 600;
      padding: 15px 10px;
      text-align: center;
      border: 1px solid #454d55;
      font-size: 1.1rem;
    }
    
    td {
      padding: 12px 10px;
      border: 1px solid #dee2e6;
      text-align: center;
    }
    
    tr:nth-child(even) {
      background-color: #f8f9fa;
    }
    
    tr:hover {
      background-color: #e9ecef;
    }
    
    .total-row {
      background-color: #e9ecef !important;
      font-weight: bold;
    }
    
    .total-row td {
      border-top: 2px solid #343a40;
      border-bottom: 2px solid #343a40;
    }
    
    /* Summary Cards */
    .summary-section {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin: 30px 0;
      justify-content: center;
    }
    
    .summary-card {
      flex: 1;
      min-width: 200px;
      padding: 20px;
      border-radius: 10px;
      color: white;
      text-align: center;
      box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }
    
    .summary-card.years {
      background: linear-gradient(135deg, #667eea, #764ba2);
    }
    
    .summary-card.awards {
      background: linear-gradient(135deg, #11998e, #38ef7d);
    }
    
    .summary-card.officers {
      background: linear-gradient(135deg, #f093fb, #f5576c);
    }
    
    .summary-card .label {
      font-size: 1rem;
      opacity: 0.9;
      margin-bottom: 10px;
    }
    
    .summary-card .value {
      font-size: 2.5rem;
      font-weight: bold;
      line-height: 1;
    }
    
    /* Footer */
    .report-footer {
      margin-top: 40px;
      padding-top: 20px;
      border-top: 1px solid #dee2e6;
      display: flex;
      justify-content: space-between;
      color: #6c757d;
      font-size: 0.9rem;
    }
    
    .signature-section {
      margin-top: 50px;
      display: flex;
      justify-content: space-between;
    }
    
    .signature-box {
      text-align: center;
      width: 200px;
    }
    
    .signature-line {
      margin-top: 50px;
      border-top: 1px solid #000;
      padding-top: 5px;
    }
    
    /* Print Buttons */
    .print-buttons {
      margin-bottom: 20px;
      display: flex;
      gap: 10px;
      justify-content: flex-end;
    }
    
    .print-buttons .btn {
      padding: 10px 20px;
      font-size: 1rem;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .print-buttons .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    /* Print Styles */
    @media print {
      body {
        background: white;
        padding: 0;
      }
      
      .print-container {
        box-shadow: none;
        padding: 0.5in;
      }
      
      .print-buttons {
        display: none;
      }
      
      th {
        background-color: #343a40 !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      
      .summary-card {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      
      .company-header {
        border-bottom: 2px solid #000;
      }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .print-container {
        padding: 15px;
      }
      
      .company-name-bn {
        font-size: 1.4rem;
      }
      
      .company-name-en {
        font-size: 1.1rem;
      }
      
      .report-title {
        font-size: 1.4rem;
      }
      
      table {
        font-size: 0.9rem;
      }
      
      th, td {
        padding: 8px 5px;
      }
      
      .summary-card .value {
        font-size: 1.8rem;
      }
      
      .signature-section {
        flex-direction: column;
        align-items: center;
        gap: 30px;
      }
    }
  </style>
</head>
<body>
  <div class="print-buttons">
    <button class="btn btn-primary" onclick="window.print()">
      <i class="glyphicon glyphicon-print"></i> Print Report
    </button>
    <button class="btn btn-success" onclick="window.history.back()">
      <i class="glyphicon glyphicon-arrow-left"></i> Back
    </button>
  </div>

  <div class="print-container">
    <?php
    // Include mpdf library
    require_once __DIR__ . '/vendor/autoload.php';
    
    // Database Connection
    $conn = new mysqli('localhost', 'root', '', 'innovation_db');
    
    // Check connection
    if($conn->connect_error) {
      die("<div class='alert alert-danger'>Database connection failed: " . $conn->connect_error . "</div>");
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8");
    
    // Get statistics by fiscal year - FIXED QUERY
    $select = "SELECT 
                fiscal_year, 
                COUNT(*) as no_of_award, 
                COUNT(DISTINCT inventors_emp_id) as no_of_officer 
              FROM innovation_tbl 
              WHERE fiscal_year IS NOT NULL AND fiscal_year != ''
              GROUP BY fiscal_year 
              ORDER BY 
                CASE 
                  WHEN fiscal_year = '২০১৭-২০১৮' THEN 1
                  WHEN fiscal_year = '২০১৮-২০১৯' THEN 2
                  WHEN fiscal_year = '২০১৯-২০২০' THEN 3
                  WHEN fiscal_year = '২০২০-২০২১' THEN 4
                  WHEN fiscal_year = '২০২১-২০২২' THEN 5
                  WHEN fiscal_year = '২০২২-২০২৩' THEN 6
                  ELSE 7
                END";
    
    $result = $conn->query($select);
    
    // Get total counts
    $total_query = "SELECT 
                      COUNT(*) as total_awards, 
                      COUNT(DISTINCT inventors_emp_id) as total_officers 
                    FROM innovation_tbl";
    $total_result = $conn->query($total_query);
    $totals = $total_result->fetch_assoc();
    
    $data = '';
    $counter = 1;
    $total_awards = 0;
    $total_officers = 0;
    
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $total_awards += $row['no_of_award'];
        $total_officers += $row['no_of_officer'];
        
        $data .= '<tr>
                    <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">' . $counter++ . '</td>
                    <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">' . htmlspecialchars($row['fiscal_year']) . '</td>
                    <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">' . $row['no_of_award'] . '</td>
                    <td style="border: 1px solid #dee2e6; padding: 10px; text-align: center;">' . $row['no_of_officer'] . '</td>
                  </tr>';
      }
      
      // Add total row
      $data .= '<tr class="total-row" style="background-color: #e9ecef; font-weight: bold;">
                  <td colspan="2" style="border: 1px solid #dee2e6; padding: 12px; text-align: center; background-color: #e9ecef;">সর্বমোট</td>
                  <td style="border: 1px solid #dee2e6; padding: 12px; text-align: center; background-color: #e9ecef;">' . $total_awards . '</td>
                  <td style="border: 1px solid #dee2e6; padding: 12px; text-align: center; background-color: #e9ecef;">' . $total_officers . '</td>
                </tr>';
    } else {
      $data = '<tr>
                <td colspan="4" style="border: 1px solid #dee2e6; padding: 30px; text-align: center; color: #6c757d;">
                  <h4>কোনো তথ্য পাওয়া যায়নি</h4>
                  <p>ডাটাবেসে কোনো উদ্ভাবনী ধারণা যোগ করা হয়নি।</p>
                </td>
              </tr>';
    }
    
    // Company Header
    ?>
    
    <!-- Company Header -->
    <div class="company-header">
      <div class="logo-container">
        <img src="./images/BCIC_logo.jpg" alt="BCIC Logo" style="width: 90px;">
      </div>
      <h1 class="company-name-bn">বাংলাদেশ কেমিক্যাল ইন্ডাস্ট্রিজ কর্পোরেশন</h1>
      <h2 class="company-name-en">Bangladesh Chemical Industries Corporation</h2>
      <h3 class="company-address">বিসিআইসি ভবন, ৩০-৩১ দিলকুশা বা/এ, ঢাকা-১০০০</h3>
      <h4 class="department-info">বাস্তবায়নে: আইসিটি বিভাগ, বিসিআইসি</h4>
    </div>
    
    <!-- Summary Cards -->
    <div class="summary-section">
      <div class="summary-card years">
        <div class="label">মোট অর্থবছর</div>
        <div class="value"><?php echo $counter - 1; ?></div>
      </div>
      <div class="summary-card awards">
        <div class="label">মোট পুরস্কার</div>
        <div class="value"><?php echo $total_awards; ?></div>
      </div>
      <div class="summary-card officers">
        <div class="label">মোট কর্মকর্তা</div>
        <div class="value"><?php echo $total_officers; ?></div>
      </div>
    </div>
    
    <!-- Report Title -->
    <h2 class="report-title">Innovation Database - Statistics Report</h2>
    
    <!-- Data Table -->
    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th style="background-color: #343a40; color: white; padding: 12px; text-align: center;">ক্রমিক নং</th>
            <th style="background-color: #343a40; color: white; padding: 12px; text-align: center;">অর্থবছর</th>
            <th style="background-color: #343a40; color: white; padding: 12px; text-align: center;">পুরস্কার সংখ্যা</th>
            <th style="background-color: #343a40; color: white; padding: 12px; text-align: center;">কর্মকর্তার সংখ্যা</th>
          </tr>
        </thead>
        <tbody>
          <?php echo $data; ?>
        </tbody>
      </table>
    </div>
    
    <!-- Additional Statistics -->
    <div style="margin: 30px 0; padding: 20px; background-color: #f8f9fa; border-radius: 8px;">
      <h4 style="color: #0d6efd; margin-bottom: 15px;">অতিরিক্ত পরিসংখ্যান</h4>
      <div style="display: flex; flex-wrap: wrap; gap: 20px;">
        <div style="flex: 1; min-width: 150px;">
          <strong>গড় পুরস্কার/বছর:</strong> 
          <?php echo $counter > 1 ? round($total_awards / ($counter - 1), 2) : 0; ?>
        </div>
        <div style="flex: 1; min-width: 150px;">
          <strong>গড় কর্মকর্তা/বছর:</strong> 
          <?php echo $counter > 1 ? round($total_officers / ($counter - 1), 2) : 0; ?>
        </div>
        <div style="flex: 1; min-width: 150px;">
          <strong>সর্বোচ্চ পুরস্কার:</strong> 
          <?php
          $max_query = "SELECT MAX(award_count) as max_award FROM (SELECT COUNT(*) as award_count FROM innovation_tbl GROUP BY fiscal_year) as t";
          $max_result = $conn->query($max_query);
          $max_row = $max_result->fetch_assoc();
          echo $max_row['max_award'] ?? 0;
          ?>
        </div>
      </div>
    </div>
    
    <!-- Signature Section -->
    <div class="signature-section">
      <div class="signature-box">
        <div>প্রস্তুতকারক</div>
        <div class="signature-line">Md. Tareq Emran</div>
        <div>প্রোগ্রামার, আইসিটি বিভাগ</div>
        <div>তারিখ: <?php echo date('d-m-Y'); ?></div>
      </div>
      <div class="signature-box">
        <div>অনুমোদনকারী</div>
        <div class="signature-line">_______</div>
        <div>_____</div>
        <div>_____</div>
      </div>
    </div>
    
    <!-- Report Footer -->
    <div class="report-footer">
      <div>প্রতিবেদন তৈরি: <?php echo date('d-m-Y h:i A'); ?></div>
      <div>পৃষ্ঠা: 1/1</div>
      <div>বিসিআইসি আইসিটি বিভাগ</div>
    </div>
    
    <?php
    // Close connection
    $conn->close();
    
    // Uncomment the following lines if you want to generate PDF using mPDF
    /*
    $mpdf = new \Mpdf\Mpdf([
      'default_font' => 'bangla',
      'mode' => 'utf-8',
      'format' => 'A4',
      'margin_left' => 20,
      'margin_right' => 20,
      'margin_top' => 20,
      'margin_bottom' => 20,
      'margin_header' => 10,
      'margin_footer' => 10
    ]);
    
    $mpdf->SetHTMLHeader('<div style="text-align: right; font-size: 8pt;">BCIC Innovation Database</div>');
    $mpdf->SetHTMLFooter('<div style="text-align: center; font-size: 8pt;">পৃষ্ঠা {PAGENO}/{nbpg}</div>');
    
    $mpdf->SetWatermarkText('BCIC');
    $mpdf->showWatermarkText = true;
    $mpdf->watermarkTextAlpha = 0.1;
    
    $mpdf->WriteHTML($html_content);
    $mpdf->Output('statistics_report.pdf', 'I');
    */
    ?>
    
  </div>
  
  <script>
    $(document).ready(function() {
      // Print button functionality
      $('.print-buttons .btn-primary').on('click', function() {
        window.print();
      });
      
      // Back button functionality
      $('.print-buttons .btn-success').on('click', function() {
        window.history.back();
      });
      
      // Add loading state to print button
      $(window).on('beforeprint', function() {
        $('.print-buttons .btn-primary').html('<span class="spinner-border spinner-border-sm"></span> প্রিন্ট হচ্ছে...');
      });
      
      $(window).on('afterprint', function() {
        $('.print-buttons .btn-primary').html('<i class="glyphicon glyphicon-print"></i> Print Report');
      });
    });
  </script>
  
</body>
</html>