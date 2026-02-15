<?php
session_name('dfms');
session_start();
include('../include/header_index.php');

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

$table = $_SESSION['username'];

// Factory title mapping
$factories = [
    'sfcl'   => 'Shahjalal Fertilizer Company Ltd. (SFCL)',
    'jfcl'   => 'Jamuna Fertilizer Company Ltd. (JFCL)',
    'afccl'  => 'Ashuganj Fertilizer Company Ltd. (AFCCL)',
    'gpfplc' => 'Ghorashal Polash Fertilizer PLC (GPFPLC)',
    'cufl'   => 'Chittagong Urea Fertilizer Ltd. (CUFL)',
    'tspcl'  => 'TSP Complex Limited (TSPCL)',
    'dapfcl' => 'DAP Fertilizer Company Limited (DAPFCL)',
    'bisf'   => 'Bangladesh Insulator & Sanitaryware Factory Ltd.(BISFL)',
    'cccl'   => 'Chatak Cement Company Limited (CCCL)',
    'ugsf'   => 'Osmania Glass Sheet Factory Limited (UGSFL)',
    'kpml'   => 'Karnaphuli Paper Mills Limited (KPML)',
];

$table1 = $factories[$table] ?? '';

// Save posted dates into session
if (isset($_POST['hit'])) {
    $_SESSION['start_date'] = $_POST['start_date'];
    $_SESSION['end_date']   = $_POST['end_date'];
}

// Get row count for pagination
$row_count = 0;
if (isset($_POST['hit'])) {
    include('../db/db.php');
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $count_query = "SELECT COUNT(*) as total FROM $table WHERE date BETWEEN '$start_date' AND '$end_date'";
    $count_result = mysqli_query($conn, $count_query);
    if ($count_result) {
        $count_data = mysqli_fetch_assoc($count_result);
        $row_count = $count_data['total'];
    }
}
?>

<style>
/* Default hidden elements */
.print-only {
    display: none;
    text-align: center;
    margin-bottom: .2rem;
    padding: 10px;
    background: white;
    color: black;
    border-bottom: 2px solid #333;
}

/* Main styling */
.gradient-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.card-header-gradient {
    background: linear-gradient(135deg, #2ecc71 0%, #1abc9c 100%) !important;
    color: white !important;
    border-bottom: none;
}

/* Table styling for screen view */
.table-responsive {
    max-height: 600px;
    overflow-y: auto;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.table thead th {
    position: sticky;
    top: 0;
    z-index: 2;
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%) !important;
    color: white !important;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 0.5px;
    border: none !important;
    padding: 12px 8px !important;
    vertical-align: middle !important;
}

.table-bordered {
    border: 1px solid #dee2e6 !important;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #e9ecef !important;
    padding: 10px 8px !important;
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

/* Column styling with colored left borders - Screen only */
@media screen {
    td:nth-child(1), th:nth-child(1) { /* # column */
        border-left: 4px solid #95a5a6 !important;
        background-color: rgba(149, 165, 166, 0.05) !important;
    }
    
    td:nth-child(2), th:nth-child(2) { /* Factory column */
        border-left: 4px solid #3498db !important;
        background-color: rgba(52, 152, 219, 0.05) !important;
    }
    
    td:nth-child(3), th:nth-child(3) { /* Date column */
        border-left: 4px solid #9b59b6 !important;
        background-color: rgba(155, 89, 182, 0.05) !important;
    }
    
    td:nth-child(4), th:nth-child(4) { /* Product column */
        border-left: 4px solid #2ecc71 !important;
        background-color: rgba(46, 204, 113, 0.05) !important;
    }
    
    td:nth-child(5), th:nth-child(5) { /* Unit column */
        border-left: 4px solid #f1c40f !important;
        background-color: rgba(241, 196, 15, 0.05) !important;
    }
    
    td:nth-child(6), th:nth-child(6) { /* Daily MT column */
        border-left: 4px solid #e74c3c !important;
        background-color: rgba(231, 76, 60, 0.05) !important;
    }
    
    td:nth-child(7), th:nth-child(7) { /* Monthly MT column */
        border-left: 4px solid #e67e22 !important;
        background-color: rgba(230, 126, 34, 0.05) !important;
    }
    
    td:nth-child(8), th:nth-child(8) { /* Yearly MT column */
        border-left: 4px solid #d35400 !important;
        background-color: rgba(211, 84, 0, 0.05) !important;
    }
    
    td:nth-child(9), th:nth-child(9) { /* Production Target column */
        border-left: 4px solid #16a085 !important;
        background-color: rgba(22, 160, 133, 0.05) !important;
    }
    
    td:nth-child(10), th:nth-child(10) { /* Due (Production) column */
        border-left: 4px solid #c0392b !important;
        background-color: rgba(192, 57, 43, 0.05) !important;
    }
    
    td:nth-child(11), th:nth-child(11) { /* Monthly Target column */
        border-left: 4px solid #8e44ad !important;
        background-color: rgba(142, 68, 173, 0.05) !important;
    }
    
    td:nth-child(12), th:nth-child(12) { /* Due (Monthly) column */
        border-left: 4px solid #f39c12 !important;
        background-color: rgba(243, 156, 18, 0.05) !important;
    }
    
    td:nth-child(13), th:nth-child(13) { /* Plant Load column */
        border-left: 4px solid #27ae60 !important;
        background-color: rgba(39, 174, 96, 0.05) !important;
    }
    
    td:nth-child(14), th:nth-child(14) { /* Remarks column */
        border-left: 4px solid #2980b9 !important;
        background-color: rgba(41, 128, 185, 0.05) !important;
    }
}

/* Value indicators */
.plant-load-high { color: #27ae60 !important; font-weight: 700; }
.plant-load-medium { color: #f39c12 !important; font-weight: 700; }
.plant-load-low { color: #e74c3c !important; font-weight: 700; }

.target-achieved { color: #27ae60 !important; font-weight: 700; }
.target-warning { color: #f39c12 !important; font-weight: 700; }
.target-failed { color: #e74c3c !important; font-weight: 700; }

/* Product badges */
.product-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border: 1px solid;
}

.product-urea { background-color: #e8f6f3 !important; color: #16a085 !important; border-color: #16a085; }
.product-dap { background-color: #fef9e7 !important; color: #f39c12 !important; border-color: #f39c12; }
.product-npk { background-color: #f4ecf7 !important; color: #8e44ad !important; border-color: #8e44ad; }
.product-sanitary { background-color: #eaf2f8 !important; color: #3498db !important; border-color: #3498db; }
.product-insulator { background-color: #f9ebea !important; color: #c0392b !important; border-color: #c0392b; }
.product-refractories { background-color: #e8f8f5 !important; color: #1abc9c !important; border-color: #1abc9c; }

/* Progress bars in cells */
.cell-progress {
    height: 6px;
    margin-top: 5px;
    border-radius: 3px;
    overflow: hidden;
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
    background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    border-radius: 4px;
}

/* Date picker styling */
.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
}

/* Button styling */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-danger {
    background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
    border: none;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #c0392b 0%, #e74c3c 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
}

/* Keep date column in single line */
td.date-nowrap, th.date-nowrap { 
    white-space: nowrap !important; 
    min-width: 100px;
}

/* Print styles - FIXED for single page printing */
@media print {
    body { 
        margin: 0 !important; 
        padding: 0 !important; 
        background: white !important;
        font-size: 10pt !important;
        width: 100% !important;
    }
    
    @page {
        size: A4 portrait;
        margin: 0.5cm;
    }

    .print-only {
        display: block !important;
        page-break-before: always;
        page-break-after: avoid;
        margin-top: 0;
        margin-bottom: 10px;
        padding: 5px;
        background: white !important;
        color: black !important;
        border-bottom: 2px solid #000 !important;
    }
    
    .print-header {
        text-align: center;
        margin-bottom: 15px;
    }
    
    .print-header h4 {
        margin: 5px 0;
        font-size: 14pt;
    }
    
    .print-header p {
        margin: 2px 0;
        font-size: 10pt;
    }

    /* Hide all screen elements */
    #search-btn, #login-btn, #print-btn, #logout, 
    .gradient-bg, .btn-group, .form-control, 
    .card-header-gradient, .summary-card, 
    .factory-badge, .card-footer, .table-responsive {
        display: none !important;
    }

    /* Show only print content */
    #print-section {
        display: block !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    #cardborder, .card {
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        page-break-inside: avoid;
    }
    
    /* Table styling for print */
    #table_content { 
        font-size: 8pt !important; 
        width: 100% !important;
        border-collapse: collapse !important;
        page-break-inside: auto;
    }
    
    #table_content thead {
        display: table-header-group !important;
    }
    
    #table_content tbody {
        display: table-row-group !important;
    }
    
    #table_content tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
    
    .table thead th {
        background: #f8f9fa !important;
        color: black !important;
        border: 1px solid #000 !important;
        font-size: 8pt !important;
        padding: 3px 2px !important;
    }
    
    .table td, .table th {
        border: 1px solid #000 !important;
        padding: 3px 2px !important;
        font-size: 8pt !important;
    }
    
    /* Remove colors for print */
    td:nth-child(n), th:nth-child(n) {
        border-left: 1px solid #000 !important;
        background-color: white !important;
        color: black !important;
    }
    
    /* Hide visual elements for print */
    .cell-progress, .product-badge, .table-hover, .table-striped {
        display: none !important;
    }
    
    /* Adjust column widths for print */
    #table_content th:nth-child(1) { width: 3% !important; }
    #table_content th:nth-child(2) { width: 8% !important; }
    #table_content th:nth-child(3) { width: 8% !important; }
    #table_content th:nth-child(4) { width: 8% !important; }
    #table_content th:nth-child(5) { width: 5% !important; }
    #table_content th:nth-child(6) { width: 7% !important; }
    #table_content th:nth-child(7) { width: 7% !important; }
    #table_content th:nth-child(8) { width: 7% !important; }
    #table_content th:nth-child(9) { width: 9% !important; }
    #table_content th:nth-child(10) { width: 6% !important; }
    #table_content th:nth-child(11) { width: 8% !important; }
    #table_content th:nth-child(12) { width: 6% !important; }
    #table_content th:nth-child(13) { width: 7% !important; }
    #table_content th:nth-child(14) { width: 12% !important; }
    
    /* Force single page */
    .single-page {
        height: auto !important;
        max-height: none !important;
        overflow: visible !important;
    }
    
    /* Page break control */
    .page-break {
        page-break-before: always;
    }
    
    /* Limit rows per page */
    .table-row-limit {
        max-height: 24cm !important;
        overflow: hidden !important;
    }
}

/* Summary cards */
.summary-card {
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    color: white;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.bg-total { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }
.bg-average { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); }
.bg-max { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); }
.bg-min { background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%); }

/* Date range display */
.date-range-display {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-left: 4px solid #3498db;
    padding: 10px 15px;
    border-radius: 5px;
    margin-bottom: 15px;
}

/* Ensure text visibility */
.table td {
    color: #495057 !important;
    font-weight: 500;
}

.table th {
    color: white !important;
}

/* Factory name badge */
.factory-badge {
    background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
    color: white;
    padding: 5px 15px;
    border-radius: 20px;
    font-weight: 600;
    display: inline-block;
    margin-bottom: 10px;
}

/* Print preview warning */
.print-warning {
    display: none;
    background: #fff3cd;
    border: 1px solid #ffeaa7;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 15px;
    text-align: center;
}

@media print {
    .print-warning {
        display: none !important;
    }
}
</style>

<div class="container-fluid">
    <!-- Header Section -->
    <div class="row gradient-bg my-3 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-1 text-white">
                <i class="fa fa-chart-line me-2"></i>
                Daily Production & Plant Status Report
            </h4>
            <p class="mb-0 text-white-75">
                <i class="fa fa-industry me-1"></i>
                <span class="factory-badge"><?php echo $table1; ?></span>
            </p>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-flex align-items-center justify-content-end">
                <i class="fa fa-calendar-alt fa-2x me-3 text-white-50"></i>
                <div class="text-end">
                    <h6 class="mb-0 text-white">Report Dashboard</h6>
                    <small class="text-white-75"><?php echo date('F d, Y'); ?></small>
                </div>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="row my-3 align-items-center">  
        <div class="col-12 col-md-8 mb-3 mb-md-0">
            <form class="row g-2 align-items-center" action="" method="post">
                <div class="col-12 col-sm-5 col-md-4">
                    <label class="form-label text-muted mb-1"><i class="fa fa-calendar-start me-1"></i> Start Date</label>
                    <input type="date" class="form-control shadow-sm" name="start_date" id="start_date" required
                           value="<?php echo $_SESSION['start_date'] ?? ''; ?>">
                </div>
                <div class="col-12 col-sm-5 col-md-4">
                    <label class="form-label text-muted mb-1"><i class="fa fa-calendar-end me-1"></i> End Date</label>
                    <input type="date" class="form-control shadow-sm" name="end_date" id="end_date" required
                           value="<?php echo $_SESSION['end_date'] ?? ''; ?>">
                </div>
                <div class="col-12 col-sm-2 col-md-4">
                    <label class="form-label invisible mb-1">Search</label>
                    <button type="submit" class="btn btn-primary w-100 shadow-sm" id="search-btn" name="hit">
                        <i class="fa fa-search me-1"></i> Generate Report
                    </button>
                </div>
            </form>     
        </div>

        <!-- Action Buttons -->
        <div class="col-12 col-md-4 text-md-end text-center">
            <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-2">
                <a class="btn btn-outline-primary" id="login-btn" href="dashboard.php">
                    <i class="fa fa-arrow-left me-1"></i> Dashboard
                </a> 
                <button onclick="printReport()" class="btn btn-outline-danger" id="print-btn">
                    <i class="fa fa-print me-1"></i> Print
                </button>
                <a class="btn btn-danger" href="logout.php" id="logout">
                    <i class="fa fa-sign-out-alt me-1"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <?php error_reporting(E_ALL ^ E_NOTICE); ?>

    <?php if(isset($_POST['hit'])): ?>
    <!-- Print Preview Warning -->
    <div class="print-warning" id="printWarning">
        <i class="fa fa-info-circle text-warning me-2"></i>
        <strong>Print Preview:</strong> Report will print on multiple pages if data exceeds one page.
        <br><small>Showing all <?php echo $row_count; ?> records in browser view.</small>
    </div>

    <!-- Summary Cards (Only show when report is generated) -->
    <div class="row mb-3">
        <div class="col-md-3 col-6">
            <div class="summary-card bg-total">
                <h6 class="mb-2"><i class="fa fa-calendar-alt me-1"></i> Date Range</h6>
                <h5 class="mb-0">
                    <?php echo date('d M', strtotime($_POST['start_date'])); ?> - 
                    <?php echo date('d M, Y', strtotime($_POST['end_date'])); ?>
                </h5>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="summary-card bg-average">
                <h6 class="mb-2"><i class="fa fa-industry me-1"></i> Factory</h6>
                <h5 class="mb-0"><?php echo strtoupper($table); ?></h5>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="summary-card bg-max">
                <h6 class="mb-2"><i class="fa fa-chart-bar me-1"></i> Total Records</h6>
                <h5 class="mb-0"><?php echo $row_count; ?> Days</h5>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="summary-card bg-min">
                <h6 class="mb-2"><i class="fa fa-user me-1"></i> User Type</h6>
                <h5 class="mb-0 text-uppercase"><?php echo $_SESSION['user_type']; ?></h5>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Report Card -->
    <div class="row">
        <div class="col-12">
            <!-- Print Section (Hidden for screen, shown for print) -->
            <div id="print-section" style="display: none;">
                <?php if(isset($_POST['hit'])): ?>
                <div class="print-only">
                    <div class="print-header">
                        <h4><?php echo $table1; ?></h4>
                        <p class="mb-1">
                            <strong>Date Range:</strong> 
                            <?php echo htmlspecialchars($_POST['start_date']); ?> 
                            to <?php echo htmlspecialchars($_POST['end_date']); ?>
                        </p>
                        <p class="mb-0">
                            <strong>Report Generated:</strong> <?php echo date('F d, Y h:i A'); ?>
                        </p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Screen View Card -->
            <div class="card shadow border-0" id="cardborder">
                <div class="card-header card-header-gradient">
                    <h5 class="card-title mb-0 text-white">
                        <i class="fa fa-table me-2"></i>
                        Production Report Details
                        <?php if(isset($_POST['hit'])): ?>
                        <span class="badge bg-warning ms-2">
                            <?php echo htmlspecialchars($_POST['start_date']); ?> to <?php echo htmlspecialchars($_POST['end_date']); ?>
                        </span>
                        <?php endif; ?>
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover text-center" id="table_content">
                            <thead>
                                <tr>
                                    <th width="2%">#</th>
                                    <th width="5%"><i class="fa fa-industry me-1"></i> Factory</th>
                                    <th width="7%" class="date-nowrap"><i class="fa fa-calendar me-1"></i> Date</th>                                         
                                    <th width="5%"><i class="fa fa-cube me-1"></i> Product</th>    
                                    <th width="4%"><i class="fa fa-cogs me-1"></i> Unit</th>       
                                    <th width="6%"><i class="fa fa-chart-line me-1"></i> Daily (MT)</th>
                                    <th width="6%"><i class="fa fa-chart-bar me-1"></i> Monthly (MT)</th>
                                    <th width="6%"><i class="fa fa-chart-area me-1"></i> Yearly (MT)</th>
                                    <th width="8%"><i class="fa fa-bullseye me-1"></i> Production Target (MT)</th>
                                    <th width="6%"><i class="fa fa-balance-scale me-1"></i> Due (MT)</th>
                                    <th width="6%"><i class="fa fa-bullseye me-1"></i> Monthly Target (MT)</th>
                                    <th width="6%"><i class="fa fa-balance-scale me-1"></i> Due (MT)</th>
                                    <th width="5%"><i class="fa fa-tachometer-alt me-1"></i> Plant Load (%)</th>                             
                                    <th width="30%"><i class="fa fa-comment me-1"></i> Remarks</th>
                                </tr>
                            </thead>
                            <tbody> 
                                <?php include('report_query.php'); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fa fa-info-circle me-1 text-primary"></i>
                                Showing production report for <strong><?php echo $table1; ?></strong>
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="fa fa-database me-1 text-success"></i>
                                Records: <?php echo $row_count; ?> | 
                                <i class="fa fa-clock ms-2 me-1 text-warning"></i>
                                <?php echo date('h:i A'); ?>
                            </small>
                        </div>
                    </div>
                </div>
            </div>  
        </div> 
    </div>
</div>

<script>
// Custom print function to handle page breaks
function printReport() {
    // Show print warning
    document.getElementById('printWarning').style.display = 'block';
    
    // Clone the table for printing
    var table = document.getElementById('table_content');
    var printWindow = window.open('', '_blank');
    
    // Prepare print content
    var printContent = `
        <html>
        <head>
            <title>Production Report - <?php echo $table1; ?></title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    font-size: 10pt; 
                    margin: 0; 
                    padding: 0.5cm; 
                }
                @page { 
                    size: A4 landscape; 
                    margin: 0.5cm; 
                }
                .print-header { 
                    text-align: center; 
                    margin-bottom: 15px; 
                    border-bottom: 2px solid #000; 
                    padding-bottom: 10px; 
                }
                .print-header h4 { 
                    margin: 5px 0; 
                    font-size: 14pt; 
                }
                .print-header p { 
                    margin: 2px 0; 
                    font-size: 10pt; 
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    font-size: 8pt; 
                    margin-top: 10px; 
                }
                th, td { 
                    border: 1px solid #000; 
                    padding: 3px 2px; 
                    text-align: center; 
                }
                th { 
                    background-color: #f2f2f2; 
                    font-weight: bold; 
                }
                tr { 
                    page-break-inside: avoid; 
                }
                .page-break { 
                    page-break-before: always; 
                }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h4><?php echo $table1; ?></h4>
                <p><strong>Date Range:</strong> 
                <?php if(isset($_POST['hit'])): ?>
                    <?php echo htmlspecialchars($_POST['start_date']); ?> to <?php echo htmlspecialchars($_POST['end_date']); ?>
                <?php endif; ?>
                </p>
                <p><strong>Report Generated:</strong> <?php echo date('F d, Y h:i A'); ?></p>
            </div>
    `;
    
    // Add table content
    printContent += table.outerHTML;
    printContent += '</body></html>';
    
    printWindow.document.write(printContent);
    printWindow.document.close();
    printWindow.focus();
    
    // Print after a short delay
    setTimeout(function() {
        printWindow.print();
        printWindow.close();
    }, 250);
    
    // Hide print warning after printing
    setTimeout(function() {
        document.getElementById('printWarning').style.display = 'none';
    }, 1000);
}

// Function to split table for printing
function splitTableForPrint(rowsPerPage) {
    var table = document.getElementById('table_content');
    var tbody = table.getElementsByTagName('tbody')[0];
    var rows = tbody.getElementsByTagName('tr');
    var totalRows = rows.length;
    
    if (totalRows <= rowsPerPage) {
        return; // No need to split
    }
    
    var pages = Math.ceil(totalRows / rowsPerPage);
    
    for (var i = 0; i < pages; i++) {
        var start = i * rowsPerPage;
        var end = Math.min(start + rowsPerPage, totalRows);
        
        // Create a new table for this page
        var newTable = table.cloneNode(true);
        var newTbody = newTable.getElementsByTagName('tbody')[0];
        
        // Remove all rows
        while (newTbody.firstChild) {
            newTbody.removeChild(newTbody.firstChild);
        }
        
        // Add rows for this page
        for (var j = start; j < end; j++) {
            newTbody.appendChild(rows[j].cloneNode(true));
        }
        
        // Add page break if not first page
        if (i > 0) {
            newTable.classList.add('page-break');
        }
    }
}
</script>

<?php include('../include/footer.php'); ?>