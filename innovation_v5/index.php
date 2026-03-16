<?php 
session_name('innovation_db');

require_once("db/db.php");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
  <title>Innovation Database - BCIC</title>
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Font Awesome 6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css"/>
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    /* Font Stack */
    * {
      font-family: 'Hind Siliguri', 'Open Sans', sans-serif;
    }
    
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
    }
    
    /* Main Container */
    .main-wrapper {
      padding: 20px;
    }
    
    .content-card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      animation: slideIn 0.5s ease-out;
    }
    
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    /* Hero Header */
    .hero-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 30px;
      color: white;
      position: relative;
      overflow: hidden;
    }
    
    .hero-header::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
      animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    
    .hero-title {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 10px;
      position: relative;
      z-index: 1;
    }
    
    .hero-subtitle {
      font-size: 0.9rem;
      opacity: 0.9;
      position: relative;
      z-index: 1;
    }
    
    /* Button Styles */
    .btn-custom {
      padding: 12px 25px;
      border-radius: 12px;
      font-weight: 500;
      transition: all 0.3s;
      position: relative;
      z-index: 1;
      border: none;
    }
    
    .btn-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }
    
    .btn-custom:active {
      transform: translateY(0);
    }
    
    .btn-primary-custom {
      background: white;
      color: #667eea;
    }
    
    .btn-primary-custom:hover {
      background: #f8f9fa;
      color: #5a67d8;
    }

        .btn-primary-custom1 {
      background: green;
      color: #667eea;
    }
    
    .btn-primary-custom1:hover {
      background: #f8f9fa;
      color: #5a67d8;
    }
    
    .btn-success-custom {
      background: #48bb78;
      color: white;
    }
    
    .btn-danger-custom {
      background: #f56565;
      color: white;
    }
    
    /* Stats Cards */
    .stats-container {
      padding: 30px;
      background: #f7fafc;
    }
    
    .stat-card {
      background: white;
      border-radius: 16px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      transition: all 0.3s;
      border: 1px solid #e2e8f0;
      animation: fadeInUp 0.5s ease-out forwards;
      opacity: 0;
    }
    
    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }
    
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .stat-icon {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 15px;
      font-size: 1.5rem;
      color: white;
    }
    
    .stat-icon.purple { background: #9f7aea; }
    .stat-icon.blue { background: #4299e1; }
    .stat-icon.green { background: #48bb78; }
    .stat-icon.orange { background: #ed8936; }
    
    .stat-value {
      font-size: 2rem;
      font-weight: 700;
      color: #2d3748;
      margin: 0;
    }
    
    .stat-label {
      color: #718096;
      font-size: 0.9rem;
      margin-top: 5px;
    }
    
    /* Table Section */
    .table-section {
      padding: 30px;
    }
    
    .table-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
      gap: 15px;
    }
    
    .table-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: #2d3748;
      margin: 0;
    }
    
    .table-title i {
      color: #9f7aea;
      margin-right: 10px;
    }
    
    .search-box {
      position: relative;
      width: 300px;
    }
    
    .search-box i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #a0aec0;
    }
    
    .search-box input {
      width: 100%;
      padding: 12px 20px 12px 45px;
      border: 2px solid #e2e8f0;
      border-radius: 12px;
      font-size: 0.95rem;
      transition: all 0.3s;
    }
    
    .search-box input:focus {
      outline: none;
      border-color: #9f7aea;
      box-shadow: 0 0 0 3px rgba(159, 122, 234, 0.1);
    }
    
    /* Table Styles */
    .table-responsive-custom {
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
    
    .table {
      margin-bottom: 0;
    }
    
    .table thead th {
      background: #f7fafc;
      color: #2d3748;
      font-weight: 600;
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 15px 12px;
      border-bottom: 2px solid #e2e8f0;
    }
    
    .table tbody td {
      padding: 15px 12px;
      color: #4a5568;
      font-size: 0.95rem;
      vertical-align: middle;
      border-bottom: 1px solid #e2e8f0;
    }
    
    .table tbody tr:hover {
      background-color: #faf5ff;
    }
    
    /* Status Badges */
    .badge-custom {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
      display: inline-block;
    }
    
    .badge-success {
      background: #c6f6d5;
      color: #22543d;
    }
    
    .badge-warning {
      background: #feebc8;
      color: #744210;
    }
    
    .badge-info {
      background: #bee3f8;
      color: #2c5282;
    }
    
    /* View Details Link */
    .view-link {
      display: inline-block;
      background: #9f7aea;
      color: white;
      padding: 8px 16px;
      border-radius: 20px;
      text-decoration: none;
      font-size: 0.9rem;
      transition: all 0.3s;
      border: none;
      cursor: pointer;
    }
    
    .view-link:hover {
      background: #805ad5;
      color: white;
      transform: translateX(3px);
    }
    
    .view-link i {
      margin-right: 5px;
    }
    
    /* Modal Styles */
    .modal-custom .modal-content {
      border-radius: 20px;
      border: none;
      overflow: hidden;
    }
    
    .modal-custom .modal-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 20px;
      border: none;
    }
    
    .modal-custom .modal-title {
      font-weight: 600;
    }
    
    .modal-custom .modal-body {
      padding: 25px;
      max-height: 70vh;
      overflow-y: auto;
    }
    
    .modal-custom .modal-footer {
      padding: 20px;
      border-top: 1px solid #e2e8f0;
    }
    
    .detail-item {
      margin-bottom: 20px;
    }
    
    .detail-label {
      font-weight: 600;
      color: #2d3748;
      margin-bottom: 5px;
      font-size: 0.9rem;
    }
    
    .detail-value {
      color: #4a5568;
      padding: 10px;
      background: #f7fafc;
      border-radius: 10px;
      border-left: 4px solid #9f7aea;
    }
    
    /* Loading Spinner */
    .spinner-custom {
      width: 40px;
      height: 40px;
      border: 4px solid #f3f3f3;
      border-top: 4px solid #9f7aea;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    /* Footer */
    .footer {
      text-align: center;
      padding: 15px;
      color: white;
      font-size: 0.9rem;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      .hero-title {
        font-size: 1.5rem;
      }
      
      .search-box {
        width: 100%;
      }
      
      .table-header {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .stat-card {
        margin-bottom: 15px;
      }
    }
    .blink-link {
    color: #ffff;
    font-weight: bold;
    animation: blinkAnimation 10s infinite;
}

@keyframes blinkAnimation {
    0% { opacity: 1; }
    50% { opacity: 0; }
    100% { opacity: 1; }
}
  </style>
  
  <link rel="icon" type="image/gif/png" href="images/bcic_logo.png">
</head>
<body>
  <div class="main-wrapper">
    <div class="container-fluid px-0">
      <div class="content-card">
        
        <!-- Hero Header -->
        <div class="hero-header">
          <div class="row align-items-center">
            <div class="col-lg-6">
              <h1 class="hero-title">
                <i class="fas fa-lightbulb me-2"></i>
                বাস্তবায়িত উদ্ভাবনী ধারণা
              </h1>
              <p class="hero-subtitle">
                সহজিকৃত ও ডিজিটাইজকৃত সেবার ডাটাবেজ
              </p>
              <p class="hero-subtitle mb-0 mt-3">
                <i class="fas fa-code me-1"></i> 
                Design & Developed By: Md. Tareq Emran, Programmer, ICT Division, BCIC
              </p>
            </div>
            <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
              <div class="d-flex gap-2 justify-content-lg-end">

                <?php
                // Check if any active innovation idea exists
                $today = date('Y-m-d');

                $active_query = mysqli_query($conn, "
                    SELECT * FROM tbl_innovation_idea 
                    WHERE start_date IS NOT NULL 
                    AND end_date IS NOT NULL
                    AND '$today' BETWEEN start_date AND end_date
                    LIMIT 1
                ");

                $is_active = mysqli_num_rows($active_query) > 0;
                $active_row = mysqli_fetch_assoc($active_query);
                ?>

                <?php if($is_active): ?>
                  <a href="libs/submit_inno_idea.php" class="blink-link btn btn-custom btn-primary-custom1" target="_blank">
                      <i class="fas fa-info-circle"></i> Submit Idea
                      <?php //echo htmlspecialchars($active_row['title']); ?>
                  </a>
              <?php endif; ?>


                <!-- <a href="libs/submit_inno_idea.php" class="btn btn-custom btn-primary-custom" target="_blank">
                  <i class="fas fa-sign-in-alt me-2"></i>
                  <span class="d-none d-sm-inline">Submit Idea</span>
                </a> -->
                <a href="login.php" class="btn btn-custom btn-primary-custom" target="_blank">
                  <i class="fas fa-sign-in-alt me-2"></i>
                  <span class="d-none d-sm-inline">Login</span>
                </a>
                <a href="statistics_report.php" class="btn btn-custom btn-success-custom">
                  <i class="fas fa-chart-bar me-2"></i>
                  <span class="d-none d-sm-inline">Statistics</span>
                </a>
                <a href="create_pdf_all_inovation.php" class="btn btn-custom btn-danger-custom" target="_blank">
                  <i class="fas fa-print me-2"></i>
                  <span class="d-none d-sm-inline">Print</span>
                </a>
                <a href="create_pdf_all_inovation_2.php" class="btn btn-custom btn-danger-custom" target="_blank">
                  <i class="fas fa-print me-2"></i>
                  <span class="d-none d-sm-inline">Print 2</span>
                </a>
              </div>
            </div>
          </div>
        </div>
        
        <?php
        include('db/db.php');
        
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        // Get statistics
        $total_query = "SELECT COUNT(*) as total FROM innovation_tbl";
        $total_result = mysqli_query($conn, $total_query);
        $total = $total_result ? mysqli_fetch_assoc($total_result)['total'] : 0;
        
        $fiscal_query = "SELECT COUNT(DISTINCT fiscal_year) as total FROM innovation_tbl";
        $fiscal_result = mysqli_query($conn, $fiscal_query);
        $fiscal = $fiscal_result ? mysqli_fetch_assoc($fiscal_result)['total'] : 0;
        
        $implemented_query = "SELECT COUNT(*) as total FROM innovation_tbl WHERE imple_status='বাস্তবায়িত'";
        $implemented_result = mysqli_query($conn, $implemented_query);
        $implemented = $implemented_result ? mysqli_fetch_assoc($implemented_result)['total'] : 0;
        
        $res = mysqli_query($conn, "SELECT * FROM innovation_tbl ORDER BY id DESC");
        ?>
        
        <!-- Statistics Cards -->
        <div class="stats-container">
          <div class="row g-4">
            <div class="col-md-3 col-6">
              <div class="stat-card">
                <div class="stat-icon purple">
                  <i class="fas fa-lightbulb"></i>
                </div>
                <h3 class="stat-value"><?php echo $total; ?></h3>
                <p class="stat-label">মোট উদ্ভাবন</p>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="stat-card">
                <div class="stat-icon blue">
                  <i class="fas fa-calendar"></i>
                </div>
                <h3 class="stat-value"><?php echo $fiscal; ?></h3>
                <p class="stat-label">অর্থবছর</p>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="stat-card">
                <div class="stat-icon green">
                  <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="stat-value"><?php echo $implemented; ?></h3>
                <p class="stat-label">বাস্তবায়িত</p>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="stat-card">
                <div class="stat-icon orange">
                  <i class="fas fa-users"></i>
                </div>
                <h3 class="stat-value"><?php echo mysqli_num_rows($res); ?></h3>
                <p class="stat-label">মোট রেকর্ড</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Table Section -->
        <div class="table-section">
          <div class="table-header">
            <h4 class="table-title">
              <i class="fas fa-list"></i>
              উদ্ভাবন তালিকা
            </h4>
            <div class="search-box">
              <i class="fas fa-search"></i>
              <input type="text" id="tableSearch" placeholder="অনুসন্ধান করুন...">
            </div>
          </div>
          
          <div class="table-responsive-custom">
            <table id="innovationTable" class="table table-hover">
              <thead>
                <tr>
                  <th>অর্থবছর</th>
                  <th>শিরোনাম</th>
                  <th>উদ্ভাবক</th>
                  <th>পদবী</th>
                  <th>কর্মস্থল</th>
                  <th>অবস্থা</th>
                  <th>যোগ্যতা</th>
                  <th>ফলাফল</th>
                  <th>বিস্তারিত</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                if ($res && mysqli_num_rows($res) > 0) {
                  while($row = mysqli_fetch_array($res)) { 
                    $status_class = '';
                    if($row['imple_status'] == 'বাস্তবায়িত') {
                      $status_class = 'badge-success';
                    } elseif($row['imple_status'] == 'চলমান') {
                      $status_class = 'badge-warning';
                    } else {
                      $status_class = 'badge-info';
                    }
                ?>
                <tr>
                  <td><span class="fw-bold"><?php echo htmlspecialchars($row['fiscal_year']); ?></span></td>
                  <td><?php echo htmlspecialchars(substr($row['title_of_invention'], 0, 200)) . '...'; ?></td>
                  <td><?php echo htmlspecialchars($row['inventors_name']); ?></td>
                  <td><?php echo htmlspecialchars($row['inventors_designation']); ?></td>
                  <td><?php echo htmlspecialchars($row['proposed_workplace']); ?></td>
                  <td><span class="badge-custom <?php echo $status_class; ?>"><?php echo htmlspecialchars($row['imple_status']); ?></span></td>
                  <td><?php echo htmlspecialchars($row['replicate_eligibility']); ?></td>
                  <td><?php echo htmlspecialchars($row['feedback']); ?></td>
                  <td>
                    <button class="view-link view_data" data-id="<?php echo $row["id"]; ?>">
                      <i class="fas fa-eye me-1"></i> বিস্তারিত
                    </button>
                  </td>
                </tr>
                <?php 
                  }
                } else {
                  echo '<tr><td colspan="9" class="text-center py-4">কোনো তথ্য পাওয়া যায়নি</td></tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
          <p class="mb-0">
            <i class="far fa-copyright me-1"></i> 
            <?php echo date('Y'); ?> BCIC Innovation Database. All rights reserved.
          </p>
        </div>
        
      </div>
    </div>
  </div>
  
  <!-- Modal for Innovation Details -->
  <div id="dataModal" class="modal fade modal-custom" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">
            <i class="fas fa-info-circle me-2"></i>
            সেবা/আইডিয়া/উদ্ভাবনের বর্ণনা
          </h4>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body" id="employee_detail">
          <!-- Content will be loaded here -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="fas fa-times me-2"></i> বন্ধ করুন
          </button>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="//cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
  
  <script>
    $(document).ready(function() {
      console.log("Document ready - Initializing...");
      
      // Initialize DataTable
      var table = $('#innovationTable').DataTable({
        "paging": true,
        "pageLength": 10,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "order": [[0, "desc"]],
        "columnDefs": [
          { "orderable": false, "targets": [8] }
        ],
        "language": {
          "emptyTable": "কোনো তথ্য পাওয়া যায়নি",
          "info": "মোট _TOTAL_ টির মধ্যে _START_ থেকে _END_ দেখানো হচ্ছে",
          "infoEmpty": "কোনো তথ্য নেই",
          "infoFiltered": "(মোট _MAX_ টি তথ্য থেকে ফিল্টার করা হয়েছে)",
          "zeroRecords": "কোনো মিল পাওয়া যায়নি",
          "paginate": {
            "first": "প্রথম",
            "last": "শেষ",
            "next": "<i class='fas fa-chevron-right'></i>",
            "previous": "<i class='fas fa-chevron-left'></i>"
          }
        }
      });
      
      // Custom search
      $('#tableSearch').on('keyup', function() {
        table.search(this.value).draw();
      });
      
      // View details modal - FIXED VERSION
      $(document).on('click', '.view_data', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        var id = $(this).data('id');
        console.log("View details clicked for ID:", id);
        
        if (!id) {
          alert('Invalid ID');
          return;
        }
        
        // Show loading state
        $('#employee_detail').html(`
          <div class="text-center py-5">
            <div class="spinner-custom mx-auto"></div>
            <p class="mt-3 text-muted">লোড হচ্ছে...</p>
          </div>
        `);
        
        // Show modal
        $('#dataModal').modal('show');
        
        // Make AJAX request
        $.ajax({
          url: "select.php",
          type: "POST",
          data: { 
            employee_id: id 
          },
          dataType: 'html',
          timeout: 10000, // 10 second timeout
          success: function(response) {
            console.log("AJAX Success - Response received");
            if (response && response.trim() !== '') {
              $('#employee_detail').html(response);
            } else {
              $('#employee_detail').html(`
                <div class="alert alert-warning m-3">
                  <i class="fas fa-exclamation-triangle me-2"></i>
                  কোন তথ্য পাওয়া যায়নি।
                </div>
              `);
            }
          },
          error: function(xhr, status, error) {
            console.error("AJAX Error:", status, error);
            console.error("Response:", xhr.responseText);
            
            let errorMessage = 'তথ্য লোড করতে সমস্যা হয়েছে।';
            if (status === 'timeout') {
              errorMessage = 'সময় বেশি লাগছে। আবার চেষ্টা করুন।';
            } else if (xhr.status === 404) {
              errorMessage = 'select.php ফাইলটি পাওয়া যায়নি।';
            } else if (xhr.status === 500) {
              errorMessage = 'সার্ভারে সমস্যা হয়েছে।';
            }
            
            $('#employee_detail').html(`
              <div class="alert alert-danger m-3">
                <i class="fas fa-exclamation-circle me-2"></i>
                ${errorMessage}
                <br>
                <small class="d-block mt-2">Error: ${error}</small>
              </div>
            `);
          }
        });
      });
      
      // Clear modal data on hide
      $('#dataModal').on('hidden.bs.modal', function() {
        console.log("Modal hidden - clearing content");
        $('#employee_detail').html('');
      });
      
      // Test if select.php exists
      $.ajax({
        url: "select.php",
        type: "HEAD",
        timeout: 5000,
        success: function() {
          console.log("select.php is accessible");
        },
        error: function() {
          console.error("select.php is not accessible");
          $('.view-link').prop('disabled', true).css('opacity', '0.5');
        }
      });
      
      // Window resize handler
      var resizeTimer;
      $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
          table.columns.adjust();
        }, 250);
      });
      
    });
  </script>
  
</body>
</html>