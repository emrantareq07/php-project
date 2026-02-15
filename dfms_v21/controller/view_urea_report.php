<?php
session_name('dfms');
session_start();
$table = $_SESSION['username'];
$user_type = $_SESSION['user_type'];

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

include('../include/header.php');
include('../db/db.php');
?>
<style>
  .table-responsive {
    max-height: 650px;
    overflow-y: auto;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  }
  .table thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: none;
    padding: 16px 12px;
  }
  .table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(102, 126, 234, 0.05);
  }
  .table-hover tbody tr:hover {
    background-color: rgba(102, 126, 234, 0.15);
    transform: scale(1.002);
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  }
  .table-bordered {
    border: 1px solid #dee2e6;
  }
  .table-bordered th,
  .table-bordered td {
    border: 1px solid #e9ecef;
  }
  .table td {
    padding: 14px 12px;
    vertical-align: middle;
    font-weight: 500;
  }
  
  /* FIXED: Removed conflicting column background colors that were hiding text */
  /* Instead, using border-left colors for visual separation */
  td:nth-child(1) { /* Date column */
    border-left: 4px solid #56ccf2 !important;
  }
  
  td:nth-child(2) { /* Product column */
    border-left: 4px solid #2ecc71 !important;
  }
  
  td:nth-child(3) { /* Daily MT column */
    border-left: 4px solid #f1c40f !important;
  }
  
  td:nth-child(4) { /* Plant Load column */
    border-left: 4px solid #9b59b6 !important;
  }
  
  td:nth-child(5) { /* Remarks column */
    border-left: 4px solid #3498db !important;
  }
  
  td:nth-child(6) { /* Action column */
    border-left: 4px solid #e74c3c !important;
  }
  
  /* Status indicators based on values - FIXED to show text */
  .plant-load-high {
    color: #27ae60 !important;
    font-weight: 700;
  }
  .plant-load-medium {
    color: #f39c12 !important;
    font-weight: 700;
  }
  .plant-load-low {
    color: #e74c3c !important;
    font-weight: 700;
  }
  
  /* Badge for product types - FIXED to show text */
  .product-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .product-urea { 
    background-color: #e8f6f3 !important; 
    color: #16a085 !important; 
    border: 1px solid #16a085;
  }
  .product-dap { 
    background-color: #fef9e7 !important; 
    color: #f39c12 !important; 
    border: 1px solid #f39c12;
  }
  .product-npk { 
    background-color: #f4ecf7 !important; 
    color: #8e44ad !important; 
    border: 1px solid #8e44ad;
  }
  .product-sanitary { 
    background-color: #eaf2f8 !important; 
    color: #3498db !important; 
    border: 1px solid #3498db;
  }
  .product-insulator { 
    background-color: #f9ebea !important; 
    color: #c0392b !important; 
    border: 1px solid #c0392b;
  }
  .product-refractories { 
    background-color: #e8f8f5 !important; 
    color: #1abc9c !important; 
    border: 1px solid #1abc9c;
  }
  
  /* Scrollbar styling */
  .table-responsive::-webkit-scrollbar {
    width: 8px;
  }
  .table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
  }
  .table-responsive::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 4px;
  }
  
  /* Header enhancements */
  .gradient-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
  }
  
  /* Card styles */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
  }
  .bg-gradient-success {
    background: linear-gradient(135deg, #2ecc71 0%, #1abc9c 100%) !important;
  }
  .bg-gradient-info {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
  }
  .bg-gradient-warning {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
  }
  
  /* Fix text visibility in cards */
  .card.text-white .card-subtitle,
  .card.text-white .card-title {
    color: white !important;
  }
  
  /* Ensure table text is visible */
  .table td {
    color: #495057 !important;
  }
  
  /* Specific fix for remarks column */
  td:nth-child(5) {
    color: #495057 !important;
  }
</style>

<div class="container-fluid my-0 border shadow rounded p-4 bg-light"> 
  <!-- Header row with gradient -->
  <div class="row align-items-center gradient-header mb-2">
    <div class="col-lg-3 col-md-12 text-center text-lg-start mb-2 mb-lg-0">
      <div class="d-flex align-items-center">
        <i class="fa fa-industry fa-2x me-3 text-white"></i>
        <div>
          <h5 class="mb-0 text-white-50">Production Dashboard</h5>
          <small class="text-white-75">Real-time Monitoring</small>
        </div>
      </div>
    </div>
    <div class="col-lg-6 col-md-12 text-center">
      <h2 class="text-uppercase mb-0 text-white">
        <i class="fa fa-chart-line me-2"></i>DFMS 
        <b class="text-warning">[<?php echo strtoupper($table) ?>]</b>
      </h2>
      <p class="mb-0 text-white-75 mt-1">
        <i class="fa fa-calendar-alt me-1"></i> 
        <?php echo date('F d, Y'); ?> | 
        <i class="fa fa-clock me-1 ms-2"></i>
        <span id="liveTime"></span>
      </p>
    </div>
    <div class="col-lg-3 col-md-12 text-center text-lg-end mt-2 mt-lg-0">
      <a href="dashboard.php?username=<?= $_SESSION['username'] ?>&user_type=<?= $_SESSION['user_type'] ?>" 
         class="btn btn-outline-light me-2 mb-2 mb-lg-0">
        <i class="fa fa-arrow-left me-1"></i> Dashboard
      </a>
      <a class="btn btn-outline-warning mb-2 mb-lg-0" href="logout.php">
        <i class="fa fa-sign-out-alt me-1"></i> Logout
      </a>
    </div>
  </div>

  <!-- Stats summary row -->
  <div class="row mb-4">
    <div class="col-md-3 col-6 mb-1">
      <div class="card border-0 shadow-sm bg-gradient-primary text-white">
        <div class="card-body text-center py-3">
          <h6 class="card-subtitle mb-2 opacity-75">Total Records</h6>
          <?php
          $count_query = "SELECT COUNT(*) as total FROM $table";
          $count_result = mysqli_query($conn, $count_query);
          $count_data = mysqli_fetch_assoc($count_result);
          ?>
          <h3 class="card-title mb-0"><?php echo $count_data['total']; ?></h3>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6 mb-1">
      <div class="card border-0 shadow-sm bg-gradient-success text-white">
        <div class="card-body text-center py-3">
          <h6 class="card-subtitle mb-2 opacity-75">Last Updated</h6>
          <?php
          $last_query = "SELECT date FROM $table ORDER BY date DESC LIMIT 1";
          $last_result = mysqli_query($conn, $last_query);
          $last_date = mysqli_fetch_assoc($last_result);
          ?>
          <h5 class="card-title mb-0"><?php echo isset($last_date['date']) ? date('M d, Y', strtotime($last_date['date'])) : 'N/A'; ?></h5>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6 mb-1">
      <div class="card border-0 shadow-sm bg-gradient-info text-white">
        <div class="card-body text-center py-3">
          <h6 class="card-subtitle mb-2 opacity-75">User Type</h6>
          <h5 class="card-title mb-0 text-uppercase"><?php echo $user_type; ?></h5>
        </div>
      </div>
    </div>
    <div class="col-md-3 col-6 mb-1">
      <div class="card border-0 shadow-sm bg-gradient-warning text-white">
        <div class="card-body text-center py-3">
          <h6 class="card-subtitle mb-2 opacity-75">Data Status</h6>
          <h5 class="card-title mb-0">Active</h5>
        </div>
      </div>
    </div>
  </div>

  <!-- Table row -->
  <div class="row">
    <div class="col-12">
      <div class="card border-0 shadow">
        <div class="card-header bg-white border-0 py-2">
          <h5 class="card-title mb-0 text-dark">
            <i class="fa fa-table me-2 text-primary"></i>
            Production Records
            <span class="badge bg-primary ms-2">Live Data</span>
          </h5>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered text-center align-middle mb-0">
              <thead>
                <tr>
                  <th width="15%"><i class="fa fa-calendar me-1"></i> Date</th>
                  <th width="20%"><i class="fa fa-cube me-1"></i> Product</th>
                  <th width="15%"><i class="fa fa-chart-bar me-1"></i> Daily (MT)</th>              
                  <th width="15%"><i class="fa fa-tachometer-alt me-1"></i> Plant Load (%)</th>
                  <th width="25%"><i class="fa fa-comment me-1"></i> Remarks</th>
                  <?php if ($user_type == 'admin' || $user_type == 'sadmin') { ?>
                    <th width="10%"><i class="fa fa-cogs me-1"></i> Action</th>
                  <?php } ?>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($table != 'bisf') {
                  $query = "SELECT * FROM $table ORDER BY date DESC";
                } else {
                  $query = "SELECT * FROM bisf ORDER BY date DESC, FIELD(product_produce, 'sanitary', 'insulator', 'refractories')";
                }
                $query_run = mysqli_query($conn, $query);

                if (mysqli_num_rows($query_run) > 0) {
                  foreach ($query_run as $row) {
                    // Determine plant load class
                    $plant_load = (float)$row['plant_load'];
                    $plant_load_class = '';
                    if ($plant_load >= 80) {
                      $plant_load_class = 'plant-load-high';
                    } elseif ($plant_load >= 50) {
                      $plant_load_class = 'plant-load-medium';
                    } else {
                      $plant_load_class = 'plant-load-low';
                    }
                    
                    // Determine product badge class
                    $product = strtolower($row['product_produce']);
                    $badge_class = '';
                    switch ($product) {
                      case 'urea':
                      case 'sanitary':
                      case 'dap':
                      case 'npk':
                      case 'insulator':
                      case 'refractories':
                        $badge_class = 'product-' . $product;
                        break;
                      default:
                        $badge_class = 'product-urea';
                    }
                    ?>
                    <tr>
                      <td>
                        <div class="d-flex flex-column">
                          <span class="fw-bold"><?= date('d M Y', strtotime($row['date'])) ?></span>
                          <small class="text-muted"><?= date('l', strtotime($row['date'])) ?></small>
                        </div>
                      </td>
                      <td>
                        <span class="product-badge <?= $badge_class ?>">
                          <?= htmlspecialchars(ucfirst($row['product_produce'])) ?>
                        </span>
                      </td>
                      <td>
                        <span class="fw-bold"><?= round((float)$row['daily'], 2); ?></span>
                        <small class="text-muted d-block">Metric Tons</small>
                      </td>                  
                      <td>
                        <span class="<?= $plant_load_class ?>">
                          <?= htmlspecialchars($row['plant_load']); ?>%
                        </span>
                        <div class="progress mt-1" style="height: 6px;">
                          <div class="progress-bar <?= $plant_load >= 80 ? 'bg-success' : ($plant_load >= 50 ? 'bg-warning' : 'bg-danger') ?>" 
                               role="progressbar" 
                               style="width: <?= min($plant_load, 100) ?>%">
                          </div>
                        </div>
                      </td>
                      <td class="text-start">
                        <div class="d-flex align-items-center">
                          <!-- <i class="fa fa-sticky-note text-primary me-2"></i> -->
                          <span><?= htmlspecialchars($row['remarks']); ?></span>
                        </div>
                      </td>
                      <?php if ($user_type == 'admin' || $user_type == 'sadmin') { ?>
                        <td>
                          <a href="edit_urea.php?id=<?= $row['id'] ?>" 
                             class="btn btn-warning btn-sm rounded-pill px-3">
                            <i class="fa fa-edit me-1"></i> Edit
                          </a>
                        </td>
                      <?php } ?>
                    </tr>
                    <?php
                  }
                } else {
                  ?>
                  <tr>
                    <td colspan="<?php echo ($user_type == 'admin' || $user_type == 'sadmin') ? '6' : '5'; ?>" 
                        class="text-center py-5">
                      <div class="text-muted">
                        <i class="fa fa-database fa-3x mb-3 opacity-25"></i>
                        <h4 class="text-danger">No Records Found</h4>
                        <p class="mb-0">Start by adding your first production record</p>
                      </div>
                    </td>
                  </tr>
                  <?php
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer bg-white border-0 py-3">
          <div class="row align-items-center">
            <div class="col-md-6">
              <small class="text-muted">
                <i class="fa fa-info-circle me-1 text-primary"></i>
                Showing all production records for <strong><?php echo ucfirst($table); ?></strong>
              </small>
            </div>
            <div class="col-md-6 text-end">
              <small class="text-muted">
                <i class="fa fa-sync-alt me-1 text-success"></i>
                Auto-refresh in <span id="refreshCountdown">60</span>s
              </small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Live time and auto-refresh script -->
<script>
  // Update live time
  function updateLiveTime() {
    const now = new Date();
    const timeString = now.toLocaleTimeString('en-US', { 
      hour12: true, 
      hour: '2-digit', 
      minute: '2-digit',
      second: '2-digit'
    });
    document.getElementById('liveTime').textContent = timeString;
  }
  
  // Auto-refresh countdown
  let countdown = 60;
  function updateCountdown() {
    countdown--;
    document.getElementById('refreshCountdown').textContent = countdown;
    
    if (countdown <= 0) {
      location.reload();
    }
  }
  
  // Initialize
  updateLiveTime();
  setInterval(updateLiveTime, 1000);
  setInterval(updateCountdown, 1000);
</script>

<?php include('../include/footer.php'); ?>