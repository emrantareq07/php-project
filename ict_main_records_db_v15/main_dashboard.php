<?php
session_name('ict_main_records_db');
session_start();
$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
$office = $_SESSION['office'];
$code = $_SESSION['code'];

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

include('db/db.php');
include('includes/header.php');

// Get initial data for dropdowns and stats
$divisions = [];
$vendors = [];
$fiscal_years = [];

// Fetch divisions
$divisionQuery = "SELECT division FROM division ORDER BY division";
$divisionResult = mysqli_query($conn, $divisionQuery);
while ($row = mysqli_fetch_assoc($divisionResult)) {
    $divisions[] = $row['division'];
}

// Fetch vendors
$vendorQuery = "SELECT vendor_name FROM vendor_list ORDER BY vendor_name";
$vendorResult = mysqli_query($conn, $vendorQuery);
while ($row = mysqli_fetch_assoc($vendorResult)) {
    $vendors[] = $row['vendor_name'];
}

// Fetch fiscal years
$fiscalQuery = "SELECT DISTINCT CONCAT(YEAR(fiscal_start), '-', YEAR(fiscal_end)) as fiscal_year 
                FROM vendor_list 
                WHERE fiscal_start IS NOT NULL AND fiscal_end IS NOT NULL
                ORDER BY fiscal_start DESC";
$fiscalResult = mysqli_query($conn, $fiscalQuery);
while ($row = mysqli_fetch_assoc($fiscalResult)) {
    $fiscal_years[] = $row['fiscal_year'];
}

// Get current fiscal year
$currentYear = date('Y');
$nextYear = date('Y') + 1;
$currentFiscalYear = "{$currentYear}-{$nextYear}";

// Get quick stats
$totalDivisions = count($divisions);
$totalVendors = count($vendors);

$recordsQuery = "SELECT COUNT(*) as total FROM records_tbl";
$recordsResult = mysqli_query($conn, $recordsQuery);
$recordsData = mysqli_fetch_assoc($recordsResult);
$totalRecords = $recordsData['total'];

// Get current month records
$currentMonth = date('Y-m');
$monthlyQuery = "SELECT COUNT(*) as month_count FROM records_tbl WHERE DATE_FORMAT(date, '%Y-%m') = '$currentMonth'";
$monthlyResult = mysqli_query($conn, $monthlyQuery);
$monthlyData = mysqli_fetch_assoc($monthlyResult);
$currentMonthRecords = $monthlyData['month_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICT Maintenance Dashboard</title>
    
    <!-- Latest Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    
    <!-- Latest Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Animate.css for animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    
    <!-- ApexCharts for additional chart types -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-light: #4895ef;
            --secondary-color: #3a0ca3;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --info-color: #7209b7;
            --dark-color: #212529;
            --light-color: #f8f9fa;
            --gray-color: #6c757d;
            --border-radius: 15px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            min-height: 100vh;
            color: var(--dark-color);
        }
        
        .dashboard-wrapper {
            padding: 20px;
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 25px;
            border-radius: var(--border-radius);
            margin-bottom: 10px;
            box-shadow: 0 10px 25px rgba(67, 97, 238, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.1;
        }
        
        .dashboard-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: var(--box-shadow);
            border: none;
            transition: var(--transition);
            height: 100%;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }
        
        .stats-card {
            text-align: center;
            padding: 30px 20px;
        }
        
        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--primary-light), var(--primary-color));
            color: white;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .stat-value {
            font-size: 2.8rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 5px;
            line-height: 1;
        }
        
        .stat-label {
            font-size: 0.95rem;
            color: var(--gray-color);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .stat-change {
            font-size: 0.85rem;
            margin-top: 8px;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-block;
        }
        
        .change-positive {
            background: rgba(76, 201, 240, 0.15);
            color: var(--success-color);
        }
        
        .change-negative {
            background: rgba(247, 37, 133, 0.15);
            color: var(--warning-color);
        }
        
        .filter-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark-color);
            display: flex;
            align-items: center;
        }
        
        .chart-title i {
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }
        
        .action-btn {
            flex: 1;
            min-width: 120px;
            padding: 15px;
            background: white;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .action-btn:hover {
            border-color: var(--primary-color);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(67, 97, 238, 0.15);
        }
        
        .action-icon {
            font-size: 24px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .action-label {
            font-size: 0.9rem;
            color: var(--dark-color);
            font-weight: 500;
        }
        
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 10;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--warning-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .user-info {
            background: rgba(255, 255, 255, 0.15);
            padding: 10px 20px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
        }
        
        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .data-table th {
            background: #f8f9fa;
            padding: 15px;
            font-weight: 600;
            color: var(--dark-color);
            border-bottom: 2px solid #e9ecef;
        }
        
        .data-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .data-table tr:hover td {
            background: #f8f9fa;
        }
        
        .progress-bar-custom {
            height: 10px;
            border-radius: 5px;
            background: #e9ecef;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            border-radius: 5px;
            background: linear-gradient(90deg, var(--primary-light), var(--primary-color));
        }
        
        @media (max-width: 768px) {
            .dashboard-wrapper {
                padding: 10px;
            }
            
            .dashboard-card {
                padding: 20px;
            }
            
            .stat-value {
                font-size: 2.2rem;
            }
            
            .quick-actions {
                flex-direction: column;
            }
            
            .action-btn {
                min-width: 100%;
            }
        }
        
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
<div class="dashboard-wrapper">
    <!-- Dashboard Header -->
    <div class="dashboard-header animate-on-scroll">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="mb-2"><i class="fas fa-tachometer-alt me-3"></i>ICT Maintenance Dashboard</h1>
                <p class="mb-0">Comprehensive analytics and insights for maintenance management</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="user-info d-inline-block">
                    <i class="fas fa-user-circle me-2"></i>
                    <?php echo htmlspecialchars($username); ?>
                    <span class="badge bg-light text-dark ms-2"><?php echo htmlspecialchars($user_type); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4 animate-on-scroll">
        <div class="col-12">
            <div class="quick-actions">
                <div class="action-btn" onclick="window.location.href='dashboard.php'">
                    <div class="action-icon">
                        <i class="fas fa-table"></i>
                    </div>
                    <div class="action-label">View Records</div>
                </div>
                <div class="action-btn" onclick="#">
                    <div class="action-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="action-label">Add Record</div>
                </div>
                <div class="action-btn" onclick="#">
                    <div class="action-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="action-label">Manage Vendors</div>
                </div>
                <!-- <div class="action-btn" onclick="window.location.href='reports.php'"> -->
                <div class="action-btn" onclick="#">
                    <div class="action-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="action-label">Generate Reports</div>
                </div>
                <div class="action-btn" onclick="exportDashboard()">
                    <div class="action-icon">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="action-label">Export Data</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4 animate-on-scroll">
        <div class="col-12">
            <div class="filter-card">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-calendar-alt me-2"></i>Fiscal Year</label>
                        <select class="form-select" id="fiscalYearFilter">
                            <option value="all">All Fiscal Years</option>
                            <?php foreach ($fiscal_years as $year): ?>
                                <option value="<?php echo htmlspecialchars($year); ?>" 
                                    <?php echo $year == $currentFiscalYear ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($year); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-building me-2"></i>Division</label>
                        <select class="form-select" id="divisionFilter">
                            <option value="all">All Divisions</option>
                            <?php foreach ($divisions as $division): ?>
                                <option value="<?php echo htmlspecialchars($division); ?>">
                                    <?php echo htmlspecialchars($division); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold"><i class="fas fa-truck me-2"></i>Vendor</label>
                        <select class="form-select" id="vendorFilter">
                            <option value="all">All Vendors</option>
                            <?php foreach ($vendors as $vendor): ?>
                                <option value="<?php echo htmlspecialchars($vendor); ?>">
                                    <?php echo htmlspecialchars($vendor); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <!-- <button class="btn btn-primary w-100" onclick="loadDashboardData()">
                            <i class="fas fa-sync-alt me-2"></i>Apply Filters
                        </button> -->
                        <button class="btn btn-primary w-100" id="applyFiltersBtn">
                            <i class="fas fa-sync-alt me-2"></i>Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Statistics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="dashboard-card stats-card animate-on-scroll">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-value" id="totalDivisions"><?php echo $totalDivisions; ?></div>
                <div class="stat-label">Total Divisions</div>
                <div class="stat-change change-positive" id="divisionChange">
                    <i class="fas fa-arrow-up me-1"></i> 0% from last month
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card stats-card animate-on-scroll">
                <div class="stat-icon">
                    <i class="fas fa-truck"></i>
                </div>
                <div class="stat-value" id="totalVendors"><?php echo $totalVendors; ?></div>
                <div class="stat-label">Active Vendors</div>
                <div class="stat-change change-positive" id="vendorChange">
                    <i class="fas fa-arrow-up me-1"></i> 0% from last month
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card stats-card animate-on-scroll">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-value" id="totalRecords"><?php echo $totalRecords; ?></div>
                <div class="stat-label">Total Records</div>
                <div class="stat-change change-positive" id="recordsChange">
                    <i class="fas fa-arrow-up me-1"></i> 0% from last month
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card stats-card animate-on-scroll">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-value" id="currentMonthRecords"><?php echo $currentMonthRecords; ?></div>
                <div class="stat-label">This Month</div>
                <div class="stat-change change-positive" id="monthlyChange">
                    <i class="fas fa-arrow-up me-1"></i> 0% from last month
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="dashboard-card animate-on-scroll">
                <h4 class="chart-title"><i class="fas fa-chart-pie me-2"></i>Records by Division</h4>
                <div class="chart-container">
                    <div class="loading-spinner" id="divisionChartLoading">
                        <div class="spinner-border text-primary" role="status"></div>
                    </div>
                    <canvas id="divisionChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="dashboard-card animate-on-scroll">
                <h4 class="chart-title"><i class="fas fa-chart-bar me-2"></i>Top Vendors by Records</h4>
                <div class="chart-container">
                    <div class="loading-spinner" id="vendorChartLoading">
                        <div class="spinner-border text-success" role="status"></div>
                    </div>
                    <canvas id="vendorChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="dashboard-card animate-on-scroll">
                <h4 class="chart-title"><i class="fas fa-chart-line me-2"></i>Monthly Trend</h4>
                <div class="chart-container">
                    <div class="loading-spinner" id="trendChartLoading">
                        <div class="spinner-border text-warning" role="status"></div>
                    </div>
                    <canvas id="monthlyTrendChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="dashboard-card animate-on-scroll">
                <h4 class="chart-title"><i class="fas fa-chart-area me-2"></i>Fiscal Year Comparison</h4>
                <div class="chart-container">
                    <div class="loading-spinner" id="fiscalChartLoading">
                        <div class="spinner-border text-info" role="status"></div>
                    </div>
                    <canvas id="fiscalYearChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity & Top Products -->
    <div class="row">
        <div class="col-md-6">
            <div class="dashboard-card animate-on-scroll">
                <h4 class="chart-title"><i class="fas fa-history me-2"></i>Recent Activity</h4>
                <div class="table-responsive">
                    <table class="data-table" id="recentActivity">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="dashboard-card animate-on-scroll">
                <h4 class="chart-title"><i class="fas fa-star me-2"></i>Top Products</h4>
                <div class="table-responsive">
                    <table class="data-table" id="topProducts">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Usage</th>
                                <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Global chart instances
let divisionChart = null;
let vendorChart = null;
let fiscalYearChart = null;
let monthlyTrendChart = null;
let isInitialLoad = true; // Track if it's the first load

// Color schemes
const colorSchemes = {
    division: ['#4361ee', '#3a0ca3', '#4cc9f0', '#f72585', '#7209b7', '#4895ef', '#560bad', '#b5179e'],
    vendor: ['#ff595e', '#ffca3a', '#8ac926', '#1982c4', '#6a4c93', '#ff9a00', '#00b4d8', '#7209b7'],
    fiscal: ['#ffadad', '#ffd6a5', '#fdffb6', '#caffbf', '#9bf6ff', '#a0c4ff', '#bdb2ff', '#ffc6ff'],
    trend: ['#4361ee', '#4cc9f0']
};

// Initialize dashboard
document.addEventListener('DOMContentLoaded', function() {
    // Load initial data
    loadDashboardData();
    
    // Set up animations
    setupAnimations();
    
    // Set up auto-refresh (every 5 minutes) - silent refresh
    setInterval(() => {
        loadDashboardData(false); // Don't show notification for auto-refresh
    }, 300000);
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'r') {
            e.preventDefault();
            loadDashboardData(true); // Show notification for manual refresh
        }
        if (e.ctrlKey && e.key === 'e') {
            e.preventDefault();
            exportDashboard();
        }
    });
    
    // Add event listener to Apply Filters button
    document.getElementById('applyFiltersBtn').addEventListener('click', function() {
        loadDashboardData(true); // Show notification when filters are applied
    });
});

async function loadDashboardData(showNotificationFlag = false) {
    const filters = {
        fiscalYear: document.getElementById('fiscalYearFilter').value,
        division: document.getElementById('divisionFilter').value,
        vendor: document.getElementById('vendorFilter').value
    };
    
    showLoading(true);
    
    try {
        // Fetch dashboard data
        const response = await fetch('api/dashboard_data.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(filters)
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        // Check if we got valid data
        if (!data.success) {
            throw new Error('Invalid data received from server');
        }
        
        // Update all dashboard components
        updateSummaryStats(data.summary || {});
        updateDivisionChart(data.divisionData || {labels: [], values: []});
        updateVendorChart(data.vendorData || {labels: [], values: []});
        updateMonthlyTrendChart(data.monthlyData || {labels: [], values: []});
        updateFiscalYearChart(data.fiscalYearData || {labels: [], values: []});
        updateRecentActivity(data.recentActivity || []);
        updateTopProducts(data.topProducts || []);
        
        // Only show success notification if flag is true AND it's not initial load
        if (showNotificationFlag && !isInitialLoad) {
            showNotification('Dashboard updated successfully', 'success');
        }
        
        // Mark initial load as complete
        isInitialLoad = false;
        
    } catch (error) {
        console.error('Error loading dashboard data:', error);
        
        // Only show error notification if it's not initial load
        if (!isInitialLoad) {
            showNotification('Error loading dashboard data. Please try again.', 'error');
        }
        
        // Load sample data for demonstration
        loadSampleData();
        
        // Mark initial load as complete even with error
        isInitialLoad = false;
    } finally {
        showLoading(false);
    }
}

function updateSummaryStats(summary) {
    // Update main stats
    document.getElementById('totalDivisions').textContent = summary.totalDivisions || 0;
    document.getElementById('totalVendors').textContent = summary.totalVendors || 0;
    document.getElementById('totalRecords').textContent = summary.totalRecords || 0;
    document.getElementById('currentMonthRecords').textContent = summary.currentMonthRecords || 0;
    
    // Update change percentages
    const divisionChange = document.getElementById('divisionChange');
    const vendorChange = document.getElementById('vendorChange');
    const recordsChange = document.getElementById('recordsChange');
    const monthlyChange = document.getElementById('monthlyChange');
    
    if (divisionChange) {
        const changeValue = summary.divisionChange || 0;
        divisionChange.innerHTML = `<i class="fas ${changeValue >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'} me-1"></i> ${Math.abs(changeValue)}% from last month`;
        divisionChange.className = `stat-change ${changeValue >= 0 ? 'change-positive' : 'change-negative'}`;
    }
    
    if (vendorChange) {
        const changeValue = summary.vendorChange || 0;
        vendorChange.innerHTML = `<i class="fas ${changeValue >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'} me-1"></i> ${Math.abs(changeValue)}% from last month`;
        vendorChange.className = `stat-change ${changeValue >= 0 ? 'change-positive' : 'change-negative'}`;
    }
    
    if (recordsChange) {
        const changeValue = summary.recordsChange || 0;
        recordsChange.innerHTML = `<i class="fas ${changeValue >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'} me-1"></i> ${Math.abs(changeValue)}% from last month`;
        recordsChange.className = `stat-change ${changeValue >= 0 ? 'change-positive' : 'change-negative'}`;
    }
    
    if (monthlyChange) {
        const changeValue = summary.monthlyChange || 0;
        monthlyChange.innerHTML = `<i class="fas ${changeValue >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'} me-1"></i> ${Math.abs(changeValue)}% from last month`;
        monthlyChange.className = `stat-change ${changeValue >= 0 ? 'change-positive' : 'change-negative'}`;
    }
}

function updateDivisionChart(data) {
    const ctx = document.getElementById('divisionChart').getContext('2d');
    
    if (divisionChart) divisionChart.destroy();
    
    divisionChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.labels,
            datasets: [{
                data: data.values,
                backgroundColor: colorSchemes.division,
                borderColor: 'white',
                borderWidth: 2,
                hoverOffset: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        font: { size: 11 }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.raw} records`;
                        }
                    }
                }
            }
        }
    });
}

function updateVendorChart(data) {
    const ctx = document.getElementById('vendorChart').getContext('2d');
    
    if (vendorChart) vendorChart.destroy();
    
    // Sort and limit to top 5 vendors
    const sortedIndices = data.values.map((v, i) => i)
        .sort((a, b) => data.values[b] - data.values[a])
        .slice(0, 5);
    
    const sortedLabels = sortedIndices.map(i => data.labels[i]);
    const sortedValues = sortedIndices.map(i => data.values[i]);
    
    vendorChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: sortedLabels,
            datasets: [{
                label: 'Records',
                data: sortedValues,
                backgroundColor: colorSchemes.vendor,
                borderColor: 'white',
                borderWidth: 1,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { stepSize: 1 }
                },
                x: {
                    grid: { display: false }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Records: ${context.raw}`;
                        }
                    }
                }
            }
        }
    });
}

function updateMonthlyTrendChart(data) {
    const ctx = document.getElementById('monthlyTrendChart').getContext('2d');
    
    if (monthlyTrendChart) monthlyTrendChart.destroy();
    
    monthlyTrendChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Records',
                data: data.values,
                backgroundColor: 'rgba(67, 97, 238, 0.1)',
                borderColor: colorSchemes.trend[0],
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'white',
                pointBorderColor: colorSchemes.trend[0],
                pointBorderWidth: 2,
                pointRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: {
                    grid: { color: 'rgba(0,0,0,0.05)' }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
}

function updateFiscalYearChart(data) {
    const ctx = document.getElementById('fiscalYearChart').getContext('2d');
    
    if (fiscalYearChart) fiscalYearChart.destroy();
    
    fiscalYearChart = new Chart(ctx, {
        type: 'radar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Records',
                data: data.values,
                backgroundColor: 'rgba(255, 173, 173, 0.2)',
                borderColor: colorSchemes.fiscal[0],
                borderWidth: 2,
                pointBackgroundColor: colorSchemes.fiscal[0]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    beginAtZero: true,
                    ticks: { display: false }
                }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
}

function updateRecentActivity(activities) {
    const tbody = document.querySelector('#recentActivity tbody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    activities.forEach(activity => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${activity.date || ''}</td>
            <td>${activity.user || ''}</td>
            <td><span class="badge bg-primary">${activity.action || ''}</span></td>
            <td>${activity.details || ''}</td>
        `;
        tbody.appendChild(row);
    });
}

function updateTopProducts(products) {
    const tbody = document.querySelector('#topProducts tbody');
    if (!tbody) return;
    
    tbody.innerHTML = '';
    
    products.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.name || ''}</td>
            <td>${product.count || 0}</td>
            <td>
                <div class="progress-bar-custom">
                    <div class="progress-fill" style="width: ${product.percentage || 0}%"></div>
                </div>
                <small class="text-muted">${product.percentage || 0}%</small>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function showLoading(loading) {
    const loaders = ['divisionChartLoading', 'vendorChartLoading', 'trendChartLoading', 'fiscalChartLoading'];
    loaders.forEach(id => {
        const loader = document.getElementById(id);
        if (loader) loader.style.display = loading ? 'flex' : 'none';
    });
}

function exportDashboard() {
    const dashboard = document.querySelector('.dashboard-wrapper');
    if (!dashboard) return;
    
    html2canvas(dashboard, {
        scale: 2,
        useCORS: true,
        backgroundColor: '#ffffff'
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = `ICT-Dashboard-${new Date().toISOString().split('T')[0]}.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
        
        showNotification('Dashboard exported successfully!', 'success');
    }).catch(error => {
        console.error('Error exporting dashboard:', error);
        showNotification('Error exporting dashboard. Please try again.', 'error');
    });
}

function showNotification(message, type) {
    // Remove any existing notifications
    const existingNotifications = document.querySelectorAll('.alert-notification');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'error' ? 'danger' : 'success'} alert-notification alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);';
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas ${type === 'error' ? 'fa-exclamation-triangle' : 'fa-check-circle'} me-2"></i>
            <div>${message}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}

function setupAnimations() {
    const elements = document.querySelectorAll('.animate-on-scroll');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });
    
    elements.forEach(element => observer.observe(element));
}

function loadSampleData() {
    // Fallback sample data for demonstration
    const sampleData = {
        summary: {
            totalDivisions: 5,
            totalVendors: 12,
            totalRecords: 245,
            currentMonthRecords: 18,
            divisionChange: 12,
            vendorChange: 8,
            recordsChange: 15,
            monthlyChange: 20
        },
        divisionData: {
            labels: ['Division A', 'Division B', 'Division C', 'Division D', 'Division E'],
            values: [45, 30, 25, 20, 15]
        },
        vendorData: {
            labels: ['Vendor X', 'Vendor Y', 'Vendor Z', 'Vendor A', 'Vendor B'],
            values: [120, 90, 75, 60, 45]
        },
        monthlyData: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            values: [65, 59, 80, 81, 56, 55]
        },
        fiscalYearData: {
            labels: ['2022-23', '2023-24', '2024-25'],
            values: [300, 450, 520]
        },
        recentActivity: [
            {date: '2024-01-15', user: 'John Doe', action: 'Added', details: 'New maintenance record'},
            {date: '2024-01-14', user: 'Jane Smith', action: 'Updated', details: 'Vendor information'},
            {date: '2024-01-13', user: 'Bob Wilson', action: 'Deleted', details: 'Duplicate record'}
        ],
        topProducts: [
            {name: 'Laptop Dell XPS', count: 45, percentage: 25},
            {name: 'Monitor Samsung', count: 38, percentage: 21},
            {name: 'Printer HP', count: 32, percentage: 18},
            {name: 'Router Cisco', count: 28, percentage: 16}
        ]
    };
    
    // Update with sample data
    updateSummaryStats(sampleData.summary);
    updateDivisionChart(sampleData.divisionData);
    updateVendorChart(sampleData.vendorData);
    updateMonthlyTrendChart(sampleData.monthlyData);
    updateFiscalYearChart(sampleData.fiscalYearData);
    updateRecentActivity(sampleData.recentActivity);
    updateTopProducts(sampleData.topProducts);
}

// Add manual refresh function
function manualRefresh() {
    loadDashboardData(true);
}

// Add filter change auto-apply if desired (optional)
// Uncomment if you want filters to auto-apply when changed
/*
document.getElementById('fiscalYearFilter').addEventListener('change', function() {
    if (!isInitialLoad) {
        loadDashboardData(true);
    }
});

document.getElementById('divisionFilter').addEventListener('change', function() {
    if (!isInitialLoad) {
        loadDashboardData(true);
    }
});

document.getElementById('vendorFilter').addEventListener('change', function() {
    if (!isInitialLoad) {
        loadDashboardData(true);
    }
});
*/
</script>

<!-- Include html2canvas for export functionality -->
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>

</body>
</html>