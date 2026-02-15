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
// include('../include/header_index.php');
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCIC Production Report</title>
    
    <!-- ONLY ONE Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- ONLY ONE Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Clean CSS without conflicting overrides -->
<style>
    :root {
        --bcic-primary: #0d6efd;
        --bcic-secondary: #6c757d;
        --bcic-success: #198754;
        --bcic-danger: #dc3545;
        --bcic-warning: #ffc107;
        --bcic-info: #0dcaf0;
        --bcic-light: #f8f9fa;
        --bcic-dark: #212529;
        --bcic-blue: #1e40af;
        --bcic-green: #166534;
    }
    
    body {
        background-color: #f5f7fb;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        min-height: 100vh;
        overflow-x: hidden; /* Prevent horizontal scroll */
    }
    
    .bcic-header {
        background: linear-gradient(135deg, var(--bcic-primary) 0%, #1e40af 100%);
        color: white;
        padding: 1rem 0;
        margin-bottom: 2rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        position: relative;
        z-index: 10;
    }
    
    .bcic-card {
        background: white;
        border-radius: 12px;
        border: none;
        box-shadow: 0 6px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease;
        margin-bottom: 1.5rem;
    }
    
    .bcic-card:hover {
        transform: translateY(-5px);
    }
    
    .bcic-card-header {
        background-color: var(--bcic-primary);
        color: white;
        border-radius: 12px 12px 0 0 !important;
        padding: 1.2rem 1.5rem;
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .btn-bcic {
        border-radius: 8px;
        font-weight: 500;
        padding: 0.5rem 1.2rem;
        transition: all 0.3s ease;
        border: none;
    }
    
    .btn-bcic-primary {
        background-color: var(--bcic-primary);
        color: white;
    }
    
    .btn-bcic-primary:hover {
        background-color: #0b5ed7;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
    }
    
    .btn-bcic-success {
        background-color: var(--bcic-success);
        color: white;
    }
    
    .btn-bcic-danger {
        background-color: var(--bcic-danger);
        color: white;
    }
    
    .btn-bcic-warning {
        background-color: var(--bcic-warning);
        color: var(--bcic-dark);
    }
    
    .btn-bcic-info {
        background-color: var(--bcic-info);
        color: var(--bcic-dark);
    }
    
    .table-bcic {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .table-bcic thead {
        background-color: var(--bcic-primary);
        color: white;
    }
    
    .table-bcic th {
        font-weight: 600;
        padding: 1rem 0.75rem;
        border: none;
    }
    
    .table-bcic tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .table-bcic tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    .table-bcic td {
        padding: 0.75rem;
        border-color: #e9ecef;
        vertical-align: middle;
    }
    
    .dropdown-item {
        padding: 0.5rem 1rem;
        transition: background-color 0.2s ease;
    }
    
    .dropdown-item:hover {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    .date-input {
        border-radius: 8px;
        border: 2px solid #dee2e6;
        padding: 0.5rem 1rem;
        transition: border-color 0.3s ease;
    }
    
    .date-input:focus {
        border-color: var(--bcic-primary);
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        outline: none;
    }
    
    .print-area {
        background: white;
        padding: 2rem;
        border-radius: 12px;
    }
    
    .action-buttons .btn {
        margin: 0 3px;
    }
    
    .no-data {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 3rem 1rem;
    }
    
    .no-data i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }
    
    .footer-notes {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1.5rem;
        border-left: 4px solid var(--bcic-primary);
    }
    
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .badge-active {
        background-color: rgba(25, 135, 84, 0.1);
        color: var(--bcic-success);
    }
    
    .badge-inactive {
        background-color: rgba(220, 53, 69, 0.1);
        color: var(--bcic-danger);
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        
        .print-area {
            padding: 0;
            box-shadow: none;
        }
    }
    
    .page-title {
        color: var(--bcic-blue);
        font-weight: 700;
        position: relative;
        padding-bottom: 0.5rem;
    }
    
    .page-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background-color: var(--bcic-primary);
        border-radius: 2px;
    }
    
    .welcome-text {
        color: var(--bcic-secondary);
        font-size: 0.9rem;
    }
    
    /* ========== FIXED DROPDOWN STYLES ========== */
    /* Remove ALL conflicting !important declarations first */
    
    /* Fix for factory dropdown container */
    .col-lg-4.col-md-6,
    .card.border-0.bg-light,
    .card-body {
        overflow: visible !important;
        position: relative;
        z-index: auto;
    }
    
    /* ========== DROPDOWN FIXES ========== */
.dropdown {
    position: relative;
}

.dropdown-menu {
    z-index: 1060 !important;
    max-height: 400px;
    overflow-y: auto;
}

/* Fix for any parent container clipping */
.container, .container-fluid, .row, .col-*, .card, .card-body {
    position: static !important;
    overflow: visible !important;
}

/* Remove any conflicting transforms */
.dropdown-menu.show {
    position: absolute !important;
    transform: none !important;
    display: block !important;
}

/* Specific factory dropdown fix */
#factoryDropdown + .dropdown-menu {
    z-index: 9999 !important;
    position: absolute !important;
    inset: 0px auto auto 0px !important;
    transform: translate3d(0px, 40px, 0px) !important;
}

/* Print styles */
@media print {
    .dropdown, .dropdown-toggle, .dropdown-menu, .no-print {
        display: none !important;
    }
}
    
    /* Fix for two-column dropdown */
    .dropdown-menu[style*="min-width: 400px"],
    .dropdown-menu.wide-dropdown {
        min-width: 400px !important;
        max-width: 400px !important;
        padding: 15px !important;
    }
    
    /* Two column layout inside dropdown */
    .dropdown-menu .dropdown-columns {
        display: flex;
        gap: 20px;
        padding: 10px 0;
    }
    
    .dropdown-menu .dropdown-column {
        flex: 1;
        min-width: 0;
    }
    
    /* Ensure dropdown items are visible */
    .dropdown-item {
        display: block;
        width: 100%;
        padding: 0.5rem 1rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        text-decoration: none;
        white-space: nowrap;
        background-color: transparent;
        border: 0;
    }
    
    /* Fix for any clipping issues */
    .container, 
    .container-fluid, 
    .row, 
    .col-*, 
    .card, 
    .card-body {
        overflow: visible !important;
    }
    
    /* Force the main container to allow overflow */
    .container {
        position: relative;
        overflow: visible !important;
    }
    
    /* Fix for Bootstrap 5 dropdown positioning */
    .dropdown-menu-end {
        right: 0;
        left: auto;
    }
    
    /* Fix for dropdown in cards */
    .card {
        position: relative;
        overflow: visible !important;
    }
    
    /* ========== SPECIAL OVERRIDE FOR FACTORY DROPDOWN ========== */
    /* If nothing else works, use this forced approach */
    #factoryDropdown + .dropdown-menu {
        z-index: 999999 !important;
        position: fixed !important;
        top: auto !important;
        left: auto !important;
        right: auto !important;
        bottom: auto !important;
        transform: none !important;
    }
    
    /* Alternative: Use modal if dropdown still doesn't work */
    .factory-dropdown-fallback {
        cursor: pointer;
    }
    
    /* Print styles - hide dropdowns */
    @media print {
        .dropdown, 
        .dropdown-toggle, 
        .dropdown-menu,
        .no-print {
            display: none !important;
        }
    }
    
    /* ========== UTILITY CLASSES ========== */
    .z-index-1000 {
        z-index: 1000 !important;
    }
    
    .z-index-9999 {
        z-index: 9999 !important;
    }
    
    .z-index-99999 {
        z-index: 99999 !important;
    }
    
    .overflow-visible {
        overflow: visible !important;
    }
    
    .position-static {
        position: static !important;
    }
</style>
</head>
<body>
    <!-- Header Section -->
    <div class="bcic-header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title mb-2 text-white">Bangladesh Chemical Industries Corporation</h1>
                    <h5 class="text-white mb-0">Daily Production & Plant Status Report System</h5>
                    <p class="welcome-text text-white-50 mb-0 mt-2">
                        <i class="fas fa-user-circle me-1"></i> Welcome, <?php echo htmlspecialchars($username); ?> 
                        <span class="ms-3"><i class="fas fa-user-tag me-1"></i> <?php echo ucfirst($user_type); ?></span>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="d-flex justify-content-end align-items-center">
                        <span class="me-3 text-white">
                            <i class="fas fa-calendar-alt me-1"></i> <?php echo date('d F Y'); ?>
                        </span>
                        <div class="dropdown">
                            <button class="btn btn-bcic btn-bcic-info dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog me-1"></i> Settings
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user-circle me-2"></i> My Profile</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-key me-2"></i> Change Password</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="container">
            <!-- Control Panel Card -->
            <div class="bcic-card no-print">
                <div class="bcic-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-sliders-h me-2"></i> Control Panel
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-light text-dark me-3">
                            <i class="fas fa-database me-1"></i> Live Data
                        </span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="autoRefresh">
                            <label class="form-check-label text-white" for="autoRefresh">Auto Refresh</label>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Date Selection -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3">
                                        <i class="fas fa-calendar-day me-2"></i> Select Report Date
                                    </h6>
                                    <form action="" method="post" class="row g-2">
                                        <div class="col-8">
                                            <input type="date" class="form-control date-input" name="date" id="date" 
                                                   value="<?php echo isset($_POST['date']) ? $_POST['date'] : $yesterday; ?>" required>
                                        </div>
                                        <div class="col-4">
                                            <button type="submit" class="btn btn-bcic btn-bcic-primary w-100" name="hit">
                                                <i class="fas fa-search me-1"></i> Search
                                            </button>
                                        </div>
                                    </form>
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i> Default shows yesterday's production data
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

<!-- Factory Selection - FIXED WORKING VERSION -->
<div class="col-lg-4 col-md-6">
    <div class="card border-0 bg-light h-100">
        <div class="card-body p-3">
            <h6 class="card-title text-primary mb-3">
                <i class="fas fa-industry me-2"></i> Factory Selection
            </h6>
            <div class="dropdown">
                <button class="btn btn-bcic btn-bcic-primary w-100 dropdown-toggle" type="button" 
                        id="factoryDropdown" data-bs-toggle="dropdown" 
                        data-bs-auto-close="outside"
                        aria-expanded="false">
                    <i class="fas fa-list me-2"></i> Select Factory
                </button>
                <ul class="dropdown-menu p-3 shadow-lg" aria-labelledby="factoryDropdown" 
                    style="min-width: 400px; max-width: 400px;">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="dropdown-header text-primary mb-2">Fertilizer Factories</h6>
                            <li><a class="dropdown-item" href="dashboard.php?val=sfcl&user_type=<?php echo $user_type; ?>">
                                <i class="fas fa-factory me-2"></i> SFCL
                            </a></li>
                            <li><a class="dropdown-item" href="dashboard.php?val=jfcl&user_type=<?php echo $user_type; ?>">
                                <i class="fas fa-factory me-2"></i> JFCL
                            </a></li>
                            <li><a class="dropdown-item" href="dashboard.php?val=afccl&user_type=<?php echo $user_type; ?>">
                                <i class="fas fa-factory me-2"></i> AFCCL
                            </a></li>
                            <li><a class="dropdown-item" href="dashboard.php?val=cufl&user_type=<?php echo $user_type; ?>">
                                <i class="fas fa-factory me-2"></i> CUFL
                            </a></li>
                            <li><a class="dropdown-item" href="dashboard.php?val=gpfplc&user_type=<?php echo $user_type; ?>">
                                <i class="fas fa-factory me-2"></i> GPFPLC
                            </a></li>
                        </div>
                        <div class="col-6">
                            <h6 class="dropdown-header text-success mb-2">Other Industries</h6>
                            <li><a class="dropdown-item" href="dashboard.php?val=tspcl&user_type=<?php echo $user_type; ?>">
                                <i class="fas fa-industry me-2"></i> TSPCL
                            </a></li>
                            <li><a class="dropdown-item" href="dashboard.php?val=dapfcl&user_type=<?php echo $user_type; ?>">
                                <i class="fas fa-industry me-2"></i> DAPFCL
                            </a></li>
                            <li><a class="dropdown-item" href="dashboard.php?val=bisf&user_type=<?php echo $user_type; ?>">
                                <i class="fas fa-industry me-2"></i> BISF
                            </a></li>
                            <li><a class="dropdown-item" href="dashboard.php?val=cccl&user_type=<?php echo $user_type; ?>">
                                <i class="fas fa-industry me-2"></i> CCCL
                            </a></li>
                            <li><a class="dropdown-item" href="dashboard.php?val=ugsf&user_type=<?php echo $user_type; ?>">
                                <i class="fas fa-industry me-2"></i> UGSF
                            </a></li>
                            <li><a class="dropdown-item" href="dashboard.php?val=kpml&user_type=<?php echo $user_type; ?>">
                                <i class="fas fa-industry me-2"></i> KPML
                            </a></li>
                        </div>
                    </div>
                    <div class="dropdown-divider my-2"></div>
                    <li class="text-center px-3 py-2">
                        <small class="text-muted">
                            <i class="fas fa-factory me-1"></i> 11 Factories Available
                        </small>
                    </li>
                </ul>
            </div>
            <div class="mt-3">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i> Click to view individual factory dashboard
                </small>
            </div>
        </div>
    </div>
</div>
                        
                        <!-- Factory Selection - FIXED SIMPLE VERSION -->
                     <!--    <div class="col-lg-4 col-md-6">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body factory-dropdown-container">
                                    <h6 class="card-title text-primary mb-3">
                                        <i class="fas fa-industry me-2"></i> Factory Selection
                                    </h6>
                                    <div class="dropdown factory-dropdown">
                                        <button class="btn btn-bcic btn-bcic-primary w-100 dropdown-toggle" type="button" 
                                                id="factoryDropdown" data-bs-toggle="dropdown" 
                                                aria-expanded="false">
                                            <i class="fas fa-list me-2"></i> Select Factory
                                        </button>
                                        <ul class="dropdown-menu w-100" aria-labelledby="factoryDropdown">
                                            <li><h6 class="dropdown-header text-primary">Fertilizer Factories</h6></li>
                                            <li><a class="dropdown-item" href="dashboard.php?val=sfcl&user_type=<?php echo $user_type; ?>">
                                                <i class="fas fa-factory me-2"></i> SFCL
                                            </a></li>
                                            <li><a class="dropdown-item" href="dashboard.php?val=jfcl&user_type=<?php echo $user_type; ?>">
                                                <i class="fas fa-factory me-2"></i> JFCL
                                            </a></li>
                                            <li><a class="dropdown-item" href="dashboard.php?val=afccl&user_type=<?php echo $user_type; ?>">
                                                <i class="fas fa-factory me-2"></i> AFCCL
                                            </a></li>
                                            <li><a class="dropdown-item" href="dashboard.php?val=cufl&user_type=<?php echo $user_type; ?>">
                                                <i class="fas fa-factory me-2"></i> CUFL
                                            </a></li>
                                            <li><a class="dropdown-item" href="dashboard.php?val=gpfplc&user_type=<?php echo $user_type; ?>">
                                                <i class="fas fa-factory me-2"></i> GPFPLC
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="dashboard.php?val=tspcl&user_type=<?php echo $user_type; ?>">
                                                <i class="fas fa-industry me-2"></i> TSPCL
                                            </a></li>
                                            <li><a class="dropdown-item" href="dashboard.php?val=dapfcl&user_type=<?php echo $user_type; ?>">
                                                <i class="fas fa-industry me-2"></i> DAPFCL
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><h6 class="dropdown-header text-success">Other Industries</h6></li>
                                            <li><a class="dropdown-item" href="dashboard.php?val=bisf&user_type=<?php echo $user_type; ?>">
                                                <i class="fas fa-industry me-2"></i> BISF
                                            </a></li>
                                            <li><a class="dropdown-item" href="dashboard.php?val=cccl&user_type=<?php echo $user_type; ?>">
                                                <i class="fas fa-industry me-2"></i> CCCL
                                            </a></li>
                                            <li><a class="dropdown-item" href="dashboard.php?val=ugsf&user_type=<?php echo $user_type; ?>">
                                                <i class="fas fa-industry me-2"></i> UGSF
                                            </a></li>
                                            <li><a class="dropdown-item" href="dashboard.php?val=kpml&user_type=<?php echo $user_type; ?>">
                                                <i class="fas fa-industry me-2"></i> KPML
                                            </a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li class="dropdown-item text-center text-muted">
                                                <small><i class="fas fa-factory me-1"></i> 11 Factories Available</small>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1"></i> Click to view individual factory dashboard
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        
                        <!-- Action Buttons -->
                        <div class="col-lg-4 col-md-12">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-primary mb-3">
                                        <i class="fas fa-cogs me-2"></i> Quick Actions
                                    </h6>
                                    <div class="action-buttons d-flex flex-wrap gap-2">
                                        <a class="btn btn-bcic btn-bcic-primary flex-fill" id="reload-btn" href="home.php">
                                            <i class="fas fa-sync-alt me-1"></i> Reload
                                        </a>
                                        
                                        <?php if ($user_type == 'sadmin' || $user_type == 'admin') { ?>
                                        <form id="downloadForm" action="dawnload_database.php" method="post" class="flex-fill">
                                            <button class="btn btn-bcic btn-bcic-warning w-100" type="submit" name="submit">
                                                <i class="fas fa-download me-1"></i> Download DB
                                            </button>
                                        </form>
                                        <?php } ?>
                                        
                                        <?php if ($user_type == 'sadmin') { ?>
                                        <a class="btn btn-bcic btn-bcic-info flex-fill" href="set_name.php">
                                            <i class="fas fa-edit me-1"></i> Set Name
                                        </a>
                                        <?php } ?>
                                        
                                        <button type="button" class="btn btn-bcic btn-bcic-success flex-fill" id="print_ind_tenants_aa">
                                            <i class="fas fa-print me-1"></i> Print Report
                                        </button>
                                        
                                        <a class="btn btn-bcic btn-bcic-danger flex-fill" href="logout.php" id="logout">
                                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Report Title -->
            <div class="bcic-card">
                <div class="card-body text-center py-4">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-start">
                            <img src="bcic_logo.png" alt="BCIC Logo" class="img-fluid" style="max-height: 80px;">
                        </div>
                        <div class="col-md-8">
                            <h3 class="text-primary mb-2">
                                <i class="fas fa-chart-line me-2"></i> Daily Production & Plant Status Report
                            </h3>
                            <div class="row justify-content-center">
                                <div class="col-auto">
                                    <div class="alert alert-primary py-2 px-3 mb-0">
                                        <i class="fas fa-calendar-check me-2"></i>
                                        <strong>Production as On:</strong>
                                        <?php if (isset($_POST['hit'])) { ?>
                                            <?php echo date('d-m-Y', strtotime($_POST['date'])); ?>
                                        <?php } else { ?>
                                            <?php echo date('d-m-Y', strtotime('-1 day')); ?>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="alert alert-success py-2 px-3 mb-0">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        <strong>Report Dated:</strong> <?php echo date('d-m-Y'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-end">
                            <span class="badge bg-primary fs-6 p-2">
                                <i class="fas fa-file-contract me-1"></i> Official Report
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Production Data Table -->
            <div class="bcic-card print-area" id="printableArea_ind_tenants_aa">
                <div class="bcic-card-header">
                    <i class="fas fa-table me-2"></i> Production Summary
                    <span class="badge bg-light text-dark ms-2">
                        <?php 
                        if (isset($_POST['hit'])) {
                            echo date('l, F d, Y', strtotime($_POST['date']));
                        } else {
                            echo date('l, F d, Y', strtotime('-1 day'));
                        }
                        ?>
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bcic table-hover mb-0" id="table_content">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Factory Name</th>
                                    <th>Product</th>
                                    <th class="text-center">Unit</th>
                                    <th class="text-center">Installed Capacity</th>
                                    <th class="text-center">Daily</th>
                                    <th class="text-center">Monthly</th>
                                    <th class="text-center">Yearly</th>
                                    <th class="text-center">Yearly Target</th>
                                    <th class="text-center">Due</th>
                                    <th class="text-center">Monthly Target</th>
                                    <th class="text-center">Plant Load (%)</th>
                                    <th>Remarks</th>
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
                
                <!-- Footer Notes -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="footer-notes">
                                <h6 class="text-primary mb-2"><i class="fas fa-share-square me-1"></i> <b>C.C TO (Not on the basis of seniority):</b></h6>
                                <div class="row">
                                    <div class="col-6">
                                        1. Sr. Secretary, MoInd, GOD, Dhaka.<br>
                                        2. Chairman (Grade-1), BCIC, Dhaka.<br>
                                        3. Addl. Secretary, MoInd, GOD, Dhaka.<br>
                                        4. PS to Honorable Advisor, MoInd, GOD, Dhaka.<br>
                                        5. Director (), BCIC, Dhaka.
                                    </div>
                                    <div class="col-6">
                                        6. Senior General Manager (Admin), BCIC, Dhaka.<br>
                                        7. Head of Marketing/CA/Chief Auditors, BCIC, Dhaka.<br>
                                        8. O/C.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="text-center border-start ps-4">
                                <h6 class="text-primary mb-2"><i class="fas fa-user-tie me-1"></i> <b>Prepared By:</b></h6>
                                <h5 class="text-success mb-1">General Manager (Production)</h5>
                                <p class="text-muted mb-1">Production Division, BCIC.</p>
                                <p class="text-muted mb-1"><i class="fas fa-phone me-1"></i> Phone No: 02223388176</p>
                                <p class="text-muted mb-0"><i class="fas fa-envelope me-1"></i> Email: productionbcic@gmail.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3 pt-3 border-top">
                        <small class="text-muted">
                            <i class="fas fa-code me-1"></i> Design & Developed By ICT Division, BCIC.
                            <span class="mx-2">|</span>
                            <i class="fas fa-shield-alt me-1"></i> Secure Production Reporting System
                            <span class="mx-2">|</span>
                            <i class="fas fa-clock me-1"></i> Last Updated: <?php echo date('h:i A'); ?>
                        </small>
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