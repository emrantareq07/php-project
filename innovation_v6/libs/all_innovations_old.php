<?php
session_name('innovation_db');
session_start();
require_once("../db/db.php");
$role=$_SESSION['role'];

// Check if user is logged in
if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize filter variables
$filter_fiscal_year = isset($_GET['fiscal_year']) ? mysqli_real_escape_string($conn, $_GET['fiscal_year']) : '';
$filter_status = isset($_GET['idea_status']) ? mysqli_real_escape_string($conn, $_GET['idea_status']) : '';

// Build the WHERE clause based on filters
$where_clause = "WHERE 1=1";

// Add fiscal year filter
if (!empty($filter_fiscal_year)) {
    $where_clause .= " AND fiscal_year = '$filter_fiscal_year'";
}

// Add status filter - handle multiple possible fields
if (!empty($filter_status)) {
    // Check which status field to use based on the selected value
    if ($filter_status == 'submitted idea' || $filter_status == 'primarily selected' || $filter_status == 'final selected') {
        // These are from the 'status' field
        $where_clause .= " AND status = '$filter_status'";
    } else {
        // These are from the 'imple_status' field (বাস্তবায়িত, চলমান)
        $where_clause .= " AND imple_status = '$filter_status'";
    }
}

// Get all innovations with filters
$query = "SELECT * FROM tbl_innovation $where_clause ORDER BY id DESC";
$result = mysqli_query($conn, $query);

// Get distinct fiscal years for filter dropdown
$fiscal_years_query = "SELECT DISTINCT fiscal_year FROM tbl_innovation WHERE fiscal_year IS NOT NULL AND fiscal_year != '' ORDER BY fiscal_year DESC";
$fiscal_years_result = mysqli_query($conn, $fiscal_years_query);

// Function to convert to Bengali numbers (for PDF)
function convertToBengaliNumber($number) {
    $english = array('0','1','2','3','4','5','6','7','8','9');
    $bengali = array('০','১','২','৩','৪','৫','৬','৭','৮','৯');
    return str_replace($english, $bengali, $number);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Innovations - BCIC Innovation Database</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            font-family: 'Hind Siliguri', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 20px;
        }
        
        .container-custom {
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .main-card {
            background: white;
            border-radius: 30px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            color: white;
            border-bottom: none;
        }
        
        .card-header h2 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }
        
        .card-header p {
            margin: 5px 0 0;
            opacity: 0.9;
        }
        
        .card-body {
            padding: 30px;
        }
        
        /* Action Buttons Container */
        .action-buttons-container {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-bottom: 20px;
        }
        
        .btn-print {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(245, 158, 11, 0.4);
            color: white;
        }
        
        .btn-pdf {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .btn-pdf:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4);
            color: white;
        }
        
        /* Filter Section */
        .filter-section {
            background: #f8fafc;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }
        
        .filter-title {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 18px;
        }
        
        .filter-title i {
            color: #667eea;
            margin-right: 10px;
        }
        
        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 15px;
            transition: all 0.3s;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }
        
        .btn-search {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-reset {
            background: #cbd5e0;
            color: #4a5568;
            padding: 12px 30px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s;
            width: 100%;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        
        .btn-reset:hover {
            background: #a0aec0;
            color: white;
        }
        
        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .table {
            width: 100% !important;
            margin: 0;
        }
        
        .table thead th {
            background: #f7fafc;
            color: #2d3748;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
            padding: 15px 10px;
            white-space: nowrap;
        }
        
        .table tbody td {
            padding: 15px 10px;
            vertical-align: middle;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .table tbody tr:hover {
            background: #f7fafc;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            white-space: nowrap;
        }
        
        .status-badge.implemented {
            background: #c6f6d5;
            color: #22543d;
        }
        
        .status-badge.ongoing {
            background: #feebc8;
            color: #744210;
        }
        
        .status-badge.pending {
            background: #fed7d7;
            color: #742a2a;
        }
        
        .status-badge.submitted {
            background: #e9d8fd;
            color: #553c9a;
        }
        
        .status-badge.primary {
            background: #bee3f8;
            color: #2c5282;
        }
        
        .status-badge.final {
            background: #fbb6ce;
            color: #97266d;
        }
        
        /* Action Buttons */
        .btn-view {
            background: #667eea;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
            margin-right: 5px;
        }
        
        .btn-view:hover {
            background: #5a67d8;
            transform: translateY(-2px);
            color: white;
        }
        
        .btn-edit {
            background: #48bb78;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-block;
        }
        
        .btn-edit:hover {
            background: #38a169;
            transform: translateY(-2px);
            color: white;
        }
        
        /* Fiscal Year Badge */
        .fiscal-badge {
            background: #667eea;
            color: white;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            white-space: nowrap;
        }
        
        /* Prize Badge */
        .prize-badge {
            background: #fbbf24;
            color: #92400e;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
                .badge-status {
            padding: 8px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-block;
        }

        .badge-approved {
            background: #c6f6d5;
            color: #22543d;
        }

        .badge-pending {
            background: #feebc8;
            color: #744210;
        }

        .badge-rejected {
            background: #fed7d7;
            color: #742a2a;
        }
        
        /* Active Filter Indicator */
        .active-filters {
            background: #e6f7ff;
            border-left: 4px solid #1890ff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .filter-tag {
            background: #1890ff;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 13px;
            margin-right: 10px;
            display: inline-block;
        }
        
        /* Print Styles */
        @media print {
            .filter-section, .action-buttons-container, .btn-view, .btn-edit, .btn-reset, .btn-search, .card-header a {
                display: none !important;
            }
            
            body {
                background: white;
                padding: 0;
            }
            
            .main-card {
                box-shadow: none;
                border-radius: 0;
            }
            
            .card-header {
                background: #667eea !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .status-badge {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .card-header h2 {
                font-size: 22px;
            }
            
            .table {
                font-size: 13px;
            }
            
            .btn-view, .btn-edit {
                padding: 4px 8px;
                font-size: 11px;
            }
            
            .action-buttons-container {
                flex-direction: column;
            }
            
            .btn-print, .btn-pdf {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class=" container-fluid">
        <div class="main-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h2><i class="fas fa-list me-2"></i>All Innovations</h2>
                    <p>View and manage all submitted innovation ideas</p>
                </div>
                <div>
                    <a href="../dashboard.php" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                
                <!-- Filter Section -->
                <div class="filter-section">
                    <h5 class="filter-title"><i class="fas fa-filter"></i>Filter Innovations</h5>
                    <form method="GET" action="" class="row g-3" id="filterForm">
                        <div class="col-md-5">
                            <label class="form-label">Fiscal Year</label>
                            <select name="fiscal_year" class="form-select">
                                <option value="">All Fiscal Years</option>
                                <?php 
                               /* ===============================
                                   SHOW ALL FISCAL YEARS IN BANGLA
                                =================================*/
                                function convertToBanglaNumber($number) {
                                    $engDigits  = ['0','1','2','3','4','5','6','7','8','9'];
                                    $bangDigits = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
                                    return str_replace($engDigits, $bangDigits, $number);
                                }

                                if ($fiscal_years_result && mysqli_num_rows($fiscal_years_result) > 0) {
                                    while ($fy = mysqli_fetch_assoc($fiscal_years_result)) {
                                        $selected = ($filter_fiscal_year == $fy['fiscal_year']) ? 'selected' : '';

                                        // Convert fiscal year to Bangla numerals for display
                                        $fy_bangla = convertToBanglaNumber($fy['fiscal_year']);

                                        echo "<option value='{$fy['fiscal_year']}' $selected>{$fy_bangla}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-md-5">
                            <label class="form-label">Status</label>
                            <select name="idea_status" class="form-select">
                                <option value="">All Status</option>
                                <option value="submitted idea" <?php echo ($filter_status == 'submitted idea') ? 'selected' : ''; ?>>Submitted Idea</option>
                                <option value="primarily selected" <?php echo ($filter_status == 'primarily selected') ? 'selected' : ''; ?>>Primarily Selected</option>
                                <option value="final selected" <?php echo ($filter_status == 'final selected') ? 'selected' : ''; ?>>Final Selected</option>
                                <option value="বাস্তবায়িত" <?php echo ($filter_status == 'বাস্তবায়িত') ? 'selected' : ''; ?>>বাস্তবায়িত</option>
                                <option value="চলমান" <?php echo ($filter_status == 'চলমান') ? 'selected' : ''; ?>>চলমান</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" name="submit" class="btn-search">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                        </div>
                        
                        <div class="col-md-12 mt-3">
                            <a href="all_innovations.php" class="btn-reset btn btn-danger">
                                <i class="fas fa-undo me-2"></i>Reset Filters
                            </a>
                        </div>
                    </form>
                </div>
                
                <!-- Active Filters Display -->
                <?php if (!empty($filter_fiscal_year) || !empty($filter_status)): ?>
                <div class="active-filters">
                    <strong><i class="fas fa-filter me-2"></i>Active Filters:</strong>
                    <?php if (!empty($filter_fiscal_year)): ?>
                        <span class="filter-tag"><i class="fas fa-calendar me-1"></i><?php echo $filter_fiscal_year; ?></span>
                    <?php endif; ?>
                    <?php if (!empty($filter_status)): ?>
                        <span class="filter-tag"><i class="fas fa-tag me-1"></i><?php echo $filter_status; ?></span>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <!-- Print/PDF Action Buttons -->
                <!-- <div class="action-buttons-container">
                    <button onclick="window.print()" class="btn-print">
                        <i class="fas fa-print"></i> Print Current View
                    </button>
                    <a href="generate_filtered_pdf.php?fiscal_year=<?php echo urlencode($filter_fiscal_year); ?>&idea_status=<?php echo urlencode($filter_status); ?>" 
                       class="btn-pdf" target="_blank">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                </div> -->
                
                <!-- Results Count -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Total Records: <span class="badge bg-primary"><?php echo mysqli_num_rows($result); ?></span></h5>
                    <button onclick="window.print()" class="btn-print">
                        <i class="fas fa-print"></i> Print Current View
                    </button>
                    <a href="generate_filtered_pdf.php?fiscal_year=<?php echo urlencode($filter_fiscal_year); ?>&idea_status=<?php echo urlencode($filter_status); ?>" 
                       class="btn-pdf" target="_blank">
                        <i class="fas fa-file-pdf"></i> Download PDF
                    </a>
                </div>
                
                <!-- Data Table -->
                <div class="table-container">
                    <div class="table-responsive">
                        <table id="innovationsTable" class="table table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Fiscal Year</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Mobile</th>
                                    <th>Workplace</th>
                                    <th>Idea Status</th>
                                    <th>Status</th>
                                    <th>Prize</th>
                                    <th>Rank</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result)) { 
                                        // Determine status class
                                        $status_class = '';
                                        if ($row['imple_status'] == 'বাস্তবায়িত') {
                                            $status_class = 'implemented';
                                        } elseif ($row['imple_status'] == 'চলমান') {
                                            $status_class = 'ongoing';
                                        } elseif ($row['status'] == 'primarily selected') {
                                            $status_class = 'primary';
                                        } elseif ($row['status'] == 'final selected') {
                                            $status_class = 'final';
                                        } else {
                                            $status_class = 'submitted';
                                        }
                                        
                                        // Prize badge
                                        $prize_display = ($row['prize'] == 'yes') ? 'Yes' : 'No';
                                        $prize_amount = !empty($row['prize_amount']) ? $row['prize_amount'] : '-';
                                        $rank = !empty($row['rank']) ? $row['rank'] : '-';
                                        
                                        // Determine which status to display
                                        $display_status = !empty($row['status']) ? $row['status'] : $row['imple_status'];
                                    ?>
                                    <tr>
                                        <td><strong><?php echo $row['id']; ?></strong></td>
                                        <td><?php echo htmlspecialchars(substr($row['title_of_idea'], 0, 250)) . (strlen($row['title_of_idea']) > 30 ? '...' : ''); ?></td>
                                        <td><span class="fiscal-badge"><?php echo convertToBanglaNumber(htmlspecialchars($row['fiscal_year'])); ?></span></td>
                                        <td><?php echo htmlspecialchars(substr($row['fullname'], 0, 100)); ?></td>
                                        <td><?php echo htmlspecialchars(substr($row['designation'], 0, 50)); ?></td>
                                        <td><?php echo htmlspecialchars($row['mobile_no']); ?></td>
                                        <td><?php echo htmlspecialchars(substr($row['place_of_posting'], 0, 50)); ?></td>
                                        <td><?php //echo htmlspecialchars($row['status']);
                                            

                                            $status = $row['status'];

                                            if($status == 'submitted idea'){
                                            echo '<span class="badge-status badge-pending">Submitted</span>';
                                            }
                                            elseif($status == 'primarily selected'){
                                            echo '<span class="badge-status badge-approved text-center">Primarily Selected</span>';
                                            }
                                            elseif($status == 'final selected'){
                                            echo '<span class="badge-status badge-approved text-center">Final Selected</span>';
                                            }
                                            else{
                                            echo '<span class="badge-status badge-rejected text-center">Rejected</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <span class="status-badge <?php echo $status_class; ?>">
                                                <?php echo htmlspecialchars($row['imple_status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($row['prize'] == 'yes'): ?>
                                                <span class="prize-badge">
                                                    <i class="fas fa-trophy me-1"></i><?php echo $prize_amount; ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo $rank; ?></td>
                                        <td>
                                            <a href="view_innovation.php?id=<?php echo $row['id']; ?>" class="btn-view" title="View Details">
                                                <i class="fas fa-eye"></i> 
                                            </a>
                                            <a href="edit_innovation.php?id=<?php echo $row['id']; ?>" class="btn-edit" title="Edit">
                                                <i class="fas fa-edit"></i> 
                                            </a>
                                            <a href="ceate_certificate.php?id=<?php echo $row['id']; ?>" class="btn-certificate" title="Certificate">
                                                <i class="fas fa-book">Create Certificate</i> 
                                            </a>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                <?php else: ?>
                                    <!-- Fixed: Added proper number of columns for empty state -->
                                    <tr>
                                        <td colspan="12" class="text-center py-4">
                                            <i class="fas fa-database fa-3x text-muted mb-3"></i>
                                            <p>No innovations found matching your criteria.</p>
                                            <?php if (!empty($filter_fiscal_year) || !empty($filter_status)): ?>
                                                <a href="all_innovations.php" class="btn btn-sm btn-outline-primary mt-2">
                                                    <i class="fas fa-undo me-1"></i>Clear Filters
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View Details Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Innovation Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    Loading...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Check if table has data before initializing DataTable
            if ($('#innovationsTable tbody tr').length > 0 && $('#innovationsTable tbody tr td').length > 1) {
                $('#innovationsTable').DataTable({
                    "order": [[0, 'desc']],
                    "pageLength": 10,
                    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
                    "language": {
                        "lengthMenu": "Show _MENU_ entries",
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                        "infoEmpty": "Showing 0 to 0 of 0 entries",
                        "infoFiltered": "(filtered from _MAX_ total entries)",
                        "search": "Search:",
                        "zeroRecords": "No matching records found",
                        "paginate": {
                            "first": "First",
                            "last": "Last",
                            "next": '<i class="fas fa-chevron-right"></i>',
                            "previous": '<i class="fas fa-chevron-left"></i>'
                        }
                    },
                    "columnDefs": [
                        { "orderable": false, "targets": 11 } // Disable sorting on actions column
                    ]
                });
            }
            
            // Add loading state to print buttons
            $('.btn-pdf').click(function() {
                $(this).html('<i class="fas fa-spinner fa-spin me-2"></i>Generating...');
            });
        });
        
        // View Details Function
        function viewDetails(id) {
            $('#modalContent').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-3x mb-3"></i><p>Loading...</p></div>');
            $('#viewModal').modal('show');
            
            $.ajax({
                url: 'get_innovation_details.php',
                type: 'POST',
                data: { id: id },
                success: function(response) {
                    $('#modalContent').html(response);
                },
                error: function() {
                    $('#modalContent').html('<div class="alert alert-danger">Error loading details</div>');
                }
            });
        }
    </script>
</body>
</html>