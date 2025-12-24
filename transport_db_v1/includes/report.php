<?php 
session_name('transport_db');
include 'vehicle_crud.php'; 
include 'header.php';
?>

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header text-center bg-primary text-white py-4 rounded shadow">
                <h1 class="display-5 fw-bold mb-2">
                    <i class="fas fa-chart-bar me-2"></i>BCIC Vehicle Database Reports
                </h1>
                <p class="lead mb-0">Comprehensive vehicle analytics and insights</p>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                Total Vehicles
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="totalVehicles"><?php echo count($vehicles); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-car fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                Operational Vehicles
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="operationalVehicles">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                Non-Operational
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="nonOperationalVehicles">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                Avg KM Driven
                            </div>
                            <div class="h5 mb-0 fw-bold text-gray-800" id="avgKmDriven">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tachometer-alt fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-filter me-2"></i>Report Filters
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="vehicleStatus" class="form-label fw-bold">
                                <i class="fas fa-car me-1"></i>Vehicle Status
                            </label>
                            <select id="vehicleStatus" class="form-select" onchange="filterData()">
                                <option value="">Show All</option>
                                <option value="ব্যবহৃত">Operational (ব্যবহৃত)</option>
                                <option value="ব্যবহার অনুপযোগী">Non-Operational (ব্যবহার অনুপযোগী)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="vehicleType" class="form-label fw-bold">
                                <i class="fas fa-shapes me-1"></i>Vehicle Type
                            </label>
                            <select id="vehicleType" class="form-select" onchange="filterData()">
                                <option value="">All Types</option>
                                <option value="কার">Car (কার)</option>
                                <option value="পাজেরো">Pajero (পাজেরো)</option>
                                <option value="মাইক্রোবাস">Microbus (মাইক্রোবাস)</option>
                                <option value="জিপ">Jeep (জিপ)</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="yearFilter" class="form-label fw-bold">
                                <i class="fas fa-calendar me-1"></i>Purchase Year
                            </label>
                            <select id="yearFilter" class="form-select" onchange="filterData()">
                                <option value="">All Years</option>
                                <?php
                                $years = array_unique(array_column($vehicles, 'sourcing_buying_year'));
                                rsort($years);
                                foreach ($years as $year) {
                                    if (!empty($year)) {
                                        echo "<option value='$year'>$year</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="driverType" class="form-label fw-bold">
                                <i class="fas fa-id-card me-1"></i>Driver Type
                            </label>
                            <select id="driverType" class="form-select" onchange="filterData()">
                                <option value="">All Types</option>
                                <option value="আউটসোর্সিং">Outsourcing (আউটসোর্সিং)</option>
                                <option value="স্থায়ী">Permanent (স্থায়ী)</option>
                                <option value="দৈনিক ভিত্তিক">Daily Basis (দৈনিক ভিত্তিক)</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-primary" onclick="filterData()">
                                    <i class="fas fa-search me-1"></i> Apply Filters
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="resetFilters()">
                                    <i class="fas fa-redo me-1"></i> Reset Filters
                                </button>
                                <button type="button" class="btn btn-success" onclick="exportToExcel()">
                                    <i class="fas fa-file-excel me-1"></i> Export to Excel
                                </button>
                                <button type="button" class="btn btn-info" onclick="exportToPDF()">
                                    <i class="fas fa-file-pdf me-1"></i> Export to PDF
                                </button>
                                <button type="button" class="btn btn-warning" onclick="printReport()">
                                    <i class="fas fa-print me-1"></i> Print Report
                                </button>
                                <button type="button" class="btn btn-dark" onclick="showChart()">
                                    <i class="fas fa-chart-pie me-1"></i> View Charts
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-table me-2"></i>Vehicle Report
                        <span class="badge bg-primary ms-2" id="resultCount">0</span>
                    </h5>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-columns me-1"></i> Columns
                        </button>
                        <ul class="dropdown-menu" id="columnToggle">
                            <li><a class="dropdown-item" href="#" data-column="0"><i class="fas fa-check me-2"></i>Reg No</a></li>
                            <li><a class="dropdown-item" href="#" data-column="1"><i class="fas fa-check me-2"></i>Type</a></li>
                            <li><a class="dropdown-item" href="#" data-column="2"><i class="fas fa-check me-2"></i>Source</a></li>
                            <li><a class="dropdown-item" href="#" data-column="3"><i class="fas fa-check me-2"></i>KM</a></li>
                            <li><a class="dropdown-item" href="#" data-column="4"><i class="fas fa-check me-2"></i>User</a></li>
                            <li><a class="dropdown-item" href="#" data-column="5"><i class="fas fa-check me-2"></i>Driver</a></li>
                            <li><a class="dropdown-item" href="#" data-column="6"><i class="fas fa-check me-2"></i>Status</a></li>
                            <li><a class="dropdown-item" href="#" data-column="7"><i class="fas fa-check me-2"></i>Year</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0" id="vehicleReportTable">
                            <thead class="table-dark">
                                <tr>
                                    <th>Reg No</th>
                                    <th>Vehicle Type</th>
                                    <th>Vehicle Source</th>
                                    <th>Driven KM</th>
                                    <th>User Name</th>
                                    <th>Driver Name</th>
                                    <th>Status</th>
                                    <th>Purchase Year</th>
                                </tr>
                            </thead>
                            <tbody id="vehicleData">
                                <?php foreach ($vehicles as $vehicle): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($vehicle['reg_no']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($vehicle['vehicle_type']); ?></td>
                                    <td><?php echo htmlspecialchars($vehicle['vehicle_source']); ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?php echo !empty($vehicle['driven_km']) ? number_format((float)$vehicle['driven_km']) : '0'; ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($vehicle['user_name']); ?></td>
                                    <td><?php echo htmlspecialchars($vehicle['driver_name']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $vehicle['vehicle_status'] === 'ব্যবহৃত' ? 'bg-success' : 'bg-danger'; ?>">
                                            <?php echo htmlspecialchars($vehicle['vehicle_status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($vehicle['sourcing_buying_year']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="dataTables_info" id="reportInfo">
                                Showing all <?php echo count($vehicles); ?> entries
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <button class="btn btn-outline-secondary" onclick="sortTable('asc')">
                                    <i class="fas fa-sort-alpha-down me-1"></i> A-Z
                                </button>
                                <button class="btn btn-outline-secondary" onclick="sortTable('desc')">
                                    <i class="fas fa-sort-alpha-up me-1"></i> Z-A
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section (Hidden by Default) -->
    <div class="row mb-4 d-none" id="chartsSection">
        <div class="col-md-6 mb-3">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Vehicle Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="250"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Vehicle Type Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="typeChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Summary -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line me-2"></i>Statistics Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon text-primary">
                                    <i class="fas fa-road fa-2x"></i>
                                </div>
                                <div class="stat-number fw-bold fs-4" id="totalKm">0</div>
                                <div class="stat-label text-muted">Total KM Driven</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon text-success">
                                    <i class="fas fa-calendar-check fa-2x"></i>
                                </div>
                                <div class="stat-number fw-bold fs-4" id="avgAge">0</div>
                                <div class="stat-label text-muted">Avg Vehicle Age</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon text-warning">
                                    <i class="fas fa-user-friends fa-2x"></i>
                                </div>
                                <div class="stat-number fw-bold fs-4" id="uniqueUsers">0</div>
                                <div class="stat-label text-muted">Unique Users</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card">
                                <div class="stat-icon text-info">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                                <div class="stat-number fw-bold fs-4" id="uniqueDrivers">0</div>
                                <div class="stat-label text-muted">Unique Drivers</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Additional CSS -->
<style>
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .card {
        border: none;
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
    
    .border-left-success {
        border-left: 4px solid #28a745 !important;
    }
    
    .border-left-primary {
        border-left: 4px solid #007bff !important;
    }
    
    .border-left-warning {
        border-left: 4px solid #ffc107 !important;
    }
    
    .border-left-info {
        border-left: 4px solid #17a2b8 !important;
    }
    
    .stat-card {
        padding: 20px;
        border-radius: 10px;
        background: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        background: #e9ecef;
        transform: scale(1.05);
    }
    
    .stat-icon {
        margin-bottom: 15px;
    }
    
    .stat-number {
        font-size: 2.5rem;
        margin-bottom: 5px;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }
    
    .badge {
        padding: 6px 12px;
        font-weight: 500;
    }
    
    .dropdown-menu {
        min-width: 200px;
    }
    
    .dropdown-item.active {
        background-color: #007bff;
        color: white;
    }

    /* Print-specific styles */
    @media print {
        /* Hide everything except the report table */
        body * {
            visibility: hidden;
        }
        
        /* Show only the filtered report section */
        #printSection,
        #printSection * {
            visibility: visible;
        }
        
        /* Position the print section */
        #printSection {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        
        /* Hide unnecessary elements in print view */
        .no-print,
        .btn-group,
        .dropdown,
        .card-header .d-flex,
        .card-footer,
        .table-responsive,
        .badge,
        .table-hover tbody tr:hover {
            display: none !important;
        }
        
        /* Print table styling */
        #vehicleReportTable {
            border: 1px solid #000;
            width: 100%;
            border-collapse: collapse;
        }
        
        #vehicleReportTable th {
            background-color: #f8f9fa !important;
            color: #000 !important;
            border: 1px solid #000;
            padding: 8px;
            font-weight: bold;
        }
        
        #vehicleReportTable td {
            border: 1px solid #000;
            padding: 6px;
        }
        
        /* Remove badges and show plain text */
        .badge {
            background: none !important;
            color: #000 !important;
            padding: 0;
            font-weight: normal;
        }
        
        /* Print header styling */
        .print-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        .print-header h1 {
            font-size: 20px;
            margin-bottom: 5px;
        }
        
        .print-header p {
            font-size: 14px;
            margin: 0;
        }
        
        /* Print filters summary */
        .print-filters {
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
            font-size: 12px;
        }
        
        /* Print statistics */
        .print-stats {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            font-size: 12px;
        }
        
        .print-stats .stat-item {
            flex: 1;
            text-align: center;
            padding: 5px;
        }
        
        /* Page breaks */
        .page-break {
            page-break-before: always;
        }
        
        /* Force black and white printing */
        * {
            color: #000 !important;
            background-color: #fff !important;
        }
        
        /* Remove shadows and effects */
        .card,
        .table-striped,
        .table-hover {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
        }
        
        /* Ensure table fits page */
        table {
            page-break-inside: auto;
        }
        
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        
        /* Remove unnecessary spacing */
        body {
            padding: 0;
            margin: 0;
        }
    }
</style>

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initial data
    let allVehicles = <?php echo json_encode($vehicles); ?>;
    let filteredVehicles = [...allVehicles];
    
    // Initialize summary statistics
    function initializeStatistics() {
        let operational = allVehicles.filter(v => v.vehicle_status === 'ব্যবহৃত').length;
        let nonOperational = allVehicles.filter(v => v.vehicle_status === 'ব্যবহার অনুপযোগী').length;
        
        // Calculate total KM
        let totalKm = allVehicles.reduce((sum, vehicle) => {
            return sum + (parseInt(vehicle.driven_km) || 0);
        }, 0);
        
        // Calculate average KM
        let avgKm = allVehicles.length > 0 ? Math.round(totalKm / allVehicles.length) : 0;
        
        // Update summary cards
        document.getElementById('operationalVehicles').textContent = operational;
        document.getElementById('nonOperationalVehicles').textContent = nonOperational;
        document.getElementById('avgKmDriven').textContent = avgKm.toLocaleString();
        document.getElementById('totalKm').textContent = totalKm.toLocaleString();
        
        // Calculate average age
        let currentYear = new Date().getFullYear();
        let totalAge = allVehicles.reduce((sum, vehicle) => {
            let year = parseInt(vehicle.sourcing_buying_year) || currentYear;
            return sum + (currentYear - year);
        }, 0);
        let avgAge = allVehicles.length > 0 ? Math.round(totalAge / allVehicles.length) : 0;
        document.getElementById('avgAge').textContent = avgAge + ' years';
        
        // Unique users and drivers
        let uniqueUsers = [...new Set(allVehicles.map(v => v.user_name))].length;
        let uniqueDrivers = [...new Set(allVehicles.map(v => v.driver_name))].length;
        document.getElementById('uniqueUsers').textContent = uniqueUsers;
        document.getElementById('uniqueDrivers').textContent = uniqueDrivers;
        
        // Initialize charts
        initializeCharts();
    }
    
    // Filter data based on selections
    function filterData() {
        let status = document.getElementById('vehicleStatus').value;
        let type = document.getElementById('vehicleType').value;
        let year = document.getElementById('yearFilter').value;
        let driverType = document.getElementById('driverType').value;
        
        filteredVehicles = allVehicles.filter(vehicle => {
            let statusMatch = !status || vehicle.vehicle_status === status;
            let typeMatch = !type || vehicle.vehicle_type === type;
            let yearMatch = !year || vehicle.sourcing_buying_year == year;
            let driverMatch = !driverType || vehicle.driver_appt_type === driverType;
            
            return statusMatch && typeMatch && yearMatch && driverMatch;
        });
        
        updateTable();
        updateSummary();
    }
    
    // Reset all filters
    function resetFilters() {
        document.getElementById('vehicleStatus').value = '';
        document.getElementById('vehicleType').value = '';
        document.getElementById('yearFilter').value = '';
        document.getElementById('driverType').value = '';
        
        filteredVehicles = [...allVehicles];
        updateTable();
        updateSummary();
    }
    
    // Update the table with filtered data
    function updateTable() {
        let tbody = document.getElementById('vehicleData');
        tbody.innerHTML = '';
        
        filteredVehicles.forEach(vehicle => {
            let row = document.createElement('tr');
            row.innerHTML = `
                <td><strong>${vehicle.reg_no || ''}</strong></td>
                <td>${vehicle.vehicle_type || ''}</td>
                <td>${vehicle.vehicle_source || ''}</td>
                <td><span class="badge bg-info">${(vehicle.driven_km ? parseInt(vehicle.driven_km).toLocaleString() : '0')}</span></td>
                <td>${vehicle.user_name || ''}</td>
                <td>${vehicle.driver_name || ''}</td>
                <td><span class="badge ${vehicle.vehicle_status === 'ব্যবহৃত' ? 'bg-success' : 'bg-danger'}">${vehicle.vehicle_status || ''}</span></td>
                <td>${vehicle.sourcing_buying_year || ''}</td>
            `;
            tbody.appendChild(row);
        });
        
        document.getElementById('resultCount').textContent = filteredVehicles.length;
        document.getElementById('reportInfo').textContent = `Showing ${filteredVehicles.length} of ${allVehicles.length} entries`;
    }
    
    // Update summary statistics
    function updateSummary() {
        let operational = filteredVehicles.filter(v => v.vehicle_status === 'ব্যবহৃত').length;
        let nonOperational = filteredVehicles.filter(v => v.vehicle_status === 'ব্যবহার অনুপযোগী').length;
        
        document.getElementById('operationalVehicles').textContent = operational;
        document.getElementById('nonOperationalVehicles').textContent = nonOperational;
    }
    
    // Initialize charts
    function initializeCharts() {
        // Status distribution chart
        let statusCtx = document.getElementById('statusChart').getContext('2d');
        let operationalCount = allVehicles.filter(v => v.vehicle_status === 'ব্যবহৃত').length;
        let nonOperationalCount = allVehicles.filter(v => v.vehicle_status === 'ব্যবহার অনুপযোগী').length;
        
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: ['Operational', 'Non-Operational'],
                datasets: [{
                    data: [operationalCount, nonOperationalCount],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        // Vehicle type distribution chart
        let typeCtx = document.getElementById('typeChart').getContext('2d');
        let types = [...new Set(allVehicles.map(v => v.vehicle_type))];
        let typeCounts = types.map(type => allVehicles.filter(v => v.vehicle_type === type).length);
        
        new Chart(typeCtx, {
            type: 'bar',
            data: {
                labels: types,
                datasets: [{
                    label: 'Number of Vehicles',
                    data: typeCounts,
                    backgroundColor: ['#007bff', '#6610f2', '#6f42c1', '#e83e8c', '#fd7e14'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
    
    // Toggle charts visibility
    function showChart() {
        let chartsSection = document.getElementById('chartsSection');
        chartsSection.classList.toggle('d-none');
    }
    
    // Print report
    function printReport() {
        window.print();
    }


    
    // Export to Excel
    function exportToExcel() {
        // Implementation for Excel export
        alert('Excel export feature would be implemented here');
        // You can use libraries like SheetJS or TableExport
    }
    
    // Export to PDF
    function exportToPDF() {
        // Implementation for PDF export
        alert('PDF export feature would be implemented here');
        // You can use libraries like jsPDF or html2pdf
    }
    
    // Sort table
    function sortTable(order) {
        filteredVehicles.sort((a, b) => {
            let nameA = (a.reg_no || '').toUpperCase();
            let nameB = (b.reg_no || '').toUpperCase();
            
            if (order === 'asc') {
                return nameA.localeCompare(nameB);
            } else {
                return nameB.localeCompare(nameA);
            }
        });
        
        updateTable();
    }
    
    // Column toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        let columnToggle = document.getElementById('columnToggle');
        let table = document.getElementById('vehicleReportTable');
        
        columnToggle.addEventListener('click', function(e) {
            if (e.target.tagName === 'A') {
                e.preventDefault();
                let column = e.target.getAttribute('data-column');
                let isActive = e.target.classList.contains('active');
                
                if (isActive) {
                    e.target.classList.remove('active');
                    e.target.innerHTML = '<i class="fas fa-times me-2"></i>' + e.target.textContent;
                } else {
                    e.target.classList.add('active');
                    e.target.innerHTML = '<i class="fas fa-check me-2"></i>' + e.target.textContent;
                }
                
                // Toggle column visibility
                let headerCells = table.querySelectorAll('th');
                let bodyRows = table.querySelectorAll('tbody tr');
                
                if (headerCells[column]) {
                    headerCells[column].classList.toggle('d-none');
                    bodyRows.forEach(row => {
                        if (row.children[column]) {
                            row.children[column].classList.toggle('d-none');
                        }
                    });
                }
            }
        });
        
        // Initialize statistics
        initializeStatistics();
    });

    
</script>
</body>
</html>