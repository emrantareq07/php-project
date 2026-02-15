<?php
session_name('dfms');
session_start();
ob_start(); // Start output buffering
error_reporting(0);
include('../db/db.php');

// Set timezone to Dhaka, Bangladesh
date_default_timezone_set('Asia/Dhaka');

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
  exit();
}

if(isset($_GET['val'])){
$table = $_GET['val']; 
$user_type = $_GET['user_type'];
$_SESSION['username']=$table ;
$_SESSION['user_type']=$user_type;
}
else {
$table = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
}

if ($user_type == 'sadmin') {
  include('../include/topbar.php'); 
  include('../include/header_sadmin.php');  
} else {
  include('../include/header.php');
  include('../include/topbar_user.php');
}
?>

<style>
/* Main styling */
.gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

/* Form card styling */
.form-card {
    border: none !important;
    border-radius: 15px !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%) !important;
    overflow: hidden;
}

.form-card .card-header {
    background: linear-gradient(135deg, #2ecc71 0%, #1abc9c 100%) !important;
    color: white !important;
    border-bottom: none !important;
    padding: 20px !important;
    border-radius: 15px 15px 0 0 !important;
}

/* Form input styling */
.form-control {
    border: 2px solid #e9ecef !important;
    border-radius: 10px !important;
    padding: 12px 15px !important;
    transition: all 0.3s ease !important;
    font-weight: 500 !important;
}

.form-control:focus {
    border-color: #667eea !important;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25) !important;
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
    color: #667eea !important;
}

/* Button styling */
.btn {
    border-radius: 10px !important;
    padding: 12px 20px !important;
    font-weight: 600 !important;
    transition: all 0.3s ease !important;
    border: none !important;
    display: flex !important;
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

.btn-warning {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    color: white !important;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e67e22 0%, #f39c12 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(243, 156, 18, 0.3) !important;
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

.btn-info {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    color: white !important;
}

.btn-info:hover {
    background: linear-gradient(135deg, #2980b9 0%, #3498db 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3) !important;
}

.btn-secondary {
    background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%) !important;
    color: white !important;
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #7f8c8d 0%, #95a5a6 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(149, 165, 166, 0.3) !important;
}

/* Alert styling */
.alert {
    border-radius: 10px !important;
    border: none !important;
    padding: 15px 20px !important;
    font-weight: 500 !important;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
}

.alert-danger {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%) !important;
    color: white !important;
}

.alert-success {
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%) !important;
    color: white !important;
}

/* Sidebar buttons styling */
.sidebar-btn {
    border-radius: 10px !important;
    padding: 15px !important;
    margin-bottom: 15px !important;
    text-align: left !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
    border-left: 4px solid transparent !important;
}

.sidebar-btn:hover {
    transform: translateX(5px) !important;
    box-shadow: 0 6px 15px rgba(0,0,0,0.15) !important;
}

.sidebar-btn i {
    width: 25px !important;
    text-align: center !important;
    margin-right: 10px !important;
    font-size: 1.1em !important;
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

/* Select styling */
select.form-control {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23667eea' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
    background-repeat: no-repeat !important;
    background-position: right 0.75rem center !important;
    background-size: 16px 12px !important;
    padding-right: 2.5rem !important;
    appearance: none !important;
}

/* Textarea styling */
textarea.form-control {
    min-height: 100px !important;
    resize: vertical !important;
}

/* Progress bar for inputs */
.input-progress {
    height: 4px !important;
    border-radius: 2px !important;
    margin-top: 5px !important;
    overflow: hidden !important;
    background-color: #e9ecef !important;
}

.input-progress-bar {
    height: 100% !important;
    background: linear-gradient(135deg, #2ecc71 0%, #1abc9c 100%) !important;
    transition: width 0.3s ease !important;
}

/* Loading animation */
.loader {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-left: 10px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .gradient-bg {
        padding: 15px !important;
        margin-bottom: 15px !important;
    }
    
    .form-card {
        margin-bottom: 20px !important;
    }
    
    .sidebar-btn {
        padding: 12px !important;
        margin-bottom: 10px !important;
    }
}

/* Input error state */
.input-error {
    border-color: #e74c3c !important;
    background-color: rgba(231, 76, 60, 0.05) !important;
}

/* Success state */
.input-success {
    border-color: #2ecc71 !important;
    background-color: rgba(46, 204, 113, 0.05) !important;
}

/* User type indicator */
.user-type-badge {
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    color: white !important;
    padding: 4px 12px !important;
    border-radius: 15px !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
}
</style>

<div class="container mt-4">
    <!-- Header Section -->
    <div class="row gradient-bg mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-1 text-white">
                <i class="fa fa-edit me-2"></i>
                Daily Production Data Entry
            </h4>
            <p class="mb-0 text-white-75">
                <i class="fa fa-industry me-1"></i>
                <span class="factory-badge"><?php echo strtoupper($table); ?></span>
                <span class="user-type-badge ms-2"><?php echo $user_type; ?></span>
            </p>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-flex align-items-center justify-content-end">
                <i class="fa fa-calendar-alt fa-2x me-3 text-white-50"></i>
                <div class="text-end">
                    <h6 class="mb-0 text-white">Data Entry Panel</h6>
                    <small class="text-white-75"><?php echo date('F d, Y (l)'); ?></small>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column Alerts -->
        <div class="col-lg-3 col-md-4 col-12">
            <?php
            if(isset($_GET['past_dated']) && $_GET['past_dated'] === "Back date error") {
                $_SESSION['back_date_error'] = true;
                header("Location: dashboard.php");
                exit();
            }
            if(isset($_GET['advance_dated']) && $_GET['advance_dated'] === "Advanced date error") {
                $_SESSION['advance_date_error'] = true;
                header("Location: dashboard.php");
                exit();
            }
            if(isset($_GET['already_exists']) && $_GET['already_exists'] === "already_exists") {
                $_SESSION['already_exists'] = true;
                header("Location: dashboard.php");
                exit();
            }
            if(isset($_GET['submitted']) && $_GET['submitted'] === "successfully") {
                $_SESSION['submitted'] = true;
                header("Location: dashboard.php");
                exit();
            }

            if(isset($_SESSION['advance_date_error'])) {
                echo '<div class="alert alert-danger shadow">
                        <i class="fa fa-exclamation-triangle me-2"></i>
                        <strong>Warning!</strong> Selected date is in the future!
                      </div>';
                unset($_SESSION['advance_date_error']);
            } elseif(isset($_SESSION['back_date_error'])) {
                echo '<div class="alert alert-danger shadow">
                        <i class="fa fa-exclamation-circle me-2"></i>
                        <strong>Error!</strong> Back date entry is not allowed!
                      </div>';
                unset($_SESSION['back_date_error']);
            } elseif(isset($_SESSION['already_exists'])) {
                echo '<div class="alert alert-danger shadow">
                        <i class="fa fa-times-circle me-2"></i>
                        <strong>Duplicate!</strong> Data for this date already exists!
                      </div>';
                unset($_SESSION['already_exists']);
            } elseif(isset($_SESSION['submitted'])) {
                echo '<div class="alert alert-success shadow">
                        <i class="fa fa-check-circle me-2"></i>
                        <strong>Success!</strong> Data inserted successfully!
                      </div>';
                unset($_SESSION['submitted']);
            } else {
                echo '<div class="alert alert-info shadow">
                        <i class="fa fa-info-circle me-2"></i>
                        <strong>Info:</strong> Enter daily production data
                      </div>';
            }
            ?>
            
            <!-- Quick Stats Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body text-center">
                    <h6 class="card-subtitle mb-2 text-muted">
                        <i class="fa fa-chart-line me-1"></i> Today's Stats
                    </h6>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-2">
                                <small class="text-muted">Factory</small>
                                <h5 class="mb-0 text-primary"><?php echo strtoupper($table); ?></h5>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-2">
                                <small class="text-muted">User Type</small>
                                <h5 class="mb-0 text-warning"><?php echo ucfirst($user_type); ?></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Center Column Form -->
        <div class="col-lg-6 col-md-8 col-12">
            <div class="card form-card">
                <div class="card-header">
                    <h5 class="mb-0 text-white">
                        <i class="fa fa-database me-2"></i>
                        Production Data Entry Form
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="insert_urea.php" method="POST" id="productionForm">
                        <div class="form-group mb-4">
                            <label for="date" class="form-label">
                                <i class="fa fa-calendar-alt"></i> Select Date
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa fa-calendar text-primary"></i>
                                </span>
                                <input type="date" class="form-control" name="date" id="date" 
                                    onChange="checkAvailability()" required 
                                    value="<?php echo date('Y-m-d', strtotime('-1 day'));?>"
                                    style="border-left: none !important;">
                            </div>
                            <div class="mt-2">
                                <span id="user-availability-status"></span>
                                <div id="loaderIcon" style="display:none" class="loader"></div>
                            </div>
                            <div class="input-progress">
                                <div id="date-progress" class="input-progress-bar" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="product_produce" class="form-label">
                                <i class="fa fa-cube"></i> Select Product
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fa fa-box text-success"></i>
                                </span>
                                <select class="form-control" name="product_produce" id="product_produce" 
                                        style="border-left: none !important;">
                                    <?php
                                    if ($table == "sfcl" || $table == "jfcl" || $table == "afccl" || $table == "gpfplc" || $table == "cufl") {
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
                            </div>
                            <div class="input-progress">
                                <div id="product-progress" class="input-progress-bar" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="daily" class="form-label">
                                        <i class="fa fa-chart-bar"></i> Daily Production (MT)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fa fa-weight text-warning"></i>
                                        </span>
                                        <input type="text" class="form-control" name="daily" id="daily"
                                               style="border-left: none !important;"
                                               placeholder="Enter metric tons">
                                        <span class="input-group-text">MT</span>
                                    </div>
                                    <div class="input-progress">
                                        <div id="daily-progress" class="input-progress-bar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="plant_load" class="form-label">
                                        <i class="fa fa-tachometer-alt"></i> Plant Load (%)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fa fa-industry text-danger"></i>
                                        </span>
                                        <input type="text" class="form-control" name="plant_load" id="plant_load"
                                               style="border-left: none !important;"
                                               placeholder="Enter percentage">
                                        <span class="input-group-text">%</span>
                                    </div>
                                    <div class="input-progress">
                                        <div id="load-progress" class="input-progress-bar" style="width: 0%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="remarks" class="form-label">
                                <i class="fa fa-comment"></i> Remarks / Notes
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 align-items-start" style="padding-top: 12px;">
                                    <i class="fa fa-sticky-note text-info"></i>
                                </span>
                                <textarea class="form-control" rows="4" name="remarks" id="remarks"
                                          style="border-left: none !important;"
                                          placeholder="Add any remarks or notes here..."></textarea>
                            </div>
                            <div class="input-progress">
                                <div id="remarks-progress" class="input-progress-bar" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" name="submit_form" class="btn btn-primary btn-lg">
                                <i class="fa fa-save me-2"></i> Save Production Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column Buttons -->
        <div class="col-lg-3 col-12">
            <div class="d-flex flex-column">
                <?php if($user_type=='sadmin'){ ?>
                    <a href="view_urea_report.php?username=<?=urlencode($_SESSION['username'])?>&user_type=<?=urlencode($_SESSION['user_type'])?>" 
                       class="btn btn-warning sidebar-btn">
                        <i class="fa fa-edit me-2"></i> Edit Records
                        <small class="d-block text-white-75 mt-1">Modify existing production data</small>
                    </a>
                    <a href="manage_user.php?username=<?=urlencode($_SESSION['username'])?>&user_type=<?=urlencode($_SESSION['user_type'])?>" 
                       class="btn btn-warning sidebar-btn">
                        <i class="fa fa-users me-2"></i> Manage Users
                        <small class="d-block text-white-75 mt-1">User management panel</small>
                    </a>
                    <a href="set_name.php" class="btn btn-info sidebar-btn">
                        <i class="fa fa-check-circle me-2"></i> Set Head Name
                        <small class="d-block text-white-75 mt-1">Configure department heads</small>
                    </a>
                <?php } elseif ($user_type=='admin') { ?>
                    <a href="home.php?username=<?=$_SESSION['username']?>&user_type=<?=$_SESSION['user_type']?>" 
                       class="btn btn-primary sidebar-btn">
                        <i class="fa fa-arrow-left me-2"></i> Dashboard
                        <small class="d-block text-white-75 mt-1">Return to main dashboard</small>
                    </a>
                    <a href="view_urea_report.php?username=<?=$_SESSION['username']?>&user_type=<?=$_SESSION['user_type']?>" 
                       class="btn btn-warning sidebar-btn">
                        <i class="fa fa-edit me-2"></i> Edit Records
                        <small class="d-block text-white-75 mt-1">Modify existing entries</small>
                    </a>
                <?php } else { ?>
                    <a href="view_urea_report.php?username=<?=$_SESSION['username']?>&user_type=<?=$_SESSION['user_type']?>" 
                       class="btn btn-warning sidebar-btn">
                        <i class="fa fa-eye me-2"></i> View Records
                        <small class="d-block text-white-75 mt-1">Browse production history</small>
                    </a>
                <?php } ?>

                <a href="urea_report_with_date_range.php?username=<?=$_SESSION['username']?>&user_type=<?=$_SESSION['user_type']?>" 
                   class="btn btn-danger sidebar-btn">
                    <i class="fa fa-print me-2"></i> Print Report
                    <!-- <small class="d-block text-white-75 mt-1">Generate printable reports</small> -->
                </a>
                <a href="yearly_target_set.php?username=<?=$_SESSION['username']?>&user_type=<?=$_SESSION['user_type']?>" 
                   class="btn btn-primary sidebar-btn">
                    <i class="fa fa-calendar-alt me-2"></i> Yearly Target
                    <small class="d-block text-white-75 mt-1">Set annual production goals</small>
                </a>
                <a href="monthly_target.php?username=<?=$_SESSION['username']?>&user_type=<?=$_SESSION['user_type']?>" 
                   class="btn btn-primary sidebar-btn">
                    <i class="fa fa-calendar me-2"></i> Monthly Target
                    <small class="d-block text-white-75 mt-1">Configure monthly targets</small>
                </a>
                <a href="logout.php" class="btn btn-danger sidebar-btn">
                    <i class="fa fa-sign-out-alt me-2"></i> Logout
                    <!-- <small class="d-block text-white-75 mt-1">Exit the system</small> -->
                </a>
                <button onclick="cleanReload()" class="btn btn-secondary sidebar-btn">
                    <i class="fas fa-sync-alt me-2"></i> Refresh Page
                    <!-- <small class="d-block text-white-75 mt-1">Clear and reload form</small> -->
                </button>
            </div>
            
            <!-- Quick Help Card -->
          <!--   <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="card-title mb-3">
                        <i class="fa fa-question-circle text-primary me-2"></i>
                        Quick Tips
                    </h6>
                    <ul class="list-unstyled mb-0 small">
                        <li class="mb-2">
                            <i class="fa fa-check-circle text-success me-2"></i>
                            Default date is yesterday
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-check-circle text-success me-2"></i>
                            Date availability checked automatically
                        </li>
                        <li class="mb-2">
                            <i class="fa fa-check-circle text-success me-2"></i>
                            Only numeric values for production
                        </li>
                        <li>
                            <i class="fa fa-check-circle text-success me-2"></i>
                            Save button submits all data
                        </li>
                    </ul>
                </div>
            </div> -->
        </div>
    </div>
</div>

<script>
function cleanReload() {
    const url = new URL(window.location.href);
    ['username', 'user_type'].forEach(param => url.searchParams.delete(param));
    window.location.replace(url.toString());
}

// Add input focus effects
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.form-control');
    
    inputs.forEach(input => {
        // Focus effect
        input.addEventListener('focus', function() {
            this.parentElement.parentElement.classList.add('focused');
            const progressBar = this.parentElement.parentElement.querySelector('.input-progress-bar');
            if (progressBar) {
                progressBar.style.width = '100%';
            }
        });
        
        // Blur effect
        input.addEventListener('blur', function() {
            this.parentElement.parentElement.classList.remove('focused');
            const progressBar = this.parentElement.parentElement.querySelector('.input-progress-bar');
            if (progressBar && this.value) {
                progressBar.style.width = '100%';
            } else if (progressBar) {
                progressBar.style.width = '0%';
            }
        });
        
        // Check initial value
        if (input.value) {
            const progressBar = input.parentElement.parentElement.querySelector('.input-progress-bar');
            if (progressBar) {
                progressBar.style.width = '100%';
            }
        }
    });
});
</script>

<script type="text/javascript">     
function checkAvailability() {
    var table1 = "<?php echo $table; ?>";
    $("#loaderIcon").show();
    $.ajax({
        url: "check_avail.php",
        data: { 
            date: $("#date").val(),
            table1: table1
        },
        type: "POST",
        success: function(data) {
            $("#user-availability-status").html(data);
            $("#loaderIcon").hide();
            
            // Add visual feedback
            if (data.includes("Available")) {
                $("#date").removeClass("input-error").addClass("input-success");
            } else if (data.includes("already exists")) {
                $("#date").removeClass("input-success").addClass("input-error");
            }
        }
    });
}
</script>

<script>    
(function($) {
  $.fn.inputFilter = function(callback, errMsg) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function(e) {
      if (callback(this.value)) {
        if (["keydown","mousedown","focusout"].indexOf(e.type) >= 0){
          $(this).removeClass("input-error");
          this.setCustomValidity("");
        }
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        $(this).addClass("input-error");
        this.setCustomValidity(errMsg);
        this.reportValidity();
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  };
}(jQuery));

$("#daily").inputFilter(function(value) {
  return /^-?\d*$/.test(value); }, "Must be an integer");
</script>   

<?php include('../include/footbar_user.php'); ob_end_flush(); ?>