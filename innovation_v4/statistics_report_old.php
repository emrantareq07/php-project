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
      min-height: 48px; /* Better touch target */
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      border-radius: 8px;
      transition: all 0.3s ease;
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
      font-size: 0.9rem;
      text-align: center;
      border-bottom: 1px solid #dee2e6;
    }
    
    @media (min-width: 768px) {
      .table tbody td {
        padding: 15px 12px;
        font-size: 1rem;
      }
    }
    
    /* Total Row */
    .total-row {
      background-color: #e9ecef;
      font-weight: 600;
    }
    
    .total-row td {
      font-size: 1rem;
      border-top: 2px solid #212529;
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
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        border-left: 4px solid #0d6efd;
      }
      
      .stats-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #dee2e6;
      }
      
      .stats-card-year {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
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
        font-size: 1.2rem;
        font-weight: 600;
        color: #0d6efd;
      }
      
      .stats-card-total {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-left: 4px solid #28a745;
      }
      
      .stats-card-total .stats-card-year,
      .stats-card-total .stats-card-label,
      .stats-card-total .stats-card-value {
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
    
    /* High contrast mode */
    @media (prefers-contrast: high) {
      .table thead {
        border: 2px solid #000;
      }
      
      .total-row {
        border: 2px solid #000;
      }
      
      .btn {
        border: 2px solid #000;
      }
    }
    
    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
      * {
        animation: none !important;
        transition: none !important;
      }
    }
    
    /* Custom Scrollbar */
    .table-responsive-custom::-webkit-scrollbar {
      height: 4px;
    }
    
    .table-responsive-custom::-webkit-scrollbar-thumb {
      background-color: #cbd5e0;
      border-radius: 4px;
    }
    
    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 3rem 1rem;
      color: #6c757d;
    }
    
    .empty-state i {
      font-size: 3rem;
      margin-bottom: 1rem;
      color: #dee2e6;
    }
    
    /* Badge for mobile stats */
    .stats-badge {
      display: inline-block;
      padding: 0.35rem 0.65rem;
      font-size: 0.75rem;
      font-weight: 600;
      line-height: 1;
      color: #fff;
      text-align: center;
      white-space: nowrap;
      vertical-align: baseline;
      border-radius: 0.25rem;
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
        Statistics Report
      </h1>
      
      <div class="button-group">
        <a href="index.php" class="btn btn-success">
          <i class="fa fa-home"></i>
          <span class="d-none d-sm-inline">Home Page</span>
          <span class="d-sm-none">Home</span>
        </a>
        <a class="btn btn-danger" href="statistics_report_pdf.php" target="_blank">
          <i class="fa fa-print"></i>
          <span class="d-none d-sm-inline">Print Report</span>
          <span class="d-sm-none">Print</span>
        </a>
      </div>
    </div>
    
    <!-- Table View (Desktop) -->
    <div class="table-responsive-custom table-view">
      <table class="table table-striped">
        <thead class="table-dark">
          <tr>
            <th class="text-center">অর্থবছর</th>
            <th class="text-center">পুরস্কার সংখ্যা</th>
            <th class="text-center">কর্মকর্তার সংখ্যা</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $total_awards = 0;
          $total_officers = 0;
          
          while($row = mysqli_fetch_array($res)) {
            $total_awards += $row['no_of_award'];
            $total_officers += $row['no_of_officer'];
          ?>
          <tr>
            <td class="text-center"><?php echo htmlspecialchars($row['fiscal_year']); ?></td>
            <td class="text-center"><?php echo htmlspecialchars($row['no_of_award']); ?></td>
            <td class="text-center"><?php echo htmlspecialchars($row['no_of_officer']); ?></td>
          </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <tr class="total-row">
            <td class="text-center fw-bold">মোট</td>
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
      
      while($row = mysqli_fetch_array($res)) { 
      ?>
      <div class="stats-card">
        <div class="stats-card-header">
          <span class="stats-card-year"><?php echo htmlspecialchars($row['fiscal_year']); ?></span>
          <span class="stats-badge bg-primary">Yearly Report</span>
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
          <span class="stats-badge bg-light text-dark">Total</span>
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
    
    <!-- Summary Section -->
    <div class="row mt-4 g-3">
      <div class="col-6 col-md-4">
        <div class="card text-center border-0 shadow-sm">
          <div class="card-body py-3">
            <h6 class="text-muted mb-2">মোট অর্থবছর</h6>
            <h3 class="mb-0 text-primary"><?php echo mysqli_num_rows($res); ?></h3>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-4">
        <div class="card text-center border-0 shadow-sm">
          <div class="card-body py-3">
            <h6 class="text-muted mb-2">মোট পুরস্কার</h6>
            <h3 class="mb-0 text-success"><?php echo $total_awards; ?></h3>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-4 mx-auto">
        <div class="card text-center border-0 shadow-sm">
          <div class="card-body py-3">
            <h6 class="text-muted mb-2">মোট কর্মকর্তা</h6>
            <h3 class="mb-0 text-info"><?php echo $total_officers; ?></h3>
          </div>
        </div>
      </div>
    </div>
    
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
    // Initialize DataTable for desktop view
    var table = $('.table-view .table').DataTable({
      "order": [[0, "desc"]], // Order by fiscal year descending
      "pageLength": 10,
      "lengthMenu": [
        [5, 10, 25, 50, -1],
        [5, 10, 25, 50, "All"]
      ],
      "language": {
        "lengthMenu": "Show _MENU_ entries",
        "info": "Showing _START_ to _END_ of _TOTAL_ years",
        "infoEmpty": "No records found",
        "infoFiltered": "(filtered from _MAX_ total years)",
        "search": "Search:",
        "zeroRecords": "No matching records found",
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
        // Add custom styling
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
      btn.html('<span class="loading-spinner"></span> Preparing...');
      
      // Reset after 2 seconds (in case of slow navigation)
      setTimeout(function() {
        btn.html('<i class="fa fa-print"></i> Print Report');
      }, 2000);
    });
    
    // Summary cards animation
    $('.card').each(function(index) {
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