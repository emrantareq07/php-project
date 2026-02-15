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

    $sql_update = "UPDATE target_table SET target = '$target' WHERE id = '$id'";
    if (mysqli_query($conn, $sql_update)) {
        header("Location: yearly_target_set.php?updated=successfully");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Include header
include('../include/header.php');

// Insert new record
if (isset($_POST['insert'])) {
    $target = $_POST['target'];
    $product_produce = $_POST['product_produce'];
    $fiscalstart = $_POST['fiscalstart'];
    $fiscalend = $_POST['fiscalend'];

    $sql_check = "SELECT * FROM target_table 
                  WHERE factory_name = '$table' 
                  AND fiscalstart = '$fiscalstart' 
                  AND product_produce = '$product_produce'";
    $result_check = mysqli_query($conn, $sql_check);

if (mysqli_num_rows($result_check) == 0) {
    $sql_insert = "INSERT INTO target_table (factory_name, product_produce, fiscalstart, fiscalend, target) 
                   VALUES ('$table', '$product_produce', '$fiscalstart', '$fiscalend', '$target')";
    if (mysqli_query($conn, $sql_insert)) {
        header("Location: yearly_target_set.php?inserted=successfully");
        exit();
    }
} else {
       header("Location: yearly_target_set.php?existed=successfully");
        exit();    
        }
    }

// Fetch all records for display
$sql_fetch = "SELECT * FROM target_table WHERE factory_name = '$table'";
$result_fetch = mysqli_query($conn, $sql_fetch);
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
    background: linear-gradient(135deg, #2ecc71 0%, #1abc9c 100%) !important;
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
    padding: 12px 24px !important;
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

.btn-warning {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%) !important;
    color: white !important;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e67e22 0%, #f39c12 100%) !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 6px 20px rgba(243, 156, 18, 0.3) !important;
}

.btn-outline-primary {
    border: 2px solid #667eea !important;
    color: #667eea !important;
    background: transparent !important;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
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

.badge-active {
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
    color: white;
}

.badge-pending {
    background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
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
    background: linear-gradient(135deg, #2ecc71 0%, #1abc9c 100%);
    border-radius: 2px;
    margin-top: 5px;
    transition: width 0.3s ease;
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
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    border-left: 4px solid #3498db;
}

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
    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
    transition: width 1s ease;
}
</style>

<div class="container-fluid py-1">
    <!-- Header Section -->
    <div class="row">
        <div class="col-12">
            <div class="gradient-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-3 text-white">
                            <i class="fas fa-bullseye me-3 animated-icon"></i>
                            Yearly Target Management
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
                            <h6 class="text-white mb-2">Fiscal Year: <?php echo date('Y', strtotime($yearrange12)); ?>-<?php echo date('y', strtotime($yearrange13)); ?></h6>
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
    <?php
    $total_targets = mysqli_num_rows($result_fetch);
    mysqli_data_seek($result_fetch, 0); // Reset pointer for future use
    ?>
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
                    <?php echo date('M d, Y', strtotime($yearrange12)); ?>
                </div>
                <div class="metric-label">
                    <i class="fa fa-calendar-start me-2"></i>Fiscal Start
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="metric-card">
                <div class="metric-value">
                    <?php echo date('M d, Y', strtotime($yearrange13)); ?>
                </div>
                <div class="metric-label">
                    <i class="fa fa-calendar-end me-2"></i>Fiscal End
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="metric-card">
                <div class="metric-value">
                    <?php 
                    $current_year = date('Y-m-d') >= $yearrange12 && date('Y-m-d') <= $yearrange13 ? 'Active' : 'Planning';
                    echo $current_year;
                    ?>
                </div>
                <div class="metric-label">
                    <i class="fa fa-chart-line me-2"></i>Status
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
                            <p class="mb-0">Yearly target has been updated successfully.</p>
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
                            <p class="mb-0">New yearly target has been set successfully.</p>
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
                            <p class="mb-0">Target for this product and fiscal year already exists.</p>
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
                        <i class="fa fa-plus-circle me-2"></i>
                        Set/Update Yearly Target
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
                                <label for="target" class="form-label">
                                    <i class="fa fa-bullseye"></i> Production Target (MT)
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Enter target in metric tons" 
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
                                <span id="submitText">Set Target</span>
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
                        Current Yearly Targets
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
                                    <th><i class="fa fa-calendar-start me-2"></i>Fiscal Start</th>
                                    <th><i class="fa fa-calendar-end me-2"></i>Fiscal End</th>
                                    <th class="text-center"><i class="fa fa-bullseye me-2"></i>Target (MT)</th>
                                    <?php if ($user_type == 'admin'): ?>
                                    <th class="text-center"><i class="fa fa-cogs me-2"></i>Actions</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                mysqli_data_seek($result_fetch, 0); // Reset pointer again
                                while ($row = mysqli_fetch_assoc($result_fetch)): 
                                ?>
                                <tr>
                                    <td class="text-center fw-bold"><?= $row['id']; ?></td>
                                    <td>
                                        <span class="status-badge badge-active">
                                            <?= $row['product_produce']; ?>
                                        </span>
                                    </td>
                                    <td><?= date('M d, Y', strtotime($row['fiscalstart'])); ?></td>
                                    <td><?= date('M d, Y', strtotime($row['fiscalend'])); ?></td>
                                    <td class="text-center fw-bold text-primary">
                                        <div class="d-flex flex-column align-items-center">
                                            <span><?= number_format($row['target']); ?> MT</span>
                                            <div class="target-progress">
                                                <div class="target-progress-bar" style="width: <?php echo min(100, ($row['target']/10000)*100); ?>%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <?php if ($user_type == 'admin'): ?>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-success btn-sm editBtn" 
                                            data-id="<?= $row['id']; ?>" 
                                            data-product="<?= $row['product_produce']; ?>" 
                                            data-target="<?= $row['target']; ?>"
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
                                Showing yearly targets for <strong><?php echo strtoupper($table); ?></strong>
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
        const fiscalstart = this.dataset.fiscalstart;
        const fiscalend = this.dataset.fiscalend;

        // Populate form fields
        document.getElementById('recordId').value = id;
        document.getElementById('product_produce').value = product;
        document.getElementById('target').value = target;
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
    submitBtn.innerHTML = '<i class="fa fa-save me-2"></i> Set Target';
    submitBtn.setAttribute("name", "insert");
    submitText.textContent = "Set Target";
    
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
    
    // Validate numeric input
    if (!/^\d+$/.test(targetValue)) {
        e.preventDefault();
        targetInput.classList.add('is-invalid');
        showToast('Please enter a valid numeric target value.', 'error');
        return false;
    }
    
    // Clear any previous error states
    targetInput.classList.remove('is-invalid');
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
</script>

<?php include('../include/footer.php'); ?>