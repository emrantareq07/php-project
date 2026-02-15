<?php
session_name('dfms');
error_reporting(0);
session_start();

if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

// Check user type FIRST before including any headers or content
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];

if ($user_type != 'sadmin' && $user_type != 'admin') {
    header("Location: access_denied.php");
    exit(); 
}

$date = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

include('../db/db_PDO.php');
include('../include/header_index.php');
?> 

<style>
/* Modern Gradient Theme */
.gradient-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 25px;
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
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    color: white !important;
    border-bottom: none !important;
    padding: 15px 20px !important;
    border-radius: 15px 15px 0 0 !important;
}

/* Button Styling */
.btn {
    border-radius: 10px !important;
    padding: 10px 20px !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
    border: none !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.3) !important;
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

.btn-danger {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%) !important;
    color: white !important;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(231, 76, 60, 0.3) !important;
}

.btn-warning {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    color: white !important;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e67e22 0%, #f39c12 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(243, 156, 18, 0.3) !important;
}

.btn-info {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    color: white !important;
}

.btn-info:hover {
    background: linear-gradient(135deg, #2980b9 0%, #3498db 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3) !important;
}

.btn-outline-light {
    border: 2px solid rgba(255, 255, 255, 0.3) !important;
    color: white !important;
    background: transparent !important;
}

.btn-outline-light:hover {
    background: rgba(255, 255, 255, 0.2) !important;
    transform: translateY(-2px);
}

/* Form Styling */
.form-control {
    border: 2px solid #e9ecef !important;
    border-radius: 10px !important;
    padding: 10px 15px !important;
    transition: all 0.3s ease !important;
    font-weight: 500 !important;
}

.form-control:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25) !important;
    transform: translateY(-1px);
}

/* Table Styling */
.table-container {
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
}

.table {
    margin-bottom: 0 !important;
    font-size: 0.85rem !important;
}

.table thead th {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    color: white !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    font-size: 12px !important;
    letter-spacing: 0.5px !important;
    border: none !important;
    padding: 12px 8px !important;
    vertical-align: middle !important;
    white-space: nowrap;
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
    padding: 10px 8px !important;
    vertical-align: middle !important;
}

/* Column Coloring */
.table td:nth-child(1), .table th:nth-child(1) { border-left: 4px solid #95a5a6 !important; }
.table td:nth-child(2), .table th:nth-child(2) { border-left: 4px solid #3498db !important; }
.table td:nth-child(3), .table th:nth-child(3) { border-left: 4px solid #2ecc71 !important; }
.table td:nth-child(4), .table th:nth-child(4) { border-left: 4px solid #f39c12 !important; }
.table td:nth-child(5), .table th:nth-child(5) { border-left: 4px solid #9b59b6 !important; }
.table td:nth-child(6), .table th:nth-child(6) { border-left: 4px solid #e74c3c !important; }
.table td:nth-child(7), .table th:nth-child(7) { border-left: 4px solid #1abc9c !important; }
.table td:nth-child(8), .table th:nth-child(8) { border-left: 4px solid #d35400 !important; }
.table td:nth-child(9), .table th:nth-child(9) { border-left: 4px solid #34495e !important; }
.table td:nth-child(10), .table th:nth-child(10) { border-left: 4px solid #8e44ad !important; }
.table td:nth-child(11), .table th:nth-child(11) { border-left: 4px solid #f1c40f !important; }
.table td:nth-child(12), .table th:nth-child(12) { border-left: 4px solid #e67e22 !important; }
.table td:nth-child(13), .table th:nth-child(13) { border-left: 4px solid #16a085 !important; }

/* Value Indicators */
.high-value { color: #27ae60 !important; font-weight: 700; }
.medium-value { color: #f39c12 !important; font-weight: 700; }
.low-value { color: #e74c3c !important; font-weight: 700; }

/* Factory Badge */
.factory-badge {
    background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%) !important;
    color: white !important;
    padding: 6px 15px !important;
    border-radius: 20px !important;
    font-weight: 600 !important;
    display: inline-block !important;
    box-shadow: 0 4px 8px rgba(155, 89, 182, 0.3) !important;
}

/* Dropdown Styling */
.dropdown-menu {
    border-radius: 10px !important;
    border: none !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    padding: 10px !important;
}

.dropdown-item {
    border-radius: 8px !important;
    padding: 8px 15px !important;
    margin: 2px 0 !important;
    transition: all 0.2s ease !important;
}

.dropdown-item:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    transform: translateX(5px);
}

/* Print Styles */
@media print {
    .gradient-header, .btn, .dropdown, #reload-btn, #reload-message,
    .form-control, .form-group, .card-header-gradient {
        display: none !important;
    }
    
    @page {
        size: A4 landscape;
        margin: 5mm 2mm;
    }
    
    body {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
    }
    
    .table thead th {
        background: #f8f9fa !important;
        color: black !important;
        border: 1px solid #000 !important;
    }
    
    .table td, .table th {
        border: 1px solid #000 !important;
        color: black !important;
    }
    
    /* Remove colors for print */
    .table td:nth-child(n), .table th:nth-child(n) {
        border-left: 1px solid #000 !important;
        background-color: white !important;
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .gradient-header {
        padding: 15px !important;
        margin-bottom: 15px !important;
    }
    
    .btn {
        padding: 8px 12px !important;
        font-size: 14px !important;
    }
    
    .table {
        font-size: 0.75rem !important;
    }
    
    .table thead th {
        font-size: 10px !important;
        padding: 8px 4px !important;
    }
    
    .table td {
        padding: 8px 4px !important;
    }
}

/* Scrollbar Styling */
.table-responsive::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 4px;
}

/* Loading Animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-left: 10px;
}

/* Status Indicators */
.status-indicator {
    display: inline-block;
    width: 10px;
    height: 10px;
    border-radius: 50%;
    margin-right: 8px;
}

.status-active { background-color: #2ecc71; }
.status-warning { background-color: #f39c12; }
.status-inactive { background-color: #95a5a6; }

/* Progress Bars */
.progress-bar-custom {
    height: 6px;
    border-radius: 3px;
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
    margin-top: 5px;
}

/* Card Styling for Stats */
.stats-card {
    background: white;
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    border-left: 4px solid;
    margin-bottom: 15px;
}

.stats-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.stats-value {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin: 10px 0;
}

.stats-label {
    font-size: 12px;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 1px;
}
/* Dropdown Positioning Fix */
.dropdown {
    position: relative !important;
}

.dropdown-menu {
    position: absolute !important;
    z-index: 9999 !important;
    transform: translate3d(0, 0, 0) !important;
    will-change: transform !important;
}

/* Specifically for the factory dropdown button area */
.d-flex.flex-wrap.justify-content-end.gap-2 {
    position: relative !important;
    z-index: 1000 !important;
}

/* Make sure the parent containers don't clip the dropdown */
.container-fluid, .row, .col-12, .gradient-card, .card-body {
    overflow: visible !important;
}

/* Fix for Bootstrap dropdown in complex layouts */
.dropdown .dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    left: auto !important;
    right: 0 !important;
    margin-top: 0.125rem !important;
}

/* Ensure dropdown appears above table */
#factoryDropdown + .dropdown-menu {
    z-index: 99999 !important;
}

/* Remove any transform that might be interfering */
.card-body {
    transform: none !important;
}
/* Force dropdown above everything */
.dropdown-menu {
    z-index: 99999 !important;
    position: fixed !important;
}

/* For factory dropdown specifically */
#factoryDropdown + .dropdown-menu {
    z-index: 999999 !important;
}

/* Ensure parent doesn't clip */
body, .container-fluid, .gradient-card {
    overflow: visible !important;
}
/* Two Column Dropdown Styles */
.dropdown-menu[style*="min-width: 400px"] {
    min-width: 400px !important;
    max-width: 400px !important;
    padding: 10px !important;
}

.dropdown-menu .container-fluid {
    padding: 0 !important;
}

.dropdown-menu .row {
    margin: 0 -5px !important;
}

.dropdown-menu .col-6 {
    padding: 0 5px !important;
}

.dropdown-menu .dropdown-item {
    border-radius: 8px !important;
    padding: 8px 12px !important;
    margin: 3px 0 !important;
    white-space: normal !important;
    display: flex !important;
    align-items: center !important;
    transition: all 0.2s ease !important;
}

.dropdown-menu .dropdown-item:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    color: white !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
}

.dropdown-header {
    font-size: 14px !important;
    font-weight: 600 !important;
    color: #2c3e50 !important;
    padding: 8px 15px !important;
    background: rgba(52, 152, 219, 0.1) !important;
    border-radius: 6px !important;
    margin: 5px 0 !important;
}

.dropdown-divider {
    margin: 8px 0 !important;
    border-color: #e9ecef !important;
}

.dropdown-footer {
    padding: 8px 15px !important;
    font-size: 12px !important;
    color: #6c757d !important;
    background: rgba(108, 117, 125, 0.05) !important;
    border-radius: 0 0 10px 10px !important;
}

/* Factory icon colors */
.dropdown-item[href*="sfcl"] i { color: #3498db !important; }
.dropdown-item[href*="jfcl"] i { color: #2ecc71 !important; }
.dropdown-item[href*="afccl"] i { color: #f39c12 !important; }
.dropdown-item[href*="cufl"] i { color: #e74c3c !important; }
.dropdown-item[href*="gpfplc"] i { color: #9b59b6 !important; }
.dropdown-item[href*="tspcl"] i { color: #1abc9c !important; }
.dropdown-item[href*="dapfcl"] i { color: #d35400 !important; }
.dropdown-item[href*="bisf"] i { color: #34495e !important; }
.dropdown-item[href*="cccl"] i { color: #8e44ad !important; }
.dropdown-item[href*="ugsf"] i { color: #f1c40f !important; }
.dropdown-item[href*="kpml"] i { color: #e67e22 !important; }

.dropdown-item:hover i {
    color: white !important;
}

/* Make sure dropdown appears above everything */
.dropdown-menu {
    z-index: 99999 !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    border: 1px solid rgba(0,0,0,0.1) !important;
}

/* Hover effects */
.dropdown-item {
    position: relative;
    overflow: hidden;
}

.dropdown-item:before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: transparent;
    transition: all 0.3s ease;
}

.dropdown-item:hover:before {
    background: white;
}
</style>

<div class="container-fluid py-3">
    <!-- Main Header -->
    <div class="row gradient-header">
        <div class="col-md-12">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2 text-white">
                        <i class="fas fa-industry me-3 animated-icon"></i>
                        BCIC Production Dashboard
                    </h2>
                    <p class="mb-0 text-white-75">
                        <i class="fa fa-user me-2"></i>
                        <span class="badge bg-light text-dark me-2"><?php echo ucfirst($user_type); ?></span>
                        <span class="badge bg-warning">Consolidated Report</span>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex align-items-center justify-content-end">
                        <i class="fa fa-calendar-alt fa-2x me-3 text-white-50"></i>
                        <div class="text-end">
                            <h6 class="mb-0 text-white">Real-time Monitoring</h6>
                            <small class="text-white-75"><?php echo date('F d, Y'); ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Controls Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="gradient-card">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <form class="row g-2 align-items-center" action="" method="post">
                                <div class="col-md-8">
                                    <label class="form-label text-muted mb-1">
                                        <i class="fa fa-calendar me-1"></i> Select Date
                                    </label>
                                    <input type="date" class="form-control shadow-sm" 
                                           placeholder="Enter Date" name="date" id="date" required
                                           value="<?php echo isset($_POST['date']) ? $_POST['date'] : $yesterday; ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label invisible mb-1">Search</label>
                                    <button type="submit" class="btn btn-primary w-100 shadow-sm" 
                                            id="search-btn" name="hit">
                                        <i class="fa fa-search me-1"></i> Search
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col-md-8">
    <div class="d-flex flex-wrap justify-content-end gap-2">
        <!-- Factory Dropdown -->
        <!-- Factory Dropdown - Updated for Two Columns -->
<div class="dropdown">
    <button class="btn btn-info dropdown-toggle" type="button" 
            id="factoryDropdown" data-bs-toggle="dropdown" 
            aria-expanded="false">
        <i class="fa fa-building me-1"></i> Select Factory
    </button>
    <ul class="dropdown-menu dropdown-menu-end" 
        aria-labelledby="factoryDropdown"
        style="min-width: 400px;">
        <li class="dropdown-header text-center mb-2">Fertilizer Factories</li>
        <div class="container-fluid">
            <div class="row">
                <!-- Column 1 -->
                <div class="col-6 px-1">
                    <li><a class="dropdown-item" href="dashboard.php?val=sfcl&user_type=<?php echo $user_type; ?>">
                        <i class="fa fa-industry me-2"></i> SFCL</a></li>
                    <li><a class="dropdown-item" href="dashboard.php?val=jfcl&user_type=<?php echo $user_type; ?>">
                        <i class="fa fa-industry me-2"></i> JFCL</a></li>
                    <li><a class="dropdown-item" href="dashboard.php?val=afccl&user_type=<?php echo $user_type; ?>">
                        <i class="fa fa-industry me-2"></i> AFCCL</a></li>
                    <li><a class="dropdown-item" href="dashboard.php?val=cufl&user_type=<?php echo $user_type; ?>">
                        <i class="fa fa-industry me-2"></i> CUFL</a></li>
                </div>
                <!-- Column 2 -->
                <div class="col-6 px-1">
                    <li><a class="dropdown-item" href="dashboard.php?val=gpfplc&user_type=<?php echo $user_type; ?>">
                        <i class="fa fa-industry me-2"></i> GPFPLC</a></li>
                    <li><a class="dropdown-item" href="dashboard.php?val=tspcl&user_type=<?php echo $user_type; ?>">
                        <i class="fa fa-industry me-2"></i> TSPCL</a></li>
                    <li><a class="dropdown-item" href="dashboard.php?val=dapfcl&user_type=<?php echo $user_type; ?>">
                        <i class="fa fa-industry me-2"></i> DAPFCL</a></li>
                </div>
            </div>
        </div>
        
        <li><hr class="dropdown-divider my-2"></li>
        
        <li class="dropdown-header text-center mb-2">Other Industries</li>
        <div class="container-fluid">
            <div class="row">
                <!-- Column 1 for other industries -->
                <div class="col-6 px-1">
                    <li><a class="dropdown-item" href="dashboard.php?val=bisf&user_type=<?php echo $user_type; ?>">
                        <i class="fa fa-industry me-2"></i> BISF</a></li>
                    <li><a class="dropdown-item" href="dashboard.php?val=cccl&user_type=<?php echo $user_type; ?>">
                        <i class="fa fa-industry me-2"></i> CCCL</a></li>
                </div>
                <!-- Column 2 for other industries -->
                <div class="col-6 px-1">
                    <li><a class="dropdown-item" href="dashboard.php?val=ugsf&user_type=<?php echo $user_type; ?>">
                        <i class="fa fa-industry me-2"></i> UGSF</a></li>
                    <li><a class="dropdown-item" href="dashboard.php?val=kpml&user_type=<?php echo $user_type; ?>">
                        <i class="fa fa-industry me-2"></i> KPML</a></li>
                </div>
            </div>
        </div>
        
        <!-- Optional: Add a footer with total count -->
        <li class="dropdown-footer text-center mt-2 py-1 border-top">
            <small class="text-muted">11 Factories Available</small>
        </li>
    </ul>
</div>

                                <!-- Action Buttons -->
                                <a class="btn btn-outline-primary" id="reload-btn" href="home.php">
                                    <i class="fa fa-refresh me-1"></i> Reload
                                </a>
                                
                                <?php if ($user_type == 'sadmin' || $user_type == 'admin'): ?>
                                <form id="downloadForm" action="dawnload_database.php" method="post" class="m-0">
                                    <button class="btn btn-warning" type="submit" name="submit">
                                        <i class="fa fa-download me-1"></i> Download DB
                                    </button>
                                </form>
                                <?php endif; ?>
                                
                                <?php if ($user_type == 'sadmin'): ?>
                                <a class="btn btn-info" href="set_name.php">
                                    <i class="fa fa-edit me-1"></i> Set Name
                                </a>
                                <?php endif; ?>
                                
                                <button type="button" class="btn btn-success" id="print_ind_tenants_aa">
                                    <i class="fa fa-print me-1"></i> Print Report
                                </button>
                                
                                <a class="btn btn-danger" href="logout.php" id="logout">
                                    <i class="fa fa-sign-out-alt me-1"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Date Display -->
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <div class="alert alert-info border-0 py-2 mb-0">
                                <h5 class="mb-1">
                                    <i class="fa fa-chart-line me-2"></i>
                                    Daily Production & Plant Status Report
                                </h5>
                                <?php if (isset($_POST['hit'])): ?>
                                <p class="mb-0">
                                    <i class="fa fa-calendar-check me-1"></i>
                                    Production as on: <strong><?php echo date('d-m-Y', strtotime($_POST['date'])); ?></strong>
                                    | Dated: <strong><?php echo date('d-m-Y'); ?></strong>
                                </p>
                                <?php else: ?>
                                <p class="mb-0">
                                    <i class="fa fa-calendar-check me-1"></i>
                                    Production as on: <strong><?php echo date('d-m-Y', strtotime('-1 day')); ?></strong>
                                    | Dated: <strong><?php echo date('d-m-Y'); ?></strong>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <?php
    include('../db/db.php');
    $display_date = isset($_POST['hit']) ? $_POST['date'] : $yesterday;
    $month_id = date('Y-m', strtotime($display_date));
    
    // Calculate basic stats
    $total_factories = 0;
    $total_daily = 0;
    $total_plants = 0;
    
    // Check for data in all tables
    $all_tables = ['gpfplc', 'sfcl', 'jfcl', 'cufl', 'afccl', 'tspcl', 'dapfcl', 'kpml', 'cccl', 'ugsf', 'bisf'];
    foreach ($all_tables as $table) {
        $check_sql = "SELECT COUNT(*) as count, SUM(daily) as total FROM $table WHERE date = '$display_date'";
        $check_result = mysqli_query($conn, $check_sql);
        if ($check_result) {
            $check_data = mysqli_fetch_assoc($check_result);
            if ($check_data['count'] > 0) {
                $total_factories++;
                $total_daily += $check_data['total'] ?? 0;
                $total_plants += $check_data['count'];
            }
        }
    }
    ?>
    
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card" style="border-left-color: #3498db;">
                <div class="stats-value"><?php echo $total_factories; ?></div>
                <div class="stats-label">
                    <i class="fa fa-industry me-2"></i>Active Factories
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card" style="border-left-color: #2ecc71;">
                <div class="stats-value"><?php echo $total_plants; ?></div>
                <div class="stats-label">
                    <i class="fa fa-cogs me-2"></i>Production Units
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card" style="border-left-color: #f39c12;">
                <div class="stats-value"><?php echo number_format($total_daily, 2); ?> MT</div>
                <div class="stats-label">
                    <i class="fa fa-chart-bar me-2"></i>Total Production
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card" style="border-left-color: #9b59b6;">
                <div class="stats-value">
                    <?php echo isset($_POST['hit']) ? date('d M Y', strtotime($_POST['date'])) : date('d M Y', strtotime('-1 day')); ?>
                </div>
                <div class="stats-label">
                    <i class="fa fa-calendar-day me-2"></i>Report Date
                </div>
            </div>
        </div>
    </div>

    <!-- Main Report Table -->
    <div class="row">
        <div class="col-12">
            <div class="gradient-card">
                <div class="card-header card-header-gradient">
                    <h5 class="mb-0 text-white">
                        <i class="fa fa-table me-2"></i>
                        Consolidated Production Report
                        <span class="badge bg-light text-dark ms-2">
                            <?php echo $total_factories; ?> Factories
                        </span>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <div id="printableArea_ind_tenants_aa">
                            <table class="table table-bordered table-striped table-hover text-center" 
                                   id="table_content">
                                <thead>
                                    <tr>
                                        <th width="3%">#</th>
                                        <th width="10%">Factory Name</th>
                                        <th width="8%">Product</th>
                                        <th width="6%">Unit</th>
                                        <th width="8%">Installed Capacity</th>
                                        <th width="7%">Daily</th>
                                        <th width="8%">Monthly</th>
                                        <th width="8%">Yearly</th>
                                        <th width="9%">Yearly Target</th>
                                        <th width="7%">Due</th>
                                        <th width="8%">Monthly Target</th>
                                        <th width="7%">Plant Load (%)</th>
                                        <th width="20%">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
                                include('../db/db.php');
                                $month11=date('m',strtotime($date));
                                $year11=date('Y',strtotime($date));

                                if($month11==7 || $month11==8 || $month11==9 || $month11==10 || $month11==11 || $month11==12 ){
                                  $year22=$year11;
                                }
                                else{
                                  $year22=$year11-1;
                                }
                                $yearrange12="$year22-07-01";
                                $year22=$year22+1;
                                $yearrange13="$year22-06-30";

                                $hasData = false;

                                if (isset($_POST['hit'])) {
                                    $date = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
                                    $_SESSION['date'] = $date;
                                    $month_id = date('Y-m', strtotime($date));

                                    $month11 = date('m', strtotime($date));
                                    $year11 = date('Y', strtotime($date));

                                    if ($month11 >= 7 && $month11 <= 12) {
                                        $year22 = $year11;
                                    } else {
                                        $year22 = $year11 - 1;
                                    }

                                    $yearrange12 = "$year22-07-01";
                                    $yearrange13 = ($year22 + 1) . "-06-30";

                                    $tables = ['gpfplc', 'sfcl', 'jfcl', 'cufl', 'afccl'];
                                    $i = 1;
                                    $total_installed_capacity = 0;
                                    $total_attain_capacity = 0;
                                    $total_daily = 0;
                                    $total_month_m = 0;
                                    $total_month_y = 0;
                                    $total_year_target = 0;
                                    $total_month_target = 0;
                                    $counttable=0;

                                    foreach ($tables as $table) {
                                        $sql_check = "SELECT * FROM $table WHERE date = '$date' ORDER BY factory_name";
                                        $result_check = mysqli_query($conn, $sql_check);      

                                        if (mysqli_num_rows($result_check) > 0) {
                                            $counttable++;
                                            $hasData = true;
                                        }
                                        
                                        $data = [];
                                        while ($row = mysqli_fetch_assoc($result_check)) {
                                            $data[$row['factory_name']][] = $row;
                                        }
                                        
                                        foreach ($data as $factory_name => $rows) {
                                            $rowspan = count($rows);
                                            $is_first_row = true;

                                            foreach ($rows as $row) {
                                                $daily = $row['daily'];
                                                $month_code = $row['month_code'];
                                                $year_code = $row['year_code'];
                                                $product_produce = $row['product_produce'];

                                                $sql_year = "SELECT * FROM target_table WHERE id = '$year_code'";
                                                $result_year = mysqli_query($conn, $sql_year);
                                                $row_year = mysqli_fetch_assoc($result_year);
                                                $year_target = $row_year['target'];

                                                $sql_month = "SELECT * FROM monthly_target WHERE id = '$month_code'";
                                                $result_month = mysqli_query($conn, $sql_month);
                                                $row_month = mysqli_fetch_assoc($result_month);
                                                $month_target = $row_month['target'];

                                                $sql_m = "SELECT * FROM $table WHERE date LIKE '$month_id%' AND date <= '$date' AND product_produce = '$product_produce'";
                                                $result_fetch_m = mysqli_query($conn, $sql_m);
                                                $month_m = 0;
                                                while ($row_m = mysqli_fetch_assoc($result_fetch_m)) {
                                                    $month_m += (float)$row_m['daily'];
                                                }

                                                $sql_y = "SELECT * FROM $table WHERE date BETWEEN '$yearrange12' AND '$yearrange13' AND date <= '$date' AND product_produce = '$product_produce'";
                                                $result_fetch_y = mysqli_query($conn, $sql_y);
                                                $month_y = 0;
                                                while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                                                    $month_y += (float)$row_y['daily'];
                                                }

                                                $total_installed_capacity += (int)$row['installed_capacity'];
                                                $total_attain_capacity += (int)$row['attain_capacity'];
                                                $total_daily += $daily;
                                                $total_month_m += $month_m;
                                                $total_month_y += $month_y;
                                                $total_year_target += $year_target;
                                                $total_month_target += $month_target;
                                                ?>
                                                <tr>
                                                    <?php if ($is_first_row): ?>
                                                    <td class="text-center align-middle fw-bold" rowspan="<?php echo $rowspan; ?>" style="background-color: rgba(13, 110, 253, 0.05);">
                                                        <?php echo $i++; ?>
                                                    </td>
                                                    <td class="text-uppercase align-middle fw-bold" rowspan="<?php echo $rowspan; ?>" style="background-color: rgba(13, 110, 253, 0.05);">
                                                        <i class="fas fa-factory me-1"></i><?php echo htmlspecialchars($factory_name, ENT_QUOTES, 'UTF-8'); ?>
                                                    </td>
                                                    <?php endif; ?>
                                                    <td class="text-uppercase">
                                                        <span class="badge bg-light text-dark"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-secondary"><?php echo ($row['product_produce'] != 'Sheet Glass') ? 'MT' : 'L.Sq.M'; ?></span>
                                                    </td>
                                                    <td class="text-center fw-bold"><?php echo number_format((int)$row['installed_capacity']); ?></td>
                                                    <td class="text-center fw-bold text-primary"><?php echo number_format($daily, 2); ?></td>
                                                    <td class="text-center fw-bold text-success"><?php echo number_format($month_m, 2); ?></td>
                                                    <td class="text-center fw-bold text-info"><?php echo number_format($month_y, 2); ?></td>
                                                    <td class="text-center fw-bold"><?php echo number_format($year_target); ?></td>
                                                    <td class="text-center fw-bold <?php echo ($year_target - $month_y) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                        <?php echo number_format($year_target - $month_y, 2); ?>
                                                    </td>
                                                    <td class="text-center fw-bold"><?php echo number_format($month_target); ?></td>
                                                    <td class="text-center">
                                                        <span class="status-badge <?php echo $row['plant_load'] > 80 ? 'badge-active' : ($row['plant_load'] > 50 ? 'badge-warning' : 'badge-inactive'); ?>">
                                                            <?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?>%
                                                        </span>
                                                    </td>
                                                    <td style="font-size: 0.85rem; text-align: left;">
                                                        <?php if(!empty($row['remarks'])): ?>
                                                        <i class="fas fa-comment text-muted me-1"></i><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?>
                                                        <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $is_first_row = false;
                                            }
                                        }
                                    }

                                    if ($counttable > 1) {
                                        echo '<tr class="table-active">';
                                        echo '<td colspan="2" class="text-center fw-bold"><i class="fas fa-calculator me-1"></i> TOTAL</td>';
                                        echo '<td class="text-center"><span class="badge bg-dark">' . htmlspecialchars($product_produce ?? '', ENT_QUOTES, 'UTF-8') . '</span></td>';
                                        echo '<td class="text-center fw-bold">' . (($row['product_produce'] ?? null) != 'Sheet Glass' ? 'MT' : 'L.Sq.M') . '</td>';
                                        echo '<td class="text-center fw-bold">' . number_format($total_installed_capacity) . '</td>';
                                        echo '<td class="text-center fw-bold text-primary">' . number_format($total_daily, 2) . '</td>';
                                        echo '<td class="text-center fw-bold text-success">' . number_format($total_month_m, 2) . '</td>';
                                        echo '<td class="text-center fw-bold text-info">' . number_format($total_month_y, 2) . '</td>';
                                        echo '<td class="text-center fw-bold">' . number_format($total_year_target) . '</td>';
                                        echo '<td class="text-center fw-bold ' . (($total_year_target - $total_month_y) >= 0 ? 'text-success' : 'text-danger') . '">' . number_format(($total_year_target - $total_month_y), 2) . '</td>';
                                        echo '<td class="text-center fw-bold">' . number_format($total_month_target) . '</td>';
                                        echo '<td class="text-center">-</td>';
                                        echo '<td class="text-center">-</td>';
                                        echo '</tr>';
                                    }
                                    
                                    // Non-urea factories
                                    $tables1 = ['tspcl','dapfcl','kpml','cccl','ugsf'];  
                                    foreach ($tables1 as $table1) {      
                                        $sql_check = "SELECT * FROM $table1 WHERE date = '$date' ORDER BY factory_name";
                                        $result_check = mysqli_query($conn, $sql_check);
                                    
                                        if (mysqli_num_rows($result_check) > 0) {
                                            $hasData = true;
                                        }

                                        $data1 = [];
                                        while ($row = mysqli_fetch_assoc($result_check)) {
                                            $data1[$row['factory_name']][] = $row;
                                        }
                                        
                                        foreach ($data1 as $factory_name => $rows) {
                                            $rowspan = count($rows); 
                                            $is_first_row = true; 
                                            foreach ($rows as $row) {
                                                $daily = $row['daily'];
                                                $month_code = $row['month_code'];
                                                $year_code = $row['year_code'];
                                                $product_produce = $row['product_produce'];

                                                $sql_year = "SELECT * FROM target_table WHERE id = '$year_code'";
                                                $result_year = mysqli_query($conn, $sql_year);
                                                $row_year = mysqli_fetch_assoc($result_year);
                                                $year_target = $row_year['target'];

                                                $sql_month = "SELECT * FROM monthly_target WHERE id = '$month_code'";
                                                $result_month = mysqli_query($conn, $sql_month);
                                                $row_month = mysqli_fetch_assoc($result_month);
                                                $month_target = $row_month['target'];

                                                $sql_m = "SELECT * FROM $table1 WHERE date LIKE '$month_id%' AND date <= '$date' AND product_produce = '$product_produce'";
                                                $result_fetch_m = mysqli_query($conn, $sql_m);
                                                $month_m = 0;
                                                while ($row_m = mysqli_fetch_assoc($result_fetch_m)) {
                                                    $month_m = round($month_m + (float)$row_m['daily'], 2);
                                                }
                                                
                                                $sql_y = "SELECT * FROM $table1 WHERE date BETWEEN '$yearrange12' AND '$yearrange13' AND date <= '$date' AND product_produce = '$product_produce'";
                                                $result_fetch_y = mysqli_query($conn, $sql_y);
                                                $month_y = 0;
                                                while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                                                    $month_y += round((float)$row_y['daily'], 2);
                                                }
                                                ?>
                                                <tr>
                                                    <?php if ($is_first_row): ?>
                                                    <td class="text-center align-middle fw-bold" rowspan="<?php echo $rowspan; ?>" style="background-color: rgba(25, 135, 84, 0.05);">
                                                        <?php echo $i++; ?>
                                                    </td>
                                                    <td class="text-uppercase align-middle fw-bold" rowspan="<?php echo $rowspan; ?>" style="background-color: rgba(25, 135, 84, 0.05);">
                                                        <i class="fas fa-industry me-1"></i><?php echo htmlspecialchars($factory_name, ENT_QUOTES, 'UTF-8'); ?>
                                                    </td>
                                                    <?php endif; ?>
                                                    <td class="text-uppercase">
                                                        <span class="badge bg-light text-dark"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-secondary"><?php echo ($row['product_produce'] != 'Sheet Glass') ? 'MT' : 'L.Sq.M'; ?></span>
                                                    </td>
                                                    <td class="text-center fw-bold"><?php echo number_format($row['installed_capacity']); ?></td>
                                                    <td class="text-center fw-bold text-primary"><?php echo number_format($daily, 2); ?></td>
                                                    <td class="text-center fw-bold text-success"><?php echo number_format($month_m, 2); ?></td>
                                                    <td class="text-center fw-bold text-info"><?php echo number_format($month_y, 2); ?></td>
                                                    <td class="text-center fw-bold"><?php echo number_format($year_target); ?></td>
                                                    <td class="text-center fw-bold <?php echo ($year_target - $month_y) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                        <?php echo number_format($year_target - $month_y, 2); ?>
                                                    </td>
                                                    <td class="text-center fw-bold"><?php echo number_format($month_target); ?></td>
                                                    <td class="text-center">
                                                        <span class="status-badge <?php echo $row['plant_load'] > 80 ? 'badge-active' : ($row['plant_load'] > 50 ? 'badge-warning' : 'badge-inactive'); ?>">
                                                            <?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?>%
                                                        </span>
                                                    </td>
                                                    <td style="font-size: 0.85rem; text-align: left;">
                                                        <?php if(!empty($row['remarks'])): ?>
                                                        <i class="fas fa-comment text-muted me-1"></i><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?>
                                                        <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $is_first_row = false; 
                                            }
                                        }
                                    }

                                    // BISF Factory
                                    $sql_check = "SELECT * FROM bisf WHERE date = '$date' ORDER BY factory_name";
                                    $result_check = mysqli_query($conn, $sql_check);

                                    if (mysqli_num_rows($result_check) > 0) {
                                        $hasData = true;
                                    }

                                    $data = [];
                                    while ($row = mysqli_fetch_assoc($result_check)) {
                                        $data[$row['factory_name']][] = $row;
                                    }
                                    
                                    foreach ($data as $factory_name => $rows) {
                                        $rowspan = count($rows);
                                        $is_first_row = true;

                                        foreach ($rows as $row) {
                                            $daily = $row['daily'];
                                            $month_code = $row['month_code'];
                                            $year_code = $row['year_code'];
                                            $product_produce = $row['product_produce'];

                                            if ($product_produce == "sanitary") {
                                                $row['installed_capacity'] = $row['sanitary_installed_capacity'];
                                            } elseif ($product_produce == "insulator") {
                                                $row['installed_capacity'] = $row['insulator_installed_capacity'];
                                            } elseif ($product_produce == "refractories") {
                                                $row['installed_capacity'] = $row['refractories_installed_capacity'];
                                            }

                                            $sql_year = "SELECT * FROM target_table WHERE id = '$year_code'";
                                            $result_year = mysqli_query($conn, $sql_year);
                                            $row_year = mysqli_fetch_assoc($result_year);
                                            $year_target = $row_year['target'];

                                            $sql_month = "SELECT * FROM monthly_target WHERE id = '$month_code'";
                                            $result_month = mysqli_query($conn, $sql_month);
                                            $row_month = mysqli_fetch_assoc($result_month);
                                            $month_target = $row_month['target'];

                                            $sql_m = "SELECT * FROM bisf WHERE date LIKE '$month_id%' AND date <= '$date' AND product_produce = '$product_produce'";
                                            $result_fetch_m = mysqli_query($conn, $sql_m);
                                            $month_m = 0;
                                            while ($row_m = mysqli_fetch_assoc($result_fetch_m)) {
                                                $month_m = round($month_m + (float)$row_m['daily'], 2);
                                            }

                                            $sql_y = "SELECT * FROM bisf WHERE date BETWEEN '$yearrange12' AND '$yearrange13' AND date <= '$date' AND product_produce = '$product_produce'";
                                            $result_fetch_y = mysqli_query($conn, $sql_y);
                                            $month_y = 0;
                                            while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                                                $month_y += round((float)$row_y['daily'], 2);
                                            }
                                            ?>
                                            <tr>
                                                <?php if ($is_first_row): ?>
                                                <td class="text-center align-middle fw-bold" rowspan="<?php echo $rowspan; ?>" style="background-color: rgba(255, 193, 7, 0.05);">
                                                    <?php echo $i++; ?>
                                                </td>
                                                <td class="text-uppercase align-middle fw-bold" rowspan="<?php echo $rowspan; ?>" style="background-color: rgba(255, 193, 7, 0.05);">
                                                    <i class="fas fa-industry me-1"></i><?php echo htmlspecialchars($factory_name, ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <?php endif; ?>
                                                <td class="text-uppercase">
                                                    <span class="badge bg-light text-dark"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary"><?php echo ($row['product_produce'] != 'Sheet Glass') ? 'MT' : 'L.Sq.M'; ?></span>
                                                </td>
                                                <td class="text-center fw-bold"><?php echo number_format((int)$row['installed_capacity']); ?></td>
                                                <td class="text-center fw-bold text-primary"><?php echo number_format($daily, 2); ?></td>
                                                <td class="text-center fw-bold text-success"><?php echo number_format($month_m, 2); ?></td>
                                                <td class="text-center fw-bold text-info"><?php echo number_format($month_y, 2); ?></td>
                                                <td class="text-center fw-bold"><?php echo number_format($year_target); ?></td>
                                                <td class="text-center fw-bold <?php echo ($year_target - $month_y) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                    <?php echo number_format($year_target - $month_y, 2); ?>
                                                </td>
                                                <td class="text-center fw-bold"><?php echo number_format($month_target); ?></td>
                                                <td class="text-center">
                                                    <span class="status-badge <?php echo $row['plant_load'] > 80 ? 'badge-active' : ($row['plant_load'] > 50 ? 'badge-warning' : 'badge-inactive'); ?>">
                                                        <?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?>%
                                                    </span>
                                                </td>
                                                <td style="font-size: 0.85rem; text-align: left;">
                                                    <?php if(!empty($row['remarks'])): ?>
                                                    <i class="fas fa-comment text-muted me-1"></i><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?>
                                                    <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $is_first_row = false;
                                        }
                                    }

                                } else {
                                    // Default view (yesterday's data)
                                    $date=$yesterday;
                                    $_SESSION['date'] = $date;
                                    $month_id = date('Y-m', strtotime($date));

                                    $month11 = date('m', strtotime($date));
                                    $year11 = date('Y', strtotime($date));

                                    if ($month11 >= 7 && $month11 <= 12) {
                                        $year22 = $year11;
                                    } else {
                                        $year22 = $year11 - 1;
                                    }

                                    $yearrange12 = "$year22-07-01";
                                    $yearrange13 = ($year22 + 1) . "-06-30";

                                    $tables = ['gpfplc', 'sfcl', 'jfcl', 'cufl', 'afccl'];
                                    $i = 1;
                                    $total_installed_capacity = 0;
                                    $total_attain_capacity = 0;
                                    $total_daily = 0;
                                    $total_month_m = 0;
                                    $total_month_y = 0;
                                    $total_year_target = 0;
                                    $total_month_target = 0;
                                    $counttable=0;
                                    
                                    foreach ($tables as $table) {
                                        $sql_check = "SELECT * FROM $table WHERE date = '$date' ORDER BY factory_name";
                                        $result_check = mysqli_query($conn, $sql_check);

                                        if (mysqli_num_rows($result_check) > 0) {
                                            $counttable++;
                                            $hasData = true;
                                        }

                                        $data = [];
                                        while ($row = mysqli_fetch_assoc($result_check)) {
                                            $data[$row['factory_name']][] = $row;
                                        }

                                        foreach ($data as $factory_name => $rows) {
                                            $rowspan = count($rows);
                                            $is_first_row = true;

                                            foreach ($rows as $row) {
                                                $daily = $row['daily'];
                                                $month_code = $row['month_code'];
                                                $year_code = $row['year_code'];
                                                $product_produce = $row['product_produce'];

                                                $sql_year = "SELECT * FROM target_table WHERE id = '$year_code'";
                                                $result_year = mysqli_query($conn, $sql_year);
                                                $row_year = mysqli_fetch_assoc($result_year);
                                                $year_target = $row_year['target'];

                                                $sql_month = "SELECT * FROM monthly_target WHERE id = '$month_code'";
                                                $result_month = mysqli_query($conn, $sql_month);
                                                $row_month = mysqli_fetch_assoc($result_month);
                                                $month_target = $row_month['target'];

                                                $sql_m = "SELECT * FROM $table WHERE date LIKE '$month_id%' AND date <= '$date' AND product_produce = '$product_produce'";
                                                $result_fetch_m = mysqli_query($conn, $sql_m);
                                                $month_m = 0;
                                                while ($row_m = mysqli_fetch_assoc($result_fetch_m)) {
                                                    $month_m += (float)$row_m['daily'];
                                                }

                                                $sql_y = "SELECT * FROM $table WHERE date BETWEEN '$yearrange12' AND '$yearrange13' AND date <= '$date' AND product_produce = '$product_produce'";
                                                $result_fetch_y = mysqli_query($conn, $sql_y);
                                                $month_y = 0;
                                                while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                                                    $month_y += (float)$row_y['daily'];
                                                }

                                                $total_installed_capacity += (int)$row['installed_capacity'];
                                                $total_attain_capacity += (int)$row['attain_capacity'];
                                                $total_daily += $daily;
                                                $total_month_m += $month_m;
                                                $total_month_y += $month_y;
                                                $total_year_target += $year_target;
                                                $total_month_target += $month_target;
                                                ?>
                                                <tr>
                                                    <?php if ($is_first_row): ?>
                                                        <td class="text-center align-middle fw-bold" rowspan="<?php echo $rowspan; ?>" style="background-color: rgba(13, 110, 253, 0.05);">
                                                            <?php echo $i++; ?>
                                                        </td>
                                                        <td class="text-uppercase align-middle fw-bold" rowspan="<?php echo $rowspan; ?>" style="background-color: rgba(13, 110, 253, 0.05);">
                                                            <i class="fas fa-factory me-1"></i><?php echo htmlspecialchars($factory_name, ENT_QUOTES, 'UTF-8'); ?>
                                                        </td>
                                                    <?php endif; ?>
                                                    <td class="text-uppercase">
                                                        <span class="badge bg-light text-dark"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-secondary"><?php echo ($row['product_produce'] != 'Sheet Glass') ? 'MT' : 'L.Sq.M'; ?></span>
                                                    </td>
                                                    <td class="text-center fw-bold"><?php echo number_format((int)$row['installed_capacity']); ?></td>
                                                    <td class="text-center fw-bold text-primary"><?php echo number_format($daily, 2); ?></td>
                                                    <td class="text-center fw-bold text-success"><?php echo number_format($month_m, 2); ?></td>
                                                    <td class="text-center fw-bold text-info"><?php echo number_format($month_y, 2); ?></td>
                                                    <td class="text-center fw-bold"><?php echo number_format($year_target); ?></td>
                                                    <td class="text-center fw-bold <?php echo ($year_target - $month_y) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                        <?php echo number_format($year_target - $month_y, 2); ?>
                                                    </td>
                                                    <td class="text-center fw-bold"><?php echo number_format($month_target); ?></td>
                                                    <td class="text-center">
                                                        <span class="status-badge <?php echo $row['plant_load'] > 80 ? 'badge-active' : ($row['plant_load'] > 50 ? 'badge-warning' : 'badge-inactive'); ?>">
                                                            <?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?>%
                                                        </span>
                                                    </td>
                                                    <td style="font-size: 0.85rem; text-align: left;">
                                                        <?php if(!empty($row['remarks'])): ?>
                                                        <i class="fas fa-comment text-muted me-1"></i><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?>
                                                        <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $is_first_row = false;
                                            }
                                        }
                                    }
                                    
                                    if ($counttable > 1) {
                                        echo '<tr class="table-active">';
                                        echo '<td colspan="2" class="text-center fw-bold"><i class="fas fa-calculator me-1"></i> TOTAL</td>';
                                        echo '<td class="text-center"><span class="badge bg-dark">' . htmlspecialchars($product_produce ?? '', ENT_QUOTES, 'UTF-8') . '</span></td>';
                                        echo '<td class="text-center fw-bold">' . (($row['product_produce'] ?? null) != 'Sheet Glass' ? 'MT' : 'L.Sq.M') . '</td>';
                                        echo '<td class="text-center fw-bold">' . number_format($total_installed_capacity) . '</td>';
                                        echo '<td class="text-center fw-bold text-primary">' . number_format($total_daily, 2) . '</td>';
                                        echo '<td class="text-center fw-bold text-success">' . number_format($total_month_m, 2) . '</td>';
                                        echo '<td class="text-center fw-bold text-info">' . number_format($total_month_y, 2) . '</td>';
                                        echo '<td class="text-center fw-bold">' . number_format($total_year_target) . '</td>';
                                        echo '<td class="text-center fw-bold ' . (($total_year_target - $total_month_y) >= 0 ? 'text-success' : 'text-danger') . '">' . number_format(($total_year_target - $total_month_y), 2) . '</td>';
                                        echo '<td class="text-center fw-bold">' . number_format($total_month_target) . '</td>';
                                        echo '<td class="text-center">-</td>';
                                        echo '<td class="text-center">-</td>';
                                        echo '</tr>';
                                    }

                                    $tables1 = ['tspcl','dapfcl','kpml','cccl','ugsf'];  
                                    foreach ($tables1 as $table1) {      
                                        $sql_check = "SELECT * FROM $table1 WHERE date = '$date' ORDER BY factory_name";
                                        $result_check = mysqli_query($conn, $sql_check);
                                        
                                        if (mysqli_num_rows($result_check) > 0) {
                                            $hasData = true;
                                        }

                                        $data1 = [];
                                        while ($row = mysqli_fetch_assoc($result_check)) {
                                            $data1[$row['factory_name']][] = $row;
                                        }
                                        
                                        foreach ($data1 as $factory_name => $rows) {
                                            $rowspan = count($rows); 
                                            $is_first_row = true; 

                                            foreach ($rows as $row) {
                                                $daily = $row['daily'];
                                                $month_code = $row['month_code'];
                                                $year_code = $row['year_code'];
                                                $product_produce = $row['product_produce'];

                                                $sql_year = "SELECT * FROM target_table WHERE id = '$year_code'";
                                                $result_year = mysqli_query($conn, $sql_year);
                                                $row_year = mysqli_fetch_assoc($result_year);
                                                $year_target = $row_year['target'];

                                                $sql_month = "SELECT * FROM monthly_target WHERE id = '$month_code'";
                                                $result_month = mysqli_query($conn, $sql_month);
                                                $row_month = mysqli_fetch_assoc($result_month);
                                                $month_target = $row_month['target'];

                                                $sql_m = "SELECT * FROM $table1 WHERE date LIKE '$month_id%' AND date <= '$date' AND product_produce = '$product_produce'";
                                                $result_fetch_m = mysqli_query($conn, $sql_m);
                                                $month_m = 0;
                                                while ($row_m = mysqli_fetch_assoc($result_fetch_m)) {
                                                    $month_m += (float)$row_m['daily'];
                                                }

                                                $sql_y = "SELECT * FROM $table1 WHERE date BETWEEN '$yearrange12' AND '$yearrange13' AND date <= '$date' AND product_produce = '$product_produce'";
                                                $result_fetch_y = mysqli_query($conn, $sql_y);
                                                $month_y = 0;
                                                while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                                                    $month_y += (float)$row_y['daily'];
                                                }
                                                ?>
                                                <tr>
                                                    <?php if ($is_first_row): ?>
                                                        <td class="text-center align-middle fw-bold" rowspan="<?php echo $rowspan; ?>" style="background-color: rgba(25, 135, 84, 0.05);">
                                                            <?php echo $i++; ?>
                                                        </td>
                                                        <td class="text-uppercase align-middle fw-bold" rowspan="<?php echo $rowspan; ?>" style="background-color: rgba(25, 135, 84, 0.05);">
                                                            <i class="fas fa-industry me-1"></i><?php echo htmlspecialchars($factory_name, ENT_QUOTES, 'UTF-8'); ?>
                                                        </td>
                                                    <?php endif; ?>
                                                    <td class="text-uppercase">
                                                        <span class="badge bg-light text-dark"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-secondary"><?php echo ($row['product_produce'] != 'Sheet Glass') ? 'MT' : 'L.Sq.M'; ?></span>
                                                    </td>
                                                    <td class="text-center fw-bold"><?php echo number_format((int)$row['installed_capacity']); ?></td>
                                                    <td class="text-center fw-bold text-primary"><?php echo number_format($daily, 2); ?></td>
                                                    <td class="text-center fw-bold text-success"><?php echo number_format($month_m, 2); ?></td>
                                                    <td class="text-center fw-bold text-info"><?php echo number_format($month_y, 2); ?></td>
                                                    <td class="text-center fw-bold"><?php echo number_format($year_target); ?></td>
                                                    <td class="text-center fw-bold <?php echo ($year_target - $month_y) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                        <?php echo number_format($year_target - $month_y, 2); ?>
                                                    </td>
                                                    <td class="text-center fw-bold"><?php echo number_format($month_target); ?></td>
                                                    <td class="text-center">
                                                        <span class="status-badge <?php echo $row['plant_load'] > 80 ? 'badge-active' : ($row['plant_load'] > 50 ? 'badge-warning' : 'badge-inactive'); ?>">
                                                            <?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?>%
                                                        </span>
                                                    </td>
                                                    <td style="font-size: 0.85rem; text-align: left;">
                                                        <?php if(!empty($row['remarks'])): ?>
                                                        <i class="fas fa-comment text-muted me-1"></i><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?>
                                                        <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                                $is_first_row = false; 
                                            }
                                        }
                                    }

                                    // BISF Factory
                                    $sql_check = "SELECT * FROM bisf WHERE date = '$date' ORDER BY factory_name";
                                    $result_check = mysqli_query($conn, $sql_check);

                                    if (mysqli_num_rows($result_check) > 0) {
                                        $hasData = true;
                                    }

                                    $data = [];
                                    while ($row = mysqli_fetch_assoc($result_check)) {
                                        $data[$row['factory_name']][] = $row;
                                    }
                                    
                                    foreach ($data as $factory_name => $rows) {
                                        $rowspan = count($rows);
                                        $is_first_row = true;

                                        foreach ($rows as $row) {
                                            $daily = $row['daily'];
                                            $month_code = $row['month_code'];
                                            $year_code = $row['year_code'];
                                            $product_produce = $row['product_produce'];

                                            if ($product_produce == "sanitary") {
                                                $row['installed_capacity'] = $row['sanitary_installed_capacity'];
                                            } elseif ($product_produce == "insulator") {
                                                $row['installed_capacity'] = $row['insulator_installed_capacity'];
                                            } elseif ($product_produce == "refractories") {
                                                $row['installed_capacity'] = $row['refractories_installed_capacity'];
                                            }

                                            $sql_year = "SELECT * FROM target_table WHERE id = '$year_code'";
                                            $result_year = mysqli_query($conn, $sql_year);
                                            $row_year = mysqli_fetch_assoc($result_year);
                                            $year_target = $row_year['target'];

                                            $sql_month = "SELECT * FROM monthly_target WHERE id = '$month_code'";
                                            $result_month = mysqli_query($conn, $sql_month);
                                            $row_month = mysqli_fetch_assoc($result_month);
                                            $month_target = $row_month['target'];

                                            $sql_m = "SELECT * FROM bisf WHERE date LIKE '$month_id%' AND date <= '$date' AND product_produce = '$product_produce'";
                                            $result_fetch_m = mysqli_query($conn, $sql_m);
                                            $month_m = 0;
                                            while ($row_m = mysqli_fetch_assoc($result_fetch_m)) {
                                                $month_m = round($month_m + (float)$row_m['daily'], 2);
                                            }

                                            $sql_y = "SELECT * FROM bisf WHERE date BETWEEN '$yearrange12' AND '$yearrange13' AND date <= '$date' AND product_produce = '$product_produce'";
                                            $result_fetch_y = mysqli_query($conn, $sql_y);
                                            $month_y = 0;
                                            while ($row_y = mysqli_fetch_assoc($result_fetch_y)) {
                                                $month_y = round($month_y + (float)$row_y['daily'], 2);
                                            }
                                            ?>
                                            <tr>
                                                <?php if ($is_first_row): ?>
                                                <td class="text-center align-middle fw-bold" rowspan="<?php echo $rowspan; ?>" style="background-color: rgba(255, 193, 7, 0.05);">
                                                    <?php echo $i++; ?>
                                                </td>
                                                <td class="text-uppercase align-middle fw-bold" rowspan="<?php echo $rowspan; ?>" style="background-color: rgba(255, 193, 7, 0.05);">
                                                    <i class="fas fa-industry me-1"></i><?php echo htmlspecialchars($factory_name, ENT_QUOTES, 'UTF-8'); ?>
                                                </td>
                                                <?php endif; ?>
                                                <td class="text-uppercase">
                                                    <span class="badge bg-light text-dark"><?php echo htmlspecialchars($row['product_produce'], ENT_QUOTES, 'UTF-8'); ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-secondary"><?php echo ($row['product_produce'] != 'Sheet Glass') ? 'MT' : 'L.Sq.M'; ?></span>
                                                </td>
                                                <td class="text-center fw-bold"><?php echo number_format((int)$row['installed_capacity']); ?></td>
                                                <td class="text-center fw-bold text-primary"><?php echo number_format($daily, 2); ?></td>
                                                <td class="text-center fw-bold text-success"><?php echo number_format($month_m, 2); ?></td>
                                                <td class="text-center fw-bold text-info"><?php echo number_format($month_y, 2); ?></td>
                                                <td class="text-center fw-bold"><?php echo number_format($year_target); ?></td>
                                                <td class="text-center fw-bold <?php echo ($year_target - $month_y) >= 0 ? 'text-success' : 'text-danger'; ?>">
                                                    <?php echo number_format($year_target - $month_y, 2); ?>
                                                </td>
                                                <td class="text-center fw-bold"><?php echo number_format($month_target); ?></td>
                                                <td class="text-center">
                                                    <span class="status-badge <?php echo $row['plant_load'] > 80 ? 'badge-active' : ($row['plant_load'] > 50 ? 'badge-warning' : 'badge-inactive'); ?>">
                                                        <?php echo htmlspecialchars($row['plant_load'], ENT_QUOTES, 'UTF-8'); ?>%
                                                    </span>
                                                </td>
                                                <td style="font-size: 0.85rem; text-align: left;">
                                                    <?php if(!empty($row['remarks'])): ?>
                                                    <i class="fas fa-comment text-muted me-1"></i><?php echo htmlspecialchars($row['remarks'], ENT_QUOTES, 'UTF-8'); ?>
                                                    <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $is_first_row = false;
                                        }
                                    }
                                }

                                if (!$hasData) {
                                    echo '<tr>';
                                    echo '<td colspan="13" class="text-center py-5 no-data">';
                                    echo '<i class="fas fa-database fa-3x text-muted mb-3"></i><br>';
                                    echo '<h5 class="text-muted mb-2"><strong>No Record Found</strong></h5>';
                                    echo '<p class="text-muted mb-0">No production data available for ';
                                    if (isset($_POST['hit'])) {
                                        echo date('d F Y', strtotime($_POST['date']));
                                    } else {
                                        echo date('d F Y', strtotime('-1 day'));
                                    }
                                    echo '</p>';
                                    echo '</td>';
                                    echo '</tr>';
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Information -->
                <div class="card-footer bg-light border-0 py-3">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="text-muted" style="font-size: 0.85rem;">
                                <b style="text-decoration: underline;">C.C TO (Not on the basis of seniority):</b><br>
                                1. Sr. Secretary, MoInd, GOD, Dhaka.<br>
                                2. Chairman (Grade-1), BCIC, Dhaka.<br>
                                3. Addl. Secretary, MoInd, GOD, Dhaka.<br>
                                4. PS to Honorable Advisor, MoInd, GOD, Dhaka.<br>
                                5. Director (), BCIC, Dhaka.<br>
                                6. Senior General Manager (Admin), BCIC, Dhaka.<br>
                                7. Head of Marketing/CA/Chief Auditors, BCIC, Dhaka.<br>
                                8. O/C.
                            </div>
                        </div>
                        <div class="col-md-5 text-end">
                            <div class="text-muted" style="font-size: 0.85rem;">
                                <strong>General Manager (Production)</strong><br>
                                Production Division, BCIC.<br>
                                Phone No: 02223388176<br>
                                Email: productionbcic@gmail.com<br>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12 text-center">
                            <small class="text-muted">
                                <i class="fa fa-info-circle me-1"></i>
                                Design & Developed By ICT Division, BCIC.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
  <!-- ONLY ONE Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Force dropdown to work
    const factoryDropdown = document.getElementById('factoryDropdown');
    if (factoryDropdown) {
        // Manually handle dropdown toggle
        factoryDropdown.addEventListener('click', function(e) {
            const dropdownMenu = this.nextElementSibling;
            if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                dropdownMenu.classList.toggle('show');
                dropdownMenu.style.position = 'absolute';
                dropdownMenu.style.zIndex = '999999';
                dropdownMenu.style.display = 'block';
            }
        });
        
        // Close dropdown when clicking elsewhere
        document.addEventListener('click', function(e) {
            if (!factoryDropdown.contains(e.target) && !factoryDropdown.nextElementSibling.contains(e.target)) {
                const dropdownMenu = factoryDropdown.nextElementSibling;
                if (dropdownMenu && dropdownMenu.classList.contains('dropdown-menu')) {
                    dropdownMenu.classList.remove('show');
                    dropdownMenu.style.display = 'none';
                }
            }
        });
    }
});
</script>
    
    <!-- Your existing print script -->
    <script type="text/javascript">
    document.getElementById('print_ind_tenants_aa').addEventListener('click', function () {
        var printContents = document.getElementById('printableArea_ind_tenants_aa').innerHTML;
        var title = `
        <div style="display: flex; align-items: center; justify-content: center; margin-bottom: 10px;">
            <img src="bcic_logo.png" alt="BCIC Logo" style="max-width: 60px; margin-right: 20px;">
            <div style="text-align: center;">
                <h5 class="text-uppercase m-0" style="margin-bottom: 5px;">Bangladesh Chemical Industries Corporation</h5>
                <p class="text-uppercase" style="margin-top: 0; margin-bottom: 0px;">Daily Production & Plant Status Report</p>
                <?php if (isset($_POST['hit'])) { ?>
                    <p class=" text-center m-0" style="margin-top: 0; margin-bottom: 0;">
                        Production as on: <?php echo date('d-m-Y', strtotime($_POST['date'])); ?>
                    </p>
                <?php } else { ?>
                    <p class=" text-center m-0" style="margin-top: 0; margin-bottom: 0;">
                        Production as on: <?php echo date('d-m-Y', strtotime('-1 day')); ?>
                    </p>
                <?php } ?>
            </div>
        </div>
        `;

        var originalContents = document.body.innerHTML;
        var imageElement = new Image();
        imageElement.src = "bcic_logo.png";
        imageElement.onload = function () {
            document.body.innerHTML = `
                <html>
                <head>
                    <title>Print Report</title>
                    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
                    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
                    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        @font-face {
                            font-family: 'Nikosh', Times, serif;
                            src: url(Nikosh.ttf);
                        }
                        * {
                            font-family: 'Open Sans', sans-serif;
                            font-family: 'Tiro Bangla', serif;
                            font-family: 'Nikosh', sans-serif;
                        }
                        .no-print, #edit_btn, #action_t, #action, #status, #status_t, #print_ind_tenants_aa, #print-btn, #footer_id {
                            display: none !important;
                            visibility: hidden !important;
                        }
                        @media print {
                            @page {
                                size: A4 landscape;
                                margin: 5mm 2mm;
                            }
                            html, body {
                                overflow: hidden;
                                margin: 0;
                                padding: 0;
                            }
                            body {
                                margin-top: 1mm;
                                padding-top: 0;
                            }
                            footer {
                                position: fixed;
                                bottom: 0;
                                left: 0;
                                width: 100%;
                                text-align: center;
                                font-size: 10px;
                                margin: 0;
                            }
                            footer::after {
                               content: "Design & Developed by ICT Division, BCIC." 
                            }                      
                        }
                    </style>
                </head>
                <body>                
                    ${title}
                    ${printContents} 
                    <footer></footer>
                </body>
                </html>
            `;
            window.print();
            document.body.innerHTML = originalContents;
            window.location.reload();
        };
    });
    
    // Auto-refresh toggle
    document.getElementById('autoRefresh').addEventListener('change', function() {
        if(this.checked) {
            // Start auto-refresh every 60 seconds
            setInterval(function() {
                window.location.reload();
            }, 60000);
            showNotification('Auto-refresh enabled (60s)', 'success');
        } else {
            showNotification('Auto-refresh disabled', 'warning');
        }
    });
    
    // Set default date to yesterday
    window.onload = function() {
        var dateInput = document.getElementById('date');
        if(dateInput && !dateInput.value) {
            var yesterday = new Date();
            yesterday.setDate(yesterday.getDate() - 1);
            var yyyy = yesterday.getFullYear();
            var mm = String(yesterday.getMonth() + 1).padStart(2, '0');
            var dd = String(yesterday.getDate()).padStart(2, '0');
            dateInput.value = yyyy + '-' + mm + '-' + dd;
        }
    };
    
    function showNotification(message, type) {
        // Create notification element
        var notification = document.createElement('div');
        notification.className = 'alert alert-' + type + ' alert-dismissible fade show position-fixed';
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            <strong>${type === 'success' ? 'Success!' : 'Notice!'}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(function() {
            notification.remove();
        }, 3000);
    }
    </script>
</body>
</html>

<?php
include('../include/footer.php');
?>