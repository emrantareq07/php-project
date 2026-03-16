<?php
include('db/db.php');

// Get statistics by fiscal year
$query = "SELECT 
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

$res = mysqli_query($conn, $query);

// Get total counts
$total_query = "SELECT 
                  COUNT(*) as total_awards, 
                  COUNT(DISTINCT inventors_emp_id) as total_officers 
                FROM innovation_tbl";
$total_result = mysqli_query($conn, $total_query);
$totals = mysqli_fetch_assoc($total_result);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
  <title>Statistics Report - Innovation Database - BCIC</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- DataTables CSS -->
  <link href="//cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">
  
  <style>
    /* Font Stack */
    * {
      font-family: 'Open Sans', 'Tiro Bangla', 'Noto Sans Bengali', 'Nikosh', sans-serif;
    }
    
    /* ===== Responsive Styles ===== */
    
    /* Main Container */
    .stats-container {
      margin: 1rem auto;
      padding: 1rem;
      width: 100%;
      max-width: 1200px;
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    }
    
    @media (min-width: 768px) {
      .stats-container {
        margin: 2rem auto;
        padding: 2rem;
      }
    }
    
    /* Header Section */
    .page-header {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      margin-bottom: 2rem;
      padding-bottom: 1rem;
      border-bottom: 2px solid #e9ecef;
    }
    
    @media (min-width: 768px) {
      .page-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
      }
    }
    
    .page-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #2c3e50;
      margin: 0;
      text-align: center;
    }
    
    @media (min-width: 768px) {
      .page-title {
        font-size: 2rem;
        text-align: left;
      }
    }
    
    .page-title i {
      color: #0d6efd;
    }
    
    /* Button Group */
    .button-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
      width: 100%;
    }
    
    @media (min-width: 576px) {
      .button-group {
        flex-direction: row;
        justify-content: flex-end;
        width: auto;
      }
    }
    
    .button-group .btn {
      width: 100%;
      padding: 0.75rem 1rem;
      font-size: 1rem;
      min-height: 48px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      border-radius: 8px;
      transition: all 0.3s ease;
      font-weight: 500;
    }
    
    @media (min-width: 576px) {
      .button-group .btn {
        width: auto;
        min-height: auto;
        padding: 0.5rem 1.5rem;
      }
    }
    
    .button-group .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .button-group .btn:active {
      transform: translateY(0);
    }
    
    /* Summary Cards */
    .summary-section {
      margin-bottom: 2rem;
    }
    
    .summary-card {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 12px;
      padding: 1.5rem;
      text-align: center;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }
    
    .summary-card .summary-label {
      font-size: 0.9rem;
      opacity: 0.9;
      margin-bottom: 0.5rem;
    }
    
    .summary-card .summary-value {
      font-size: 2rem;
      font-weight: 700;
      line-height: 1;
    }
    
    @media (min-width: 768px) {
      .summary-card .summary-value {
        font-size: 2.5rem;
      }
    }
    
    .summary-card.total-awards {
      background: linear-gradient(135deg, #11998e, #38ef7d);
    }
    
    .summary-card.total-officers {
      background: linear-gradient(135deg, #f093fb, #f5576c);
    }
    
    /* Table Container */
    .table-responsive-custom {
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      margin: 1.5rem 0;
      border-radius: 12px;
      background: white;
      box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    }
    
    /* Table Styles */
    .table {
      width: 100% !important;
      border-collapse: collapse;
      margin-bottom: 0;
    }
    
    .table thead {
      background-color: #212529;
      color: white;
    }
    
    .table thead th {
      padding: 15px 10px;
      font-weight: 600;
      font-size: 0.95rem;
      white-space: nowrap;
      text-align: center;
      border-bottom: none;
    }
    
    @media (min-width: 768px) {
      .table thead th {
        padding: 18px 15px;
        font-size: 1.1rem;
      }
    }
    
    .table tbody td {
      padding: 12px 8px;
      vertical-align: middle;
      font-size: 0.95rem;
      text-align: center;
      border-bottom: 1px solid #dee2e6;
    }
    
    @media (min-width: 768px) {
      .table tbody td {
        padding: 15px 12px;
        font-size: 1rem;
      }
    }
    
    .table tbody tr:hover {
      background-color: #f8f9fa;
    }
    
    /* Total Row */
    .total-row {
      background-color: #e9ecef;
      font-weight: 700;
    }
    
    .total-row td {
      font-size: 1rem;
      border-top: 2px solid #212529;
      border-bottom: 2px solid #212529;
    }
    
    @media (min-width: 768px) {
      .total-row td {
        font-size: 1.1rem;
      }
    }
    
    /* Stats Cards (Mobile View) */
    .stats-card-view {
      display: none;
    }
    
    @media (max-width: 767px) {
      .table-view {
        display: none;
      }
      
      .stats-card-view {
        display: block;
      }
      
      .stats-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-left: 4px solid #0d6efd;
        transition: all 0.3s ease;
      }
      
      .stats-card:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
      }
      
      .stats-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #dee2e6;
      }
      
      .stats-card-year {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2c3e50;
      }
      
      .stats-card-badge {
        background: #0d6efd;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
      }
      
      .stats-card-body {
        display: flex;
        justify-content: space-around;
        gap: 1rem;
      }
      
      .stats-card-item {
        text-align: center;
        flex: 1;
      }
      
      .stats-card-label {
        font-size: 0.8rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
      }
      
      .stats-card-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #0d6efd;
      }
      
      .stats-card-total {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-left: 4px solid #28a745;
        margin-top: 1.5rem;
      }
      
      .stats-card-total .stats-card-year,
      .stats-card-total .stats-card-label,
      .stats-card-total .stats-card-value {
        color: white;
      }
      
      .stats-card-total .stats-card-badge {
        background: rgba(255,255,255,0.2);
        color: white;
      }
    }
    
    /* DataTables Customization */
    .dataTables_wrapper {
      padding: 1rem;
    }
    
    .dataTables_length,
    .dataTables_filter {
      margin-bottom: 1.5rem;
    }
    
    @media (max-width: 767px) {
      .dataTables_length,
      .dataTables_filter {
        text-align: left;
        width: 100%;
      }
      
      .dataTables_filter input {
        width: 100% !important;
        margin-left: 0 !important;
        margin-top: 0.5rem;
        min-height: 44px;
        padding: 0.75rem;
        border-radius: 8px;
        border: 1px solid #ced4da;
      }
      
      .dataTables_length select {
        min-height: 44px;
        padding: 0.5rem;
        border-radius: 8px;
        border: 1px solid #ced4da;
      }
    }
    
    /* Pagination */
    .dataTables_paginate {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 0.25rem;
      margin-top: 1.5rem;
    }
    
    .dataTables_paginate .paginate_button {
      padding: 0.5rem 0.75rem;
      margin: 0;
      border: 1px solid #dee2e6;
      border-radius: 6px;
      min-width: 40px;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    @media (max-width: 767px) {
      .dataTables_paginate .paginate_button {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        min-width: 48px;
        min-height: 48px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
      }
    }
    
    .dataTables_paginate .paginate_button.current {
      background: #0d6efd;
      color: white !important;
      border-color: #0d6efd;
    }
    
    .dataTables_paginate .paginate_button:hover {
      background: #e9ecef;
    }
    
    /* Info Text */
    .dataTables_info {
      text-align: center;
      margin-bottom: 1rem;
      font-size: 0.9rem;
      color: #6c757d;
    }
    
    @media (min-width: 768px) {
      .dataTables_info {
        text-align: left;
        margin-bottom: 0;
      }
    }
    
    /* Print Styles */
    @media print {
      .button-group,
      .dataTables_length,
      .dataTables_filter,
      .dataTables_paginate,
      .dataTables_info {
        display: none !important;
      }
      
      .stats-container {
        box-shadow: none;
        margin: 0;
        padding: 0.5in;
      }
      
      .table thead {
        background-color: #212529 !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      
      .total-row {
        background-color: #e9ecef !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
      
      .summary-card {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    }
    
    /* Loading States */
    .loading-spinner {
      display: inline-block;
      width: 1rem;
      height: 1rem;
      border: 2px solid #f3f3f3;
      border-top: 2px solid #0d6efd;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    /* Touch Optimization */
    .btn, 
    .form-control,
    .form-select,
    .paginate_button {
      -webkit-tap-highlight-color: transparent;
    }
    
    .btn:active {
      opacity: 0.8;
    }
    
    /* Prevent zoom on iOS */
    @media screen and (-webkit-min-device-pixel-ratio: 0) {
      select, textarea, input {
        font-size: 16px !important;
      }
    }
    
    /* Landscape optimization */
    @media (max-height: 600px) and (orientation: landscape) {
      .stats-container {
        margin: 0.5rem auto;
        padding: 0.5rem;
      }
      
      .page-title {
        font-size: 1.25rem;
      }
      
      .button-group .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.9rem;
        min-height: 40px;
      }
    }
    
    /* No Data State */
    .no-data {
      text-align: center;
      padding: 3rem;
      color: #6c757d;
    }
    
    .no-data i {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: #dee2e6;
    }
  </style>
  
  <link rel="icon" type="image/gif/png" href="images/bcic_logo.png">
</head>
<body style="background-color: #f8f9fa;">
  <div class="stats-container">
    
    <!-- Page Header -->
    <div class="page-header">
      <h1 class="page-title">
        <i class="fa fa-bar-chart me-2"></i>
        পরিসংখ্যান প্রতিবেদন
      </h1>
      
      <div class="button-group">
        <a href="index.php" class="btn btn-success">
          <i class="fa fa-home"></i>
          <span class="d-none d-sm-inline">হোম পৃষ্ঠা</span>
          <span class="d-sm-none">হোম</span>
        </a>
        <a class="btn btn-danger" href="statistics_report_pdf.php" target="_blank">
          <i class="fa fa-print"></i>
          <span class="d-none d-sm-inline">প্রিন্ট রিপোর্ট</span>
          <span class="d-sm-none">প্রিন্ট</span>
        </a>
      </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="row summary-section g-3">
      <div class="col-12 col-md-4">
        <div class="summary-card">
          <div class="summary-label">মোট অর্থবছর</div>
          <div class="summary-value"><?php echo mysqli_num_rows($res); ?></div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="summary-card total-awards">
          <div class="summary-label">মোট পুরস্কার</div>
          <div class="summary-value"><?php echo $totals['total_awards']; ?></div>
        </div>
      </div>
      <div class="col-12 col-md-4">
        <div class="summary-card total-officers">
          <div class="summary-label">মোট কর্মকর্তা</div>
          <div class="summary-value"><?php echo $totals['total_officers']; ?></div>
        </div>
      </div>
    </div>
    
    <?php if(mysqli_num_rows($res) > 0): ?>
    
    <!-- Table View (Desktop) -->
    <div class="table-responsive-custom table-view">
      <table class="table table-striped">
        <thead class="table-dark">
          <tr>
            <th class="text-center">ক্রমিক নং</th>
            <th class="text-center">অর্থবছর</th>
            <th class="text-center">পুরস্কার সংখ্যা</th>
            <th class="text-center">কর্মকর্তার সংখ্যা</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $counter = 1;
          $total_awards = 0;
          $total_officers = 0;
          
          while($row = mysqli_fetch_assoc($res)) {
            $total_awards += $row['no_of_award'];
            $total_officers += $row['no_of_officer'];
          ?>
          <tr>
            <td class="text-center"><?php echo $counter++; ?></td>
            <td class="text-center"><?php echo htmlspecialchars($row['fiscal_year']); ?></td>
            <td class="text-center"><?php echo htmlspecialchars($row['no_of_award']); ?></td>
            <td class="text-center"><?php echo htmlspecialchars($row['no_of_officer']); ?></td>
          </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr class="total-row">
            <td colspan="2" class="text-center fw-bold">সর্বমোট</td>
            <td class="text-center fw-bold"><?php echo $total_awards; ?></td>
            <td class="text-center fw-bold"><?php echo $total_officers; ?></td>
          </tr>
        </tfoot>
      </table>
    </div>
    
    <!-- Card View (Mobile) -->
    <div class="stats-card-view">
      <?php 
      // Reset pointer
      mysqli_data_seek($res, 0);
      $counter = 1;
      
      while($row = mysqli_fetch_assoc($res)) { 
      ?>
      <div class="stats-card">
        <div class="stats-card-header">
          <span class="stats-card-year"><?php echo htmlspecialchars($row['fiscal_year']); ?></span>
          <span class="stats-card-badge">#<?php echo $counter++; ?></span>
        </div>
        <div class="stats-card-body">
          <div class="stats-card-item">
            <div class="stats-card-label">পুরস্কার</div>
            <div class="stats-card-value"><?php echo htmlspecialchars($row['no_of_award']); ?></div>
          </div>
          <div class="stats-card-item">
            <div class="stats-card-label">কর্মকর্তা</div>
            <div class="stats-card-value"><?php echo htmlspecialchars($row['no_of_officer']); ?></div>
          </div>
        </div>
      </div>
      <?php } ?>
      
      <!-- Total Card -->
      <div class="stats-card stats-card-total">
        <div class="stats-card-header">
          <span class="stats-card-year">সর্বমোট</span>
          <span class="stats-card-badge">সমষ্টি</span>
        </div>
        <div class="stats-card-body">
          <div class="stats-card-item">
            <div class="stats-card-label">পুরস্কার</div>
            <div class="stats-card-value"><?php echo $total_awards; ?></div>
          </div>
          <div class="stats-card-item">
            <div class="stats-card-label">কর্মকর্তা</div>
            <div class="stats-card-value"><?php echo $total_officers; ?></div>
          </div>
        </div>
      </div>
    </div>
    
    <?php else: ?>
    
    <!-- No Data State -->
    <div class="no-data">
      <i class="fa fa-database"></i>
      <h4>কোনো তথ্য পাওয়া যায়নি</h4>
      <p class="text-muted">ডাটাবেসে কোনো উদ্ভাবনী ধারণা যোগ করা হয়নি।</p>
    </div>
    
    <?php endif; ?>
    
    <!-- Last Updated -->
    <div class="text-center text-muted mt-4 small">
      <i class="fa fa-clock-o me-1"></i>
      সর্বশেষ হালনাগাদ: <?php echo date('d-m-Y h:i A'); ?>
    </div>
    
  </div>
  
  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  
  <script>
  $(document).ready(function() {
    <?php if(mysqli_num_rows($res) > 0): ?>
    // Initialize DataTable for desktop view
    var table = $('.table-view .table').DataTable({
      "order": [[1, "desc"]],
      "pageLength": 10,
      "lengthMenu": [
        [5, 10, 25, 50, -1],
        [5, 10, 25, 50, "সব"]
      ],
      "language": {
        "lengthMenu": "প্রদর্শন _MENU_ এন্ট্রি",
        "info": "মোট _TOTAL_ টির মধ্যে _START_ থেকে _END_ দেখানো হচ্ছে",
        "infoEmpty": "কোনো এন্ট্রি নেই",
        "infoFiltered": "(_MAX_ টOTAL এন্ট্রি থেকে ফিল্টার করা হয়েছে)",
        "search": "অনুসন্ধান:",
        "zeroRecords": "কোনো মিল পাওয়া যায়নি",
        "paginate": {
          "first": '<i class="fa fa-angle-double-left"></i>',
          "last": '<i class="fa fa-angle-double-right"></i>',
          "next": '<i class="fa fa-angle-right"></i>',
          "previous": '<i class="fa fa-angle-left"></i>'
        }
      },
      "responsive": false,
      "autoWidth": true,
      "initComplete": function() {
        $('.dataTables_filter input').addClass('form-control');
        $('.dataTables_length select').addClass('form-select');
      }
    });
    
    // Handle window resize
    var resizeTimer;
    $(window).on('resize', function() {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function() {
        table.columns.adjust();
      }, 250);
    });
    
    // Orientation change handler
    window.addEventListener('orientationchange', function() {
      setTimeout(function() {
        table.columns.adjust();
      }, 200);
    });
    <?php endif; ?>
    
    // Touch optimization
    if ('ontouchstart' in window) {
      $('.btn, .paginate_button').on('touchstart', function() {
        $(this).css('opacity', '0.7');
      }).on('touchend', function() {
        $(this).css('opacity', '');
      });
    }
    
    // Prevent zoom on double tap for iOS
    document.addEventListener('touchstart', function(e) {
      if (e.touches.length > 1) {
        e.preventDefault();
      }
    }, { passive: false });
    
    // Print button enhancement
    $('.btn-danger').on('click', function(e) {
      var btn = $(this);
      btn.html('<span class="loading-spinner"></span> প্রস্তুত হচ্ছে...');
      
      setTimeout(function() {
        btn.html('<i class="fa fa-print"></i> প্রিন্ট রিপোর্ট');
      }, 2000);
    });
    
    // Animate cards on load
    $('.summary-card, .stats-card').each(function(index) {
      $(this).css({
        'opacity': '0',
        'transform': 'translateY(20px)'
      }).delay(index * 100).animate({
        'opacity': '1',
        'transform': 'translateY(0)'
      }, 300);
    });
    
  });
  </script>
  
</body>
</html>