<?php
// view_transport_request.php
session_name('factory_work_request_db');
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: incoming_w_req_transport.php");
    exit;
}

$transport_id = intval($_GET['id']);

$conn = new mysqli('localhost', 'root', '', 'factory_work_request_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user info
$user_id = $_SESSION['user_id'];
$user_division = $_SESSION['division'] ?? '';
$user_section = $_SESSION['section'] ?? '';
$user_full_name = $_SESSION['full_name'] ?? '';
$user_role = $_SESSION['role'] ?? 'user';

// Get complete user info including routine_role
$stmt = $conn->prepare("SELECT routine_role, division, section FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result()->fetch_assoc();
$routine_role = $user_result['routine_role'] ?? '';
$user_db_division = $user_result['division'] ?? $user_division;
$user_db_section = $user_result['section'] ?? $user_section;
$stmt->close();

// Check if user is Transport Section Head or Division Head
$isTransportSectionHead = ($user_db_division === 'Administration Division' && $routine_role === 'section_head' && $user_db_section === 'Transport');
$isTransportDivisionHead = ($user_db_division === 'Administration Division' && $routine_role === 'division_head');

// Get transport request details
$stmt = $conn->prepare("SELECT * FROM transport_w_req_tbl WHERE id = ?");
$stmt->bind_param("i", $transport_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    header("Location: incoming_w_req_transport.php");
    exit;
}

$transport = $result->fetch_assoc();
$stmt->close();

// Check access permissions
$has_access = false;
if ($user_role === 'admin' || $user_role === 'sadmin') {
    $has_access = true;
} elseif ($isTransportSectionHead || $isTransportDivisionHead) {
    $has_access = true;
} elseif ($routine_role === 'user' && isset($transport['user_id']) && $transport['user_id'] == $user_id) {
    $has_access = true;
}

if (!$has_access) {
    $conn->close();
    header("Location: incoming_w_req_transport.php?error=access_denied");
    exit;
}

// Get requester details if available
$requester_info = null;
if (isset($transport['user_id'])) {
    $stmt = $conn->prepare("SELECT full_name, email, emp_id FROM users WHERE id = ?");
    $stmt->bind_param("i", $transport['user_id']);
    $stmt->execute();
    $requester_result = $stmt->get_result();
    if ($requester_result->num_rows > 0) {
        $requester_info = $requester_result->fetch_assoc();
    }
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Transport Request - TR-<?php echo str_pad($transport_id, 6, '0', STR_PAD_LEFT); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .transport-header {
            background-color: #2E7D32;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
        }
        
        .transport-card {
            border: 2px solid #2E7D32;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .status-badge {
            font-size: 0.9rem;
            padding: 5px 10px;
        }
        
        .info-card {
            border-left: 4px solid #4CAF50;
            background-color: #f8fff9;
        }
        
        .section-title {
            color: #2E7D32;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 8px;
            margin-bottom: 20px;
        }
        
        .detail-label {
            font-weight: 600;
            color: #555;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }
        
        .detail-value {
            font-size: 1.1rem;
            color: #333;
        }
        
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline:before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            bottom: 0;
            width: 2px;
            background-color: #4CAF50;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        
        .timeline-item:before {
            content: '';
            position: absolute;
            left: -25px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: #4CAF50;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #4CAF50;
        }
        
        .print-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
        
        @media print {
            .no-print {
                display: none !important;
            }
            
            .print-button {
                display: none !important;
            }
            
            body {
                font-size: 12pt;
            }
            
            .container {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            .transport-header {
                background-color: #2E7D32 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #2E7D32;">
        <div class="container-fluid">
            <a class="navbar-brand" href="incoming_work_requests.php">
                <i class="fas fa-arrow-left me-2"></i>Back to Transport Requests
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 d-none d-md-block">
                    <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($user_full_name); ?>
                </span>
                <span class="badge bg-light text-dark me-3">
                    <?php 
                    if ($isTransportSectionHead) {
                        echo 'Transport Section Head';
                    } elseif ($isTransportDivisionHead) {
                        echo 'Transport Division Head';
                    } elseif ($user_role === 'admin' || $user_role === 'sadmin') {
                        echo 'Administrator';
                    } else {
                        echo 'Requester';
                    }
                    ?>
                </span>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <!-- Transport Request Header -->
        <div class="transport-card mb-4">
            <div class="transport-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="mb-1">
                            <i class="fas fa-car me-2"></i>Transport Request
                        </h2>
                        <h4 class="mb-0">TR-<?php echo str_pad($transport_id, 6, '0', STR_PAD_LEFT); ?></h4>
                        <p class="mb-0 opacity-75">Created on: <?php echo date('F j, Y', strtotime($transport['created_at'])); ?></p>
                    </div>
                    <div class="col-md-4 text-end">
                        <div class="mb-2">
                            <span class="status-badge badge 
                                <?php echo $transport['approval_status'] === 'approved' ? 'bg-success' : 
                                       ($transport['approval_status'] === 'rejected' ? 'bg-danger' : 'bg-warning'); ?>">
                                <?php echo htmlspecialchars(ucfirst($transport['approval_status'] ?? 'pending')); ?> Status
                            </span>
                        </div>
                        <div>
                            <span class="status-badge badge 
                                <?php echo $transport['v_provide_status'] === 'Yes' ? 'bg-success' : 
                                       ($transport['v_provide_status'] === 'No' ? 'bg-danger' : 'bg-warning'); ?>">
                                Vehicle: <?php echo htmlspecialchars($transport['v_provide_status'] ?? 'Pending'); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Alert for urgent/important information -->
                <?php if (strtotime($transport['departure_date']) < strtotime(date('Y-m-d'))): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Important:</strong> This transport request was for a past date (<?php echo date('F j, Y', strtotime($transport['departure_date'])); ?>).
                </div>
                <?php elseif (strtotime($transport['departure_date']) == strtotime(date('Y-m-d'))): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-clock me-2"></i>
                    <strong>Today's Request:</strong> Departure is scheduled for today.
                </div>
                <?php endif; ?>
                
                <!-- Main Information Row -->
                <div class="row g-4 mb-4">
                    <!-- Employee Information -->
                    <div class="col-lg-6">
                        <div class="card h-100 info-card">
                            <div class="card-body">
                                <h5 class="section-title">
                                    <i class="fas fa-user-tie me-2"></i>Employee Information
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="detail-label">Full Name</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($transport['full_name']); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-label">Employee ID</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($transport['emp_id'] ?? 'N/A'); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-label">Designation</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($transport['designation']); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-label">Contact Number</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($transport['contact_no']); ?></div>
                                    </div>
                                    <?php if ($requester_info && isset($requester_info['email'])): ?>
                                    <div class="col-md-12">
                                        <div class="detail-label">Email Address</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($requester_info['email']); ?></div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Transport Details -->
                    <div class="col-lg-6">
                        <div class="card h-100 info-card">
                            <div class="card-body">
                                <h5 class="section-title">
                                    <i class="fas fa-map-marked-alt me-2"></i>Transport Details
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="detail-label">Departure Date</div>
                                        <div class="detail-value">
                                            <?php echo date('F j, Y (l)', strtotime($transport['departure_date'])); ?>
                                            <?php if (strtotime($transport['departure_date']) < strtotime(date('Y-m-d'))): ?>
                                                <span class="badge bg-danger ms-1">Past</span>
                                            <?php elseif (strtotime($transport['departure_date']) == strtotime(date('Y-m-d'))): ?>
                                                <span class="badge bg-warning ms-1">Today</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-label">Time Schedule</div>
                                        <div class="detail-value">
                                            <?php echo date('h:i A', strtotime($transport['start_time'])); ?> - 
                                            <?php echo date('h:i A', strtotime($transport['end_time'])); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-label">Visiting Place</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($transport['visiting_place']); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-label">Destination</div>
                                        <div class="detail-value"><?php echo htmlspecialchars($transport['destination']); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-label">Number of Visitors</div>
                                        <div class="detail-value"><?php echo $transport['no_of_visitor']; ?> person(s)</div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-label">Request Date</div>
                                        <div class="detail-value"><?php echo date('F j, Y', strtotime($transport['date'])); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Vehicle Assignment Section -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header" style="background-color: #e8f5e9;">
                                <h5 class="mb-0">
                                    <i class="fas fa-truck me-2"></i>Vehicle Assignment
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="detail-label">Vehicle Provide Status</div>
                                        <div class="detail-value">
                                            <span class="badge 
                                                <?php echo $transport['v_provide_status'] === 'Yes' ? 'bg-success' : 
                                                       ($transport['v_provide_status'] === 'No' ? 'bg-danger' : 'bg-warning'); ?>">
                                                <?php echo htmlspecialchars($transport['v_provide_status'] ?? 'Pending'); ?>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="detail-label">Driver Name</div>
                                        <div class="detail-value">
                                            <?php echo !empty($transport['driver_name']) ? htmlspecialchars($transport['driver_name']) : '<span class="text-muted">Not assigned</span>'; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="detail-label">Vehicle Number</div>
                                        <div class="detail-value">
                                            <?php echo !empty($transport['vehicle_no']) ? htmlspecialchars($transport['vehicle_no']) : '<span class="text-muted">Not assigned</span>'; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="detail-label">Approval Status</div>
                                        <div class="detail-value">
                                            <span class="badge 
                                                <?php echo $transport['approval_status'] === 'approved' ? 'bg-success' : 
                                                       ($transport['approval_status'] === 'rejected' ? 'bg-danger' : 'bg-warning'); ?>">
                                                <?php echo htmlspecialchars(ucfirst($transport['approval_status'] ?? 'pending')); ?>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($transport['vehicle_exit_time']) || !empty($transport['vehicle_entry_time'])): ?>
                                    <div class="col-md-6">
                                        <div class="detail-label">Vehicle Exit Time</div>
                                        <div class="detail-value">
                                            <?php echo !empty($transport['vehicle_exit_time']) ? date('F j, Y h:i A', strtotime($transport['vehicle_exit_time'])) : '<span class="text-muted">Not recorded</span>'; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-label">Vehicle Entry Time</div>
                                        <div class="detail-value">
                                            <?php echo !empty($transport['vehicle_entry_time']) ? date('F j, Y h:i A', strtotime($transport['vehicle_entry_time'])) : '<span class="text-muted">Not recorded</span>'; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($transport['transport_notes'])): ?>
                                    <div class="col-12">
                                        <div class="detail-label">Transport Notes</div>
                                        <div class="detail-value">
                                            <div class="alert alert-info mb-0">
                                                <?php echo nl2br(htmlspecialchars($transport['transport_notes'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Timeline Section -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header" style="background-color: #f3e5f5;">
                                <h5 class="mb-0">
                                    <i class="fas fa-history me-2"></i>Request Timeline
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div class="timeline-item">
                                        <div class="detail-label">Request Submitted</div>
                                        <div class="detail-value">
                                            <?php echo date('F j, Y, h:i A', strtotime($transport['created_at'])); ?>
                                        </div>
                                        <p class="text-muted mb-0">Transport request was created by employee</p>
                                    </div>
                                    
                                    <?php if (!empty($transport['vehicle_exit_time'])): ?>
                                    <div class="timeline-item">
                                        <div class="detail-label">Vehicle Departed</div>
                                        <div class="detail-value">
                                            <?php echo date('F j, Y, h:i A', strtotime($transport['vehicle_exit_time'])); ?>
                                        </div>
                                        <p class="text-muted mb-0">Vehicle left the premises for the trip</p>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($transport['vehicle_entry_time'])): ?>
                                    <div class="timeline-item">
                                        <div class="detail-label">Vehicle Returned</div>
                                        <div class="detail-value">
                                            <?php echo date('F j, Y, h:i A', strtotime($transport['vehicle_entry_time'])); ?>
                                        </div>
                                        <p class="text-muted mb-0">Vehicle returned to the premises</p>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($transport['updated_at']) && $transport['updated_at'] != $transport['created_at']): ?>
                                    <div class="timeline-item">
                                        <div class="detail-label">Last Updated</div>
                                        <div class="detail-value">
                                            <?php echo date('F j, Y, h:i A', strtotime($transport['updated_at'])); ?>
                                        </div>
                                        <p class="text-muted mb-0">Request details were last updated</p>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Card Footer with Action Buttons -->
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <?php if ($isTransportSectionHead || $isTransportDivisionHead || $user_role === 'admin' || $user_role === 'sadmin'): ?>
                        <button type="button" class="btn btn-success me-2" 
                                onclick="window.location.href='incoming_w_req_transport.php?status=<?php echo $transport['approval_status'] === 'approved' ? 'complete' : 'incomplete'; ?>'">
                            <i class="fas fa-list me-1"></i>Back to List
                        </button>
                        
                        <button type="button" class="btn btn-primary me-2"
                                onclick="openUpdateModal()">
                            <i class="fas fa-edit me-1"></i>Update Request
                        </button>
                        <?php endif; ?>
                        
                        <?php if ($user_role === 'admin' || $user_role === 'sadmin'): ?>
                        <button type="button" class="btn btn-danger"
                                onclick="confirmDelete()">
                            <i class="fas fa-trash me-1"></i>Delete Request
                        </button>
                        <?php endif; ?>
                    </div>
                    
                    <div>
                        <button type="button" class="btn btn-outline-secondary no-print" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Information -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Request Summary</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">
                            Transport request for <strong><?php echo htmlspecialchars($transport['full_name']); ?></strong> 
                            to visit <strong><?php echo htmlspecialchars($transport['visiting_place']); ?></strong> 
                            at <strong><?php echo htmlspecialchars($transport['destination']); ?></strong> 
                            on <strong><?php echo date('F j, Y', strtotime($transport['departure_date'])); ?></strong>.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Status Summary</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="detail-label">Current Status</div>
                                <div class="detail-value">
                                    <span class="badge 
                                        <?php echo $transport['approval_status'] === 'approved' ? 'bg-success' : 
                                               ($transport['approval_status'] === 'rejected' ? 'bg-danger' : 'bg-warning'); ?>">
                                        <?php echo htmlspecialchars(ucfirst($transport['approval_status'] ?? 'pending')); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="detail-label">Vehicle Status</div>
                                <div class="detail-value">
                                    <span class="badge 
                                        <?php echo $transport['v_provide_status'] === 'Yes' ? 'bg-success' : 
                                               ($transport['v_provide_status'] === 'No' ? 'bg-danger' : 'bg-warning'); ?>">
                                        <?php echo htmlspecialchars($transport['v_provide_status'] ?? 'Pending'); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Print Button (Fixed) -->
    <button type="button" class="btn btn-primary print-button no-print" onclick="window.print()">
        <i class="fas fa-print me-2"></i>Print Request
    </button>
    
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function openUpdateModal() {
            // Redirect to update page or open modal
            window.location.href = 'update_transport_request.php?id=<?php echo $transport_id; ?>';
        }
        
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this transport request?\n\nTR-<?php echo str_pad($transport_id, 6, '0', STR_PAD_LEFT); ?>\nEmployee: <?php echo htmlspecialchars($transport['full_name']); ?>\n\nThis action cannot be undone.')) {
                window.location.href = 'delete_transport_request.php?id=<?php echo $transport_id; ?>';
            }
        }
        
        // Print-specific setup
        document.addEventListener('DOMContentLoaded', function() {
            // Add print stylesheet dynamically if needed
            if (!document.querySelector('link[media="print"]')) {
                const printCSS = document.createElement('link');
                printCSS.rel = 'stylesheet';
                printCSS.media = 'print';
                printCSS.href = '';
                document.head.appendChild(printCSS);
            }
        });
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+P or Cmd+P for print
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            
            // Escape to go back
            if (e.key === 'Escape') {
                window.history.back();
            }
        });
    </script>
</body>
</html>