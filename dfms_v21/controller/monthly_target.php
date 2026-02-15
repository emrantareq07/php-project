<?php
session_name('dfms');
session_start();
$table = $_SESSION['username'];
$user_type = $_SESSION['user_type'];

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include('../db/db.php');

// Calculate fiscal year range
$date = date('Y-m-d');
$month11 = date('m', strtotime($date));
$year11 = date('Y', strtotime($date));

if ($month11 >= 7) {
    $year22 = $year11;
} else {
    $year22 = $year11 - 1;
}

$yearrange12 = "$year22-07-01";
$year22++;
$yearrange13 = "$year22-06-30";

// Handle record update
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $target = $_POST['target'];

    $sql_update = "UPDATE monthly_target SET target = '$target' WHERE id = '$id'";

    if (mysqli_query($conn, $sql_update)) {
        header("Location: monthly_target.php?updated=successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Include header
include('../include/header.php');

if (isset($_POST['insert'])) {   
    $target = $_POST['target'];
    $product_produce = $_POST['product_produce'];
     $target_date = $_POST['target_date'];

     $month_id = date('Y-m', strtotime($target_date));

    $fiscalstart = $_POST['fiscalstart'];
    $fiscalend = $_POST['fiscalend'];
       $sql_check = "SELECT * FROM monthly_target 
              WHERE factory_name = '$table' 
              AND fiscalstart = '$fiscalstart' 
              AND product_produce = '$product_produce' 
              AND target_date LIKE '$month_id%' ";

    $result_check = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($result_check) == 0) {    
    $sql_insert = "INSERT INTO monthly_target (factory_name, product_produce, fiscalstart, fiscalend, target,target_date) VALUES ('$table', '$product_produce', '$fiscalstart', '$fiscalend', '$target','$target_date')";

    if (mysqli_query($conn, $sql_insert)) {
        header("Location: monthly_target.php?inserted=successfully");
        exit();
    }
} else {
        header("Location: monthly_target.php?existed=successfully");
        exit();
        }
}

// Fetch all records for display
$sql_fetch = "SELECT * FROM monthly_target WHERE factory_name = '$table' order by id desc";
$result_fetch = mysqli_query($conn, $sql_fetch);

// Get stats for dashboard
$total_targets = mysqli_num_rows($result_fetch);
mysqli_data_seek($result_fetch, 0); // Reset pointer

// Get current month target if exists
$current_month = date('Y-m');
$current_month_target = 0;
$check_current = mysqli_query($conn, "SELECT target FROM monthly_target WHERE factory_name = '$table' AND target_date LIKE '$current_month%' LIMIT 1");
if ($check_current && mysqli_num_rows($check_current) > 0) {
    $current_data = mysqli_fetch_assoc($check_current);
    $current_month_target = $current_data['target'];
}
?>

<style>
/* Gradient backgrounds */
.gradient-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 25px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.gradient-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border: none;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.gradient-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.15);
}

.card-header-gradient {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    color: white !important;
    border-bottom: none !important;
    padding: 20px !important;
    border-radius: 15px 15px 0 0 !important;
}

/* Form styling */
.form-control {
    border: 2px solid #e9ecef !important;
    border-radius: 10px !important;
    padding: 12px 15px !important;
    transition: all 0.3s ease !important;
    font-weight: 500 !important;
}

.form-control:focus {
    border-color: #f39c12 !important;
    box-shadow: 0 0 0 0.25rem rgba(243, 156, 18, 0.25) !important;
    transform: translateY(-1px);
}

.form-label {
    font-weight: 600 !important;
    color: #495057 !important;
    margin-bottom: 8px !important;
    display: flex !important;
    align-items: center !important;
}

.form-label i {
    margin-right: 8px !important;
    color: #f39c12 !important;
}

/* Button styling */
.btn {
    border-radius: 10px !important;
    padding: 12px 24px !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
    border: none !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.btn-primary {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #e67e22 0%, #f39c12 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(243, 156, 18, 0.3) !important;
}

.btn-success {
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%) !important;
    color: white !important;
}

.btn-success:hover {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(46, 204, 113, 0.3) !important;
}

.btn-outline-primary {
    border: 2px solid #f39c12 !important;
    color: #f39c12 !important;
    background: transparent !important;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    color: white !important;
    transform: translateY(-2px);
}

/* Alert styling */
.alert {
    border-radius: 10px !important;
    border: none !important;
    padding: 15px 20px !important;
    font-weight: 500 !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    animation: slideIn 0.5s ease-out;
}

.alert-success {
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%) !important;
    color: white !important;
}

.alert-danger {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
    color: white !important;
}

.alert-warning {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    color: white !important;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Table styling */
.table-container {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.table {
    margin-bottom: 0 !important;
}

.table thead th {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    color: white !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    font-size: 14px !important;
    letter-spacing: 0.5px !important;
    border: none !important;
    padding: 15px 12px !important;
    vertical-align: middle !important;
}

.table-striped tbody tr:nth-of-type(odd) {
    background-color: rgba(52, 152, 219, 0.05) !important;
}

.table-hover tbody tr:hover {
    background-color: rgba(52, 152, 219, 0.15) !important;
    transform: scale(1.002);
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #e9ecef !important;
    padding: 12px 10px !important;
    vertical-align: middle !important;
}

/* Status badges */
.status-badge {
    padding: 6px 15px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-current {
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
    color: white;
}

.badge-upcoming {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    color: white;
}

.badge-past {
    background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
    color: white;
}

/* Factory badge */
.factory-badge {
    background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%) !important;
    color: white !important;
    padding: 8px 20px !important;
    border-radius: 20px !important;
    font-weight: 600 !important;
    display: inline-block !important;
    box-shadow: 0 4px 8px rgba(155, 89, 182, 0.3) !important;
}

/* Progress indicator */
.progress-indicator {
    height: 4px;
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    border-radius: 2px;
    margin-top: 5px;
    transition: width 0.3s ease;
}

/* Month badge */
.month-badge {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    color: white;
    padding: 4px 12px;
    border-radius: 15px;
    font-weight: 600;
    font-size: 11px;
    display: inline-block;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .gradient-header {
        padding: 15px !important;
        margin-bottom: 20px !important;
    }
    
    .gradient-card {
        margin-bottom: 20px !important;
    }
    
    .btn {
        padding: 10px 15px !important;
        font-size: 14px !important;
    }
}

/* Custom input group */
.input-group-custom .input-group-text {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    color: white;
    border: none;
    border-radius: 10px 0 0 10px;
    font-weight: 600;
}

/* Animated icons */
.animated-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

/* Metric cards */
.metric-card {
    background: white;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border-left: 4px solid;
}

.metric-card:nth-child(1) { border-left-color: #3498db; }
.metric-card:nth-child(2) { border-left-color: #2ecc71; }
.metric-card:nth-child(3) { border-left-color: #f39c12; }
.metric-card:nth-child(4) { border-left-color: #9b59b6; }

.metric-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.metric-value {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    margin: 10px 0;
}

.metric-label {
    font-size: 14px;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Target completion indicator */
.target-progress {
    height: 8px;
    border-radius: 4px;
    background-color: #ecf0f1;
    margin: 10px 0;
    overflow: hidden;
}

.target-progress-bar {
    height: 100%;
    border-radius: 4px;
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    transition: width 1s ease;
}

/* Month status indicator */
.month-status {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 8px;
}

.status-current { background-color: #2ecc71; }
.status-upcoming { background-color: #3498db; }
.status-past { background-color: #95a5a6; }

/* Date picker custom */
input[type="date"]::-webkit-calendar-picker-indicator {
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%23f39c12" viewBox="0 0 16 16"><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>');
    cursor: pointer;
}
</style>

<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row">
        <div class="col-12">
            <div class="gradient-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-3 text-white">
                            <i class="fas fa-calendar-alt me-3 animated-icon"></i>
                            Monthly Target Management
                        </h2>
                        <p class="mb-0 text-white-75">
                            <i class="fa fa-industry me-2"></i>
                            <span class="factory-badge"><?php echo strtoupper($table); ?></span>
                            <span class="badge bg-light text-dark ms-2">
                                <i class="fa fa-user me-1"></i><?php echo ucfirst($user_type); ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end text-center">
                        <div class="d-flex flex-column align-items-md-end">
                            <h6 class="text-white mb-2">
                                <i class="fa fa-calendar me-1"></i>
                                <?php echo date('F Y'); ?>
                            </h6>
                            <a href="dashboard.php" class="btn btn-outline-light">
                                <i class="fa fa-arrow-left me-2"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="metric-card">
                <div class="metric-value"><?php echo $total_targets; ?></div>
                <div class="metric-label">
                    <i class="fa fa-list-alt me-2"></i>Total Targets
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="metric-card">
                <div class="metric-value">
                    <?php echo $current_month_target > 0 ? number_format($current_month_target) . ' MT' : 'Not Set'; ?>
                </div>
                <div class="metric-label">
                    <i class="fa fa-bullseye me-2"></i>Current Month
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="metric-card">
                <div class="metric-value">
                    <?php echo date('M', strtotime($yearrange12)); ?> - <?php echo date('M Y', strtotime($yearrange13)); ?>
                </div>
                <div class="metric-label">
                    <i class="fa fa-calendar-alt me-2"></i>Fiscal Year
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="metric-card">
                <div class="metric-value">
                    <?php 
                    $current_date = date('Y-m-d');
                    $status = ($current_date >= $yearrange12 && $current_date <= $yearrange13) ? 'Active' : 'Planning';
                    echo $status;
                    ?>
                </div>
                <div class="metric-label">
                    <i class="fa fa-chart-line me-2"></i>Fiscal Status
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <div class="row">
        <div class="col-12">
            <?php if (isset($_GET['updated'])) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successMessage">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Success!</h5>
                            <p class="mb-0">Monthly target has been updated successfully.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } elseif (isset($_GET['inserted'])) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successMessage">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-check-circle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Success!</h5>
                            <p class="mb-0">New monthly target has been set successfully.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } elseif (isset($_GET['existed'])) { ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert" id="successMessage">
                    <div class="d-flex align-items-center">
                        <i class="fa fa-exclamation-triangle fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading mb-1">Duplicate Entry!</h5>
                            <p class="mb-0">Target for this month and product already exists.</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Form Section -->
    <div class="row mb-5">
        <div class="col-lg-8 mx-auto">
            <div class="gradient-card">
                <div class="card-header card-header-gradient">
                    <h5 class="mb-0 text-white">
                        <i class="fa fa-calendar-plus me-2"></i>
                        Set/Update Monthly Target
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="" method="POST" id="targetForm">
                        <input type="hidden" name="id" id="recordId" value="">
                        
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="product_produce" class="form-label">
                                    <i class="fa fa-cube"></i> Product Type
                                </label>
                                <select class="form-control" name="product_produce" id="product_produce">
                                    <?php
                                    if (in_array($table, ["sfcl", "jfcl", "afccl", "gpfplc", "cufl"])) {
                                        echo '<option value="Urea">Urea</option>';
                                    } elseif ($table == "tspcl") {
                                        echo '<option value="TSP">TSP</option>';
                                    } elseif ($table == "dapfcl") {
                                        echo '<option value="DAP">DAP</option>';
                                    } elseif ($table == "kpml") {
                                        echo '<option value="Paper">Paper</option>';
                                    } elseif ($table == "cccl") {
                                        echo '<option value="Cement">Cement</option>';
                                    } elseif ($table == "ugsf") {
                                        echo '<option value="Sheet Glass">Sheet Glass</option>';
                                    } else {
                                        echo '<option value="sanitary">Sanitary Ware</option>';
                                        echo '<option value="insulator">Insulator</option>';
                                        echo '<option value="refractories">Refractories</option>';
                                    }
                                    ?>
                                </select>
                                <div class="progress-indicator" id="product-indicator"></div>
                            </div>
                            
                            <div class="col-md-6 mb-4">
                                <label for="target_date" class="form-label">
                                    <i class="fa fa-calendar-day"></i> Target Month
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-calendar-alt"></i>
                                    </span>
                                    <input type="date" class="form-control" 
                                           name="target_date" id="target_date" required>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <i class="fa fa-info-circle me-1"></i>
                                    Select any date within the target month
                                </small>
                                <div class="progress-indicator" id="date-indicator"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label for="target" class="form-label">
                                    <i class="fa fa-bullseye"></i> Monthly Production Target (MT)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fa fa-weight-hanging"></i>
                                    </span>
                                    <input type="text" class="form-control" 
                                           placeholder="Enter target in metric tons" 
                                           name="target" id="target" required>
                                    <span class="input-group-text bg-light">MT</span>
                                </div>
                                <div class="progress-indicator" id="target-indicator"></div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label">
                                    <i class="fa fa-calendar-alt"></i> Fiscal Year Range
                                </label>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fiscalstart" class="form-label small">Start Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fa fa-calendar-start"></i>
                                            </span>
                                            <input type="date" class="form-control" name="fiscalstart" 
                                                   id="fiscalstart" value="<?= $yearrange12; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="fiscalend" class="form-label small">End Date</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fa fa-calendar-end"></i>
                                            </span>
                                            <input type="date" class="form-control" name="fiscalend" 
                                                   id="fiscalend" value="<?= $yearrange13; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-3 d-md-flex justify-content-md-center mt-4">
                            <button type="submit" name="insert" id="formSubmitBtn" class="btn btn-primary btn-lg px-5">
                                <i class="fa fa-save me-2"></i> 
                                <span id="submitText">Set Monthly Target</span>
                            </button>
                            <button type="reset" class="btn btn-outline-primary btn-lg px-5">
                                <i class="fa fa-redo me-2"></i> Reset Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Targets Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="gradient-card">
                <div class="card-header card-header-gradient">
                    <h5 class="mb-0 text-white">
                        <i class="fa fa-table me-2"></i>
                        Monthly Targets Overview
                        <span class="badge bg-light text-dark ms-2"><?php echo $total_targets; ?> Records</span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-container">
                        <table class="table table-striped table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th><i class="fa fa-cube me-2"></i>Product</th>
                                    <th class="text-center"><i class="fa fa-bullseye me-2"></i>Target (MT)</th>
                                    <th><i class="fa fa-calendar me-2"></i>Target Month</th>
                                    <th><i class="fa fa-calendar-start me-2"></i>Fiscal Start</th>
                                    <th><i class="fa fa-calendar-end me-2"></i>Fiscal End</th>
                                    <th><i class="fa fa-chart-bar me-2"></i>Status</th>
                                    <?php if ($user_type == 'admin' || $user_type == 'sadmin'): ?>
                                    <th class="text-center"><i class="fa fa-cogs me-2"></i>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                mysqli_data_seek($result_fetch, 0); // Reset pointer again
                                while ($row = mysqli_fetch_assoc($result_fetch)): 
                                    $target_month = date('F Y', strtotime($row['target_date']));
                                    $current_month = date('F Y');
                                    $status_class = '';
                                    $status_text = '';
                                    
                                    if ($target_month == $current_month) {
                                        $status_class = 'status-current';
                                        $status_text = 'Current';
                                    } elseif (strtotime($row['target_date']) > strtotime('now')) {
                                        $status_class = 'status-upcoming';
                                        $status_text = 'Upcoming';
                                    } else {
                                        $status_class = 'status-past';
                                        $status_text = 'Past';
                                    }
                                ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $row['id']; ?></td>
                                    <td>
                                        <span class="status-badge badge-active">
                                            <?= $row['product_produce']; ?>
                                        </span>
                                    </td>
                                    <td class="text-center fw-bold text-primary">
                                        <div class="d-flex flex-column align-items-center">
                                            <span><?= number_format($row['target']); ?> MT</span>
                                            <div class="target-progress">
                                                <div class="target-progress-bar" style="width: <?php echo min(100, ($row['target']/10000)*100); ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="month-status <?= $status_class; ?>"></span>
                                            <?= date('F Y', strtotime($row['target_date'])); ?>
                                            <?php if ($target_month == $current_month): ?>
                                                <span class="month-badge ms-2">Current</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($row['fiscalstart'])); ?></td>
                                    <td><?= date('M d, Y', strtotime($row['fiscalend'])); ?></td>
                                    <td>
                                        <span class="badge <?= 
                                            $status_text == 'Current' ? 'badge-current' : 
                                            ($status_text == 'Upcoming' ? 'badge-upcoming' : 'badge-past')
                                        ?>">
                                            <?= $status_text; ?>
                                        </span>
                                    </td>
                                    <?php if ($user_type == 'admin' || $user_type == 'sadmin'): ?>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-success btn-sm editBtn" 
                                            data-id="<?= $row['id']; ?>" 
                                            data-product="<?= $row['product_produce']; ?>" 
                                            data-target="<?= $row['target']; ?>"
                                            data-date="<?= $row['target_date']; ?>"
                                            data-fiscalstart="<?= $row['fiscalstart']; ?>"
                                            data-fiscalend="<?= $row['fiscalend']; ?>">
                                            <i class="fa fa-edit me-1"></i> Edit
                                        </button>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fa fa-info-circle me-1 text-primary"></i>
                                Showing monthly targets for <strong><?php echo strtoupper($table); ?></strong>
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="fa fa-clock me-1 text-warning"></i>
                                Last Updated: <?php echo date('h:i A'); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Handle Edit Button Click
document.querySelectorAll('.editBtn').forEach(button => {
    button.addEventListener('click', function () {
        const id = this.dataset.id;
        const product = this.dataset.product;
        const target = this.dataset.target;
        const date = this.dataset.date;
        const fiscalstart = this.dataset.fiscalstart;
        const fiscalend = this.dataset.fiscalend;

        // Populate form fields
        document.getElementById('recordId').value = id;
        document.getElementById('product_produce').value = product;
        document.getElementById('target').value = target;
        document.getElementById('target_date').value = date;
        document.getElementById('fiscalstart').value = fiscalstart;
        document.getElementById('fiscalend').value = fiscalend;

        // Make product_produce field readonly
        document.getElementById('product_produce').setAttribute('disabled', true);

        // Change form button text and style
        const submitBtn = document.getElementById('formSubmitBtn');
        const submitText = document.getElementById('submitText');
        submitBtn.innerHTML = '<i class="fa fa-sync me-2"></i> Update Target';
        submitBtn.setAttribute("name", "update");
        submitText.textContent = "Update Target";
        
        // Change button color to success
        submitBtn.classList.remove('btn-primary');
        submitBtn.classList.add('btn-success');

        // Scroll to form
        document.querySelector('.gradient-card').scrollIntoView({
            behavior: 'smooth'
        });
    });
});

// Reset form to default state
document.getElementById('targetForm').addEventListener('reset', function () {
    // Remove disabled attribute for product_produce
    document.getElementById('product_produce').removeAttribute('disabled');

    // Reset button text and style
    const submitBtn = document.getElementById('formSubmitBtn');
    const submitText = document.getElementById('submitText');
    submitBtn.innerHTML = '<i class="fa fa-save me-2"></i> Set Monthly Target';
    submitBtn.setAttribute("name", "insert");
    submitText.textContent = "Set Monthly Target";
    
    // Reset button color
    submitBtn.classList.remove('btn-success');
    submitBtn.classList.add('btn-primary');
    
    // Clear hidden field
    document.getElementById('recordId').value = '';
});

// Auto-hide alerts
setTimeout(function () {
    const successMessage = document.getElementById('successMessage');
    if (successMessage) {
        successMessage.style.transition = 'opacity 0.5s';
        successMessage.style.opacity = '0';
        setTimeout(() => {
            if (successMessage.parentNode) {
                successMessage.parentNode.removeChild(successMessage);
            }
        }, 500);
    }
}, 3000);

// Input progress indicators
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.form-control');
    
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            const indicatorId = this.id + '-indicator';
            const indicator = document.getElementById(indicatorId);
            if (indicator) {
                indicator.style.width = '100%';
            }
        });
        
        input.addEventListener('blur', function() {
            const indicatorId = this.id + '-indicator';
            const indicator = document.getElementById(indicatorId);
            if (indicator && !this.value) {
                indicator.style.width = '0%';
            }
        });
        
        // Check initial value
        if (input.value) {
            const indicatorId = input.id + '-indicator';
            const indicator = document.getElementById(indicatorId);
            if (indicator) {
                indicator.style.width = '100%';
            }
        }
    });
});

// Form validation
document.getElementById('targetForm').addEventListener('submit', function(e) {
    const targetInput = document.getElementById('target');
    const targetValue = targetInput.value.trim();
    const dateInput = document.getElementById('target_date');
    const dateValue = dateInput.value;
    
    // Validate numeric input for target
    if (!/^\d+$/.test(targetValue)) {
        e.preventDefault();
        targetInput.classList.add('is-invalid');
        showToast('Please enter a valid numeric target value.', 'error');
        return false;
    }
    
    // Validate date input
    if (!dateValue) {
        e.preventDefault();
        dateInput.classList.add('is-invalid');
        showToast('Please select a target month.', 'error');
        return false;
    }
    
    // Clear any previous error states
    targetInput.classList.remove('is-invalid');
    dateInput.classList.remove('is-invalid');
});

// Toast notification function
function showToast(message, type = 'success') {
    const toastContainer = document.createElement('div');
    toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
    toastContainer.style.zIndex = '9999';
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'error' ? 'danger' : 'success'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fa ${type === 'error' ? 'fa-exclamation-circle' : 'fa-check-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    document.body.appendChild(toastContainer);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast after hiding
    toast.addEventListener('hidden.bs.toast', function () {
        toastContainer.remove();
    });
}

// Set default date to current month
document.addEventListener('DOMContentLoaded', function() {
    const today = new Date();
    const currentMonth = today.toISOString().split('T')[0].slice(0, 7) + '-15'; // Middle of month
    document.getElementById('target_date').value = currentMonth;
});
</script>

<?php include('../include/footer.php'); ?>