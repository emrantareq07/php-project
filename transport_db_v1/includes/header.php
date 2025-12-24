<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Vehicle Management System</title>
    
    <!-- Bootstrap 5.3.2 CSS (Latest) -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> -->
    
    <!-- Font Awesome 6.5.1 (Latest) -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    
    <!-- DataTables 1.13.7 CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css"> -->

    <!-- Bootstrap 5.3.3 (when released) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome 6.6.0 (when released) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

<!-- DataTables 1.14.0 (when released) -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.14.0/css/dataTables.bootstrap5.min.css">
    
    <!-- DataTables Buttons 2.4.2 CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    
    <!-- Google Fonts (Optional) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&display=swap" rel="stylesheet">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }
        
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
/*                font-family:'Noto Sans Bengali',sans-serif;background:#f8f9fa;*/
            background-color: #f8f9fa;
            color: #333;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
        }
        
        .card {
            font-family:'Noto Sans Bengali',sans-serif;background:#f8f9fa;
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }
        
        .btn {
            border-radius: 6px;
            font-weight: 500;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
            border-color: #2980b9;
            transform: translateY(-1px);
        }
        
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            border: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        
        .table tbody tr {
            transition: background-color 0.2s ease;
        }
        
        .table tbody tr:hover {
            background-color: rgba(52, 152, 219, 0.05);
        }
        
        .badge {
            font-weight: 500;
            padding: 5px 10px;
            border-radius: 4px;
        }
        
        .modal-content {
            border-radius: 12px;
            border: none;
        }
        
        .modal-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 12px 12px 0 0;
        }
        
        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid #dee2e6;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(52, 152, 219, 0.25);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
        
        /* DataTables custom styling */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate {
            padding: 15px;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 6px;
            border: 1px solid #dee2e6;
            padding: 6px 12px;
        }
        
        .dt-buttons {
            margin-bottom: 15px;
        }
        
        .dt-buttons .btn {
            margin-right: 5px;
            margin-bottom: 5px;
        }
        
        /* Print optimization */
        @media print {
            .no-print, .dt-buttons, .dataTables_length, .dataTables_filter {
                display: none !important;
            }
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card {
                margin: 10px;
            }
            
            .btn {
                padding: 6px 12px;
                font-size: 0.9rem;
            }
            
            .table-responsive {
                border: none;
            }
        }
    
    /* ... your existing CSS ... */

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
</head>
<body>
    <!-- Optional: Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">
                <i class="fas fa-car me-2"></i>Vehicle Management
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-home me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="report.php">
                            <i class="fas fa-chart-bar me-1"></i> Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container-fluid">