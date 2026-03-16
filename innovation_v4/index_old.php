<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
  <title>Innovation Database - BCIC</title>
  
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">
  
  <style>
    /* Font Stack */
    * {
      font-family: 'Open Sans', 'Tiro Bangla', 'Noto Sans Bengali', 'Nikosh', sans-serif;
    }
    
    /* Custom Styles */
    .bs-example {
      margin: 10px;
    }
    
    /* ===== Responsive Styles ===== */
    
    /* Header Section */
    .header-container {
      padding: 1rem;
    }
    
    .header-title {
      font-size: 1.2rem;
      line-height: 1.5;
      margin-bottom: 1rem;
    }
    
    @media (min-width: 768px) {
      .header-title {
        font-size: 1.5rem;
        margin-bottom: 0;
      }
    }
    
    .header-subtitle {
      font-size: 0.75rem;
      color: #6c757d;
    }
    
    @media (min-width: 768px) {
      .header-subtitle {
        font-size: 0.8rem;
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
      padding: 0.6rem 1rem;
      font-size: 0.9rem;
      min-height: 44px; /* Better touch target */
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }
    
    @media (min-width: 576px) {
      .button-group .btn {
        width: auto;
        min-height: auto;
        padding: 0.5rem 1rem;
      }
    }
    
    /* Table Container */
    .table-container {
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
      margin: 1rem 0;
      border-radius: 8px;
      background: white;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    /* Table Styles */
    .table {
      width: 100% !important;
      border-collapse: collapse;
      min-width: 1200px; /* Ensures horizontal scroll on mobile */
    }
    
    @media (max-width: 767px) {
      .table {
        min-width: 1000px; /* Adjust based on content */
      }
    }
    
    .table thead {
      background-color: #6f42c1; /* Purple */
      color: white;
    }
    
    .table thead th {
      padding: 12px 8px;
      font-weight: 600;
      font-size: 0.9rem;
      white-space: nowrap;
      vertical-align: middle;
    }
    
    @media (min-width: 768px) {
      .table thead th {
        padding: 15px 10px;
        font-size: 1rem;
      }
    }
    
    .table tbody td {
      padding: 10px 8px;
      vertical-align: top;
      font-size: 0.9rem;
    }
    
    @media (min-width: 768px) {
      .table tbody td {
        padding: 12px 10px;
        font-size: 1rem;
      }
    }
    
    /* View Details Link */
    .view-data-link {
      display: inline-block;
      background-color: #28a745;
      color: white;
      padding: 4px 8px;
      border-radius: 4px;
      text-decoration: none;
      font-size: 0.8rem;
      margin-left: 5px;
      white-space: nowrap;
    }
    
    @media (min-width: 768px) {
      .view-data-link {
        padding: 5px 10px;
        font-size: 0.9rem;
      }
    }
    
    .view-data-link:hover {
      background-color: #218838;
      color: white;
      text-decoration: none;
    }
    
    /* Modal Styles */
    .modal-dialog {
      margin: 0.5rem;
      max-width: calc(100% - 1rem);
    }
    
    @media (min-width: 768px) {
      .modal-dialog {
        margin: 1.75rem auto;
        max-width: 90%;
      }
    }
    
    @media (min-width: 1200px) {
      .modal-dialog {
        max-width: 1140px;
      }
    }
    
    .modal-content {
      border-radius: 12px;
      border: none;
      box-shadow: 0 5px 30px rgba(0,0,0,0.2);
    }
    
    .modal-header {
      padding: 1rem;
      background-color: #f8f9fa;
      border-radius: 12px 12px 0 0;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    @media (min-width: 768px) {
      .modal-header {
        padding: 1.5rem;
      }
    }
    
    .modal-title {
      font-size: 1.1rem;
      font-weight: 600;
      margin: 0;
    }
    
    @media (min-width: 768px) {
      .modal-title {
        font-size: 1.25rem;
      }
    }
    
    .modal-body {
      padding: 1rem;
      max-height: 70vh;
      overflow-y: auto;
      -webkit-overflow-scrolling: touch;
    }
    
    @media (min-width: 768px) {
      .modal-body {
        padding: 1.5rem;
      }
    }
    
    .modal-footer {
      padding: 1rem;
      border-top: 1px solid #dee2e6;
      background-color: #f8f9fa;
      border-radius: 0 0 12px 12px;
    }
    
    @media (max-width: 767px) {
      .modal-footer {
        flex-direction: column;
        gap: 0.5rem;
      }
      
      .modal-footer .btn {
        width: 100%;
        margin: 0 !important;
        min-height: 44px;
      }
    }
    
    /* DataTables Customization */
    .dataTables_wrapper {
      padding: 1rem;
    }
    
    .dataTables_length,
    .dataTables_filter {
      margin-bottom: 1rem;
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
      }
      
      .dataTables_length select {
        min-height: 44px;
        width: auto;
      }
    }
    
    /* Pagination */
    .dataTables_paginate {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 0.25rem;
      margin-top: 1rem;
    }
    
    .dataTables_paginate .paginate_button {
      padding: 0.5rem 0.75rem;
      margin: 0;
      border: 1px solid #dee2e6;
      border-radius: 4px;
      min-width: 40px;
      text-align: center;
      cursor: pointer;
    }
    
    @media (max-width: 767px) {
      .dataTables_paginate .paginate_button {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        min-width: 44px;
        min-height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
      }
    }
    
    .dataTables_paginate .paginate_button.current {
      background: #6f42c1;
      color: white !important;
      border-color: #6f42c1;
    }
    
    /* Info Text */
    .dataTables_info {
      text-align: center;
      margin-bottom: 1rem;
      font-size: 0.9rem;
    }
    
    @media (min-width: 768px) {
      .dataTables_info {
        text-align: left;
        margin-bottom: 0;
      }
    }
    
    /* Loading States */
    .loading-spinner {
      display: inline-block;
      width: 1rem;
      height: 1rem;
      border: 2px solid #f3f3f3;
      border-top: 2px solid #6f42c1;
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
    .paginate_button,
    .view-data-link {
      -webkit-tap-highlight-color: transparent;
    }
    
    .btn:active,
    .view-data-link:active {
      opacity: 0.8;
    }
    
    /* Prevent zoom on iOS */
    @media screen and (-webkit-min-device-pixel-ratio: 0) {
      select, textarea, input {
        font-size: 16px !important;
      }
    }
    
    /* Custom Scrollbar */
    .modal-body::-webkit-scrollbar {
      width: 4px;
    }
    
    .modal-body::-webkit-scrollbar-thumb {
      background-color: #cbd5e0;
      border-radius: 4px;
    }
    
    /* Table cell specific */
    .text-left-pb {
      text-align: left;
      padding-bottom: 1rem !important;
    }
    
    .text-center-pb {
      text-align: center;
      padding-bottom: 1rem !important;
    }
    
    /* Landscape optimization */
    @media (max-height: 600px) and (orientation: landscape) {
      .modal-body {
        max-height: 50vh;
      }
      
      .header-title {
        font-size: 1rem;
      }
      
      .button-group .btn {
        padding: 0.4rem 0.8rem;
        font-size: 0.85rem;
      }
    }
    
    /* High contrast mode */
    @media (prefers-contrast: high) {
      .table thead {
        border: 2px solid #000;
      }
      
      .view-data-link {
        border: 2px solid #000;
      }
      
      .modal-content {
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
    
    /* Print styles */
    @media print {
      .button-group,
      .dataTables_length,
      .dataTables_filter,
      .dataTables_paginate,
      .view-data-link {
        display: none !important;
      }
      
      .table {
        border: 1px solid #000;
      }
      
      .table thead {
        background-color: #6f42c1 !important;
        color: white !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
      }
    }
  </style>
  
  <link rel="icon" type="image/gif/png" href="images/bcic_logo.png">
</head>
<body>
  <!-- <div class="bs-example bg-white shadow rounded"> -->
    <div class="container-fluid border shadow rounded" style="width:100%;">
      
      <!-- Header Row -->
      <div class="row header-container">
        <div class="col-12 col-md-8">
          <h2 class="header-title text-center text-md-start text-white bg-success rounded p-3 mb-0">
            বাস্তবায়িত উদ্ভাবনী ধারণা, সহজিকৃত ও ডিজিটাইজকৃত সেবার ডাটাবেজ
          </h2>
          <p class="header-subtitle text-center text-md-start text-muted mt-2 mb-0">
            [--Design & Developed By: Md. Tareq Emran, Programmer, ICT Division, BCIC--]
          </p>
        </div>
        <div class="col-12 col-md-4 mt-3 mt-md-0">
          <div class="button-group">
            <a class="btn btn-primary" href="login.php" target="_blank">
              <i class="fa fa-sign-in"></i> 
              <span class="d-none d-sm-inline">Login</span>
            </a>
            <a href="statistics_report.php" class="btn btn-success">
              <i class="fa fa-chart-line"></i> 
              <span class="d-none d-sm-inline"><i class="fa fa-bar-chart me-0"></i> Statistics</span>
              <span class="d-sm-none">Stats</span>
            </a>
            <a class="btn btn-danger" href="create_pdf_all_inovation.php" target="_blank">
              <i class="fa fa-print"></i> 
              <span class="d-none d-sm-inline">Print</span>
            </a>
          </div>
        </div>
      </div>

      <?php
      include('db/db.php');
      $res = mysqli_query($conn, "SELECT * FROM innovation_tbl ORDER BY id DESC");
      ?>
      
      <!-- Table Container -->
      <div class="table-container">
        <table class="table table-striped table-hover pt-2" style="width:100%">
          <thead style="background-color: purple;" class="text-white">
            <tr>
              <th class="text-center">অর্থবছর</th>
              <th class="text-left">সেবা/আইডিয়া/উদ্ভাবনের শিরোনাম</th>
              <th>উদ্ভাবক/উদ্ভাবকের নাম, পদবী, এমপ্লয়ী নং ও প্রস্তাবকালীন কর্মস্থল</th>
              <th class="text-left">সেবা/আইডিয়া/উদ্ভাবনের সংক্ষিপ্ত বর্নণা</th>
              <th class="text-center">বাস্তাবায়নের অবস্থা</th>
              <th class="text-center">রেপ্লিকেট যোগ্যতা</th>
              <th class="text-center">ফলাফল</th>
              <th class="text-center">সেবার লিংক</th>
              <th class="text-center">মন্তব্য</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            while($row = mysqli_fetch_array($res)) { ?>
            <tr>
              <td class="text-center"><?php echo htmlspecialchars($row['fiscal_year']); ?></td>
              <td><?php echo htmlspecialchars($row['title_of_invention']); ?></td>
              <td>
                <?php 
                echo htmlspecialchars($row['inventors_name']) . "<br>" . 
                     htmlspecialchars($row['inventors_designation']) . "<br>" . 
                     htmlspecialchars($row['inventors_emp_id']) . "<br>" . 
                     htmlspecialchars($row['proposed_workplace']); 
                ?>
              </td>
              <td>
                <?php echo htmlspecialchars($row['title_of_invention']); ?>
                <a class="view-data-link view_data" id="<?php echo $row["id"]; ?>" style="cursor: pointer;">
                  বিস্তারিত...
                </a>
              </td>
              <td class="text-center"><?php echo htmlspecialchars($row['imple_status']); ?></td>
              <td class="text-center"><?php echo htmlspecialchars($row['replicate_eligibility']); ?></td>
              <td class="text-center"><?php echo htmlspecialchars($row['feedback']); ?></td>
              <td class="text-center">
                <?php 
                if(!empty($row['service_link'])) {
                  echo '<a href="' . htmlspecialchars($row['service_link']) . '" target="_blank" class="text-truncate d-inline-block" style="max-width:150px;">Link</a>';
                } 
                ?>
              </td>
              <td><?php echo htmlspecialchars($row['remarks']); ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      
      <!-- Modal for Innovation Details -->
      <div id="dataModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">সেবা/আইডিয়া/উদ্ভাবনের বর্নণা</h4>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="employee_detail"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                <i class="fa fa-times me-1"></i> Close
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  <!-- </div> -->

  <!-- Scripts -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
  <script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  
  <script type='text/javascript'>
    $(document).ready(function() {
      // Initialize DataTable with responsive options
      var table = $('.table').DataTable({
        lengthMenu: [
          [5, 10, 25, 50, -1],
          [5, 10, 25, 50, 'All'],
        ],
        "iDisplayLength": 10,
        "order": [[0, "desc"]], // Order by fiscal year descending
        "processing": true,
        "columnDefs": [
          {
            "targets": [0, 3, 4, 5, 6, 7, 8],
            "orderable": false,
          },
        ],
        "language": {
          "lengthMenu": "Show _MENU_ entries",
          "info": "Showing _START_ to _END_ of _TOTAL_ innovations",
          "infoEmpty": "No innovations found",
          "infoFiltered": "(filtered from _MAX_ total)",
          "search": "Search:",
          "zeroRecords": "No matching innovations found",
          "paginate": {
            "first": "First",
            "last": "Last",
            "next": '<i class="fa fa-chevron-right"></i>',
            "previous": '<i class="fa fa-chevron-left"></i>'
          }
        },
        "initComplete": function() {
          // Add loading complete handler
          $('.table').removeClass('no-data');
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

      // View details modal
      $(document).on('click', '.view_data', function() {
        var employee_id = $(this).attr("id");
        var link = $(this);
        
        // Show loading state
        link.html('<span class="loading-spinner"></span>');
        
        $.ajax({
          url: "select.php",
          method: "post",
          data: { employee_id: employee_id },
          success: function(data) {
            $('#employee_detail').html(data);
            $('#dataModal').modal("show");
            link.html('বিস্তারিত...');
          },
          error: function() {
            alert('Error loading details. Please try again.');
            link.html('বিস্তারিত...');
          }
        });
      });

      // Handle modal hidden event
      $('#dataModal').on('hidden.bs.modal', function() {
        $('#employee_detail').empty();
      });

      // Orientation change handler
      window.addEventListener('orientationchange', function() {
        setTimeout(function() {
          table.columns.adjust();
        }, 200);
      });

      // Touch optimization
      if ('ontouchstart' in window) {
        $('.btn, .view-data-link, .paginate_button').on('touchstart', function() {
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

      // Add responsive classes to table cells for mobile
      function addResponsiveLabels() {
        if ($(window).width() <= 767) {
          $('.table thead th').each(function(index) {
            var headerText = $(this).text();
            $('.table tbody tr').each(function() {
              var cell = $(this).find('td').eq(index);
              if (!cell.find('.mobile-label').length) {
                cell.prepend('<span class="mobile-label d-block d-md-none fw-bold text-muted mb-1">' + headerText + '</span>');
              }
            });
          });
        } else {
          $('.mobile-label').remove();
        }
      }

      // Initial call and on resize
      addResponsiveLabels();
      $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(addResponsiveLabels, 250);
      });

    });
  </script>

</body>
</html>