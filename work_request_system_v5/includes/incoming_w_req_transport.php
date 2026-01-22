<?php
// incoming_work_requests.php - FOCUS ON TRANSPORT REQUESTS ONLY
session_name('factory_work_request_db');
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'factory_work_request_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user info from session and database
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

// Handle filters - focus on transport
$status_filter = $_GET['status'] ?? 'all';
$view_type = $_GET['view'] ?? 'incoming';

// Initialize variables
$transport_requests = [];
$access_granted = false;
$access_reason = '';
$transport_sql_query = '';
$transport_params = [];
$transport_types = '';

// Check if user is Transport Section Head or Division Head
$isTransportSectionHead = ($user_db_division === 'Administration Division' && $routine_role === 'section_head' && $user_db_section === 'Transport');
$isTransportDivisionHead = ($user_db_division === 'Administration Division' && $routine_role === 'division_head');

// DYNAMIC ACCESS LOGIC FOR TRANSPORT REQUESTS ONLY
if ($routine_role === 'section_head') {
    if ($isTransportSectionHead) {
        // Transport Section Head - Show only transport requests
        $transport_sql_query = "SELECT * FROM transport_w_req_tbl WHERE 1=1";
        
        // Filter by approval status if not 'all'
        if ($status_filter !== 'all') {
            if ($status_filter === 'complete') {
                $transport_sql_query .= " AND approval_status = 'approved'";
            } elseif ($status_filter === 'incomplete') {
                $transport_sql_query .= " AND (approval_status IS NULL OR approval_status = 'pending')";
            }
        }
        
        $access_granted = true;
        $access_reason = "Transport Section Head of 'Transport' section";
        
    } else {
        // Other Section Heads - No access to transport requests
        $access_granted = false;
        $access_reason = "Section Head of '" . htmlspecialchars($user_db_section) . "' section (No Transport access)";
    }
    
} elseif ($routine_role === 'division_head') {
    if ($isTransportDivisionHead) {
        // Transport Division Head - Show all transport requests
        $transport_sql_query = "SELECT * FROM transport_w_req_tbl WHERE 1=1";
        
        // Filter by approval status if not 'all'
        if ($status_filter !== 'all') {
            if ($status_filter === 'complete') {
                $transport_sql_query .= " AND approval_status = 'approved'";
            } elseif ($status_filter === 'incomplete') {
                $transport_sql_query .= " AND (approval_status IS NULL OR approval_status = 'pending')";
            }
        }
        
        $access_granted = true;
        $access_reason = "Transport Division Head";
        
    } else {
        // Other Division Heads - No access to transport requests
        $access_granted = false;
        $access_reason = "Division Head of '" . htmlspecialchars($user_db_division) . "' division (No Transport access)";
    }
    
} elseif ($user_role === 'admin' || $user_role === 'sadmin') {
    // Admin can see transport requests
    $transport_sql_query = "SELECT * FROM transport_w_req_tbl WHERE 1=1";
    
    // Filter by approval status if not 'all'
    if ($status_filter !== 'all') {
        if ($status_filter === 'complete') {
            $transport_sql_query .= " AND approval_status = 'approved'";
        } elseif ($status_filter === 'incomplete') {
            $transport_sql_query .= " AND (approval_status IS NULL OR approval_status = 'pending')";
        }
    }
    
    $access_granted = true;
    $access_reason = "Administrator";
    
} else {
    // REGULAR USER: Can only see their own transport requests
    $transport_sql_query = "SELECT * FROM transport_w_req_tbl WHERE user_id = ?";
    $transport_params[] = $user_id;
    $transport_types .= 'i';
    
    // Filter by approval status if not 'all'
    if ($status_filter !== 'all') {
        if ($status_filter === 'complete') {
            $transport_sql_query .= " AND approval_status = 'approved'";
        } elseif ($status_filter === 'incomplete') {
            $transport_sql_query .= " AND (approval_status IS NULL OR approval_status = 'pending')";
        }
    }
    
    $access_granted = true;
    $access_reason = "Your Transport Requests";
}

// Execute transport query if access granted
if ($access_granted && !empty($transport_sql_query)) {
    $transport_sql_query .= " ORDER BY 
        CASE 
            WHEN departure_date < CURDATE() THEN 1
            WHEN departure_date = CURDATE() THEN 2
            ELSE 3
        END,
        created_at DESC";
    
    $transport_stmt = $conn->prepare($transport_sql_query);
    if (!empty($transport_params)) {
        $transport_stmt->bind_param($transport_types, ...$transport_params);
    }
    $transport_stmt->execute();
    $transport_result = $transport_stmt->get_result();
    $transport_requests = $transport_result->fetch_all(MYSQLI_ASSOC);
    $transport_stmt->close();
}

// Get transport statistics
$complete_count = 0;
$incomplete_count = 0;
$total_all_requests = 0;

if ($access_granted) {
    // Count transport requests
    $transport_stats_sql = "SELECT 
        COUNT(*) as total_all,
        SUM(CASE WHEN approval_status = 'approved' THEN 1 ELSE 0 END) as total_complete,
        SUM(CASE WHEN approval_status IS NULL OR approval_status = 'pending' THEN 1 ELSE 0 END) as total_incomplete
        FROM transport_w_req_tbl WHERE 1=1";
    
    // Add conditions for regular users
    if ($routine_role === 'user' && !($user_role === 'admin' || $user_role === 'sadmin')) {
        $transport_stats_sql .= " AND user_id = " . intval($user_id);
    }
    
    $transport_stats_result = $conn->query($transport_stats_sql);
    $transport_stats = $transport_stats_result->fetch_assoc();
    
    $complete_count = $transport_stats['total_complete'] ?? 0;
    $incomplete_count = $transport_stats['total_incomplete'] ?? 0;
    $total_all_requests = $transport_stats['total_all'] ?? 0;
}

$conn->close();

$display_requests = $transport_requests;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transport Work Requests</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        .badge-transport {
            background-color: #4CAF50;
            color: white;
        }
        
        .badge-pending {
            background-color: #ff9800;
            color: white;
        }
        
        .badge-approved {
            background-color: #2196F3;
            color: white;
        }
        
        .badge-rejected {
            background-color: #f44336;
            color: white;
        }
        
        .action-transport {
            background-color: #4CAF50;
            color: white;
        }
        
        .action-transport:hover {
            background-color: #388E3C;
            color: white;
        }
        
        /* Modal styles */
        .transport-modal .modal-header {
            background-color: #4CAF50;
            color: white;
        }
        
        .transport-field {
            border-left: 3px solid #4CAF50;
        }
        
        .navbar-custom {
            background-color: #2E7D32;
        }
        
        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }
        
        .empty-state-icon {
            color: #d1d1d1;
            margin-bottom: 20px;
        }
        
        .user-role-badge {
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
        }
        
        .role-section-head { background-color: #4CAF50; color: white; }
        .role-division-head { background-color: #2196F3; color: white; }
        .role-admin { background-color: #9C27B0; color: white; }
        .role-user { background-color: #757575; color: white; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="fas fa-car me-2"></i>Transport Request System
            </a>
            <div class="d-flex align-items-center">
                <a href="dashboard.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-home me-1"></i>Home
                </a>
                <span class="text-white me-3 d-none d-md-block">
                    <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($user_full_name); ?>
                </span>
                <a href="logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar Filters -->
            <div class="col-lg-2 col-md-3">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Filters</h6>
                    </div>
                    <div class="card-body">
                        <form id="filterForm" method="GET">
                            <!-- Status Filter -->
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" onchange="document.getElementById('filterForm').submit()">
                                    <option value="all" <?php echo $status_filter === 'all' ? 'selected' : ''; ?>>All Status</option>
                                    <option value="complete" <?php echo $status_filter === 'complete' ? 'selected' : ''; ?>>Approved/Complete</option>
                                    <option value="incomplete" <?php echo $status_filter === 'incomplete' ? 'selected' : ''; ?>>Pending/Incomplete</option>
                                </select>
                            </div>
                            
                            <!-- View Type Filter -->
                            <div class="mb-3">
                                <label class="form-label">View Type</label>
                                <select class="form-select" name="view" onchange="document.getElementById('filterForm').submit()">
                                    <option value="incoming" <?php echo $view_type === 'incoming' ? 'selected' : ''; ?>>Incoming Requests</option>
                                    <?php if ($routine_role === 'user'): ?>
                                    <option value="my_requests" <?php echo $view_type === 'my_requests' ? 'selected' : ''; ?>>My Requests</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            
                            <!-- Statistics -->
                            <div class="mt-4 pt-3 border-top">
                                <h6 class="mb-3"><i class="fas fa-chart-bar me-2"></i>Statistics</h6>
                                <div class="row text-center">
                                    <div class="col-6 mb-2">
                                        <div class="stat-number"><?php echo $total_all_requests; ?></div>
                                        <div class="stat-label">Total</div>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <div class="stat-number text-success"><?php echo $complete_count; ?></div>
                                        <div class="stat-label">Complete</div>
                                    </div>
                                    <div class="col-6 mb-2">
                                        <div class="stat-number text-warning"><?php echo $incomplete_count; ?></div>
                                        <div class="stat-label">Pending</div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            <div class="col-lg-10 col-md-9">
                <!-- Page Header -->
                <?php if ($isTransportSectionHead || $isTransportDivisionHead): ?>
                <div class="alert alert-success mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-car fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-1">Transport Request Management</h5>
                            <p class="mb-0">
                                You are viewing and managing <strong>Transport Requests</strong>.
                                <?php if ($status_filter === 'incomplete'): ?>
                                    Showing only <span class="badge bg-warning">Pending/Incomplete</span> requests.
                                <?php elseif ($status_filter === 'complete'): ?>
                                    Showing only <span class="badge bg-success">Approved/Complete</span> requests.
                                <?php else: ?>
                                    Showing <span class="badge bg-info">All Transport Requests</span>.
                                <?php endif; ?>
                                <br>
                                You can update vehicle details and approval status.
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- User Info Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>Your Access Level</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="detail-label text-muted mb-1">Your Role</div>
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold"><?php echo ucfirst(str_replace('_', ' ', $routine_role ?: 'user')); ?></span>
                                    <span class="user-role-badge role-<?php echo str_replace('_', '-', $routine_role ?: 'user'); ?> ms-2">
                                        <?php echo ucfirst(str_replace('_', ' ', $routine_role ?: 'user')); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="detail-label text-muted mb-1">Your Division</div>
                                <div class="fw-bold"><?php echo htmlspecialchars($user_division); ?></div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="detail-label text-muted mb-1">Your Section</div>
                                <div class="fw-bold"><?php echo htmlspecialchars($user_section); ?></div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="detail-label text-muted mb-1">Access Level</div>
                                <div class="fw-bold">
                                    <?php 
                                    if ($isTransportSectionHead) {
                                        echo '<span class="badge bg-success">Transport Section Head</span>';
                                    } elseif ($isTransportDivisionHead) {
                                        echo '<span class="badge bg-success">Transport Division Head</span>';
                                    } elseif ($user_role === 'admin' || $user_role === 'sadmin') {
                                        echo '<span class="badge bg-danger">Administrator</span>';
                                    } elseif ($routine_role === 'user') {
                                        echo '<span class="badge bg-secondary">Regular User</span>';
                                    } else {
                                        echo '<span class="badge bg-warning">Limited Access</span>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="text-muted small">
                            <i class="fas fa-info-circle me-1"></i>
                            <?php echo $access_reason; ?>
                            <?php if ($isTransportSectionHead || $isTransportDivisionHead): ?>
                                - You can update vehicle details and approval status
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Transport Requests Table -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-car me-2"></i>
                            Transport Requests
                            <span class="badge bg-primary ms-2"><?php echo count($display_requests); ?></span>
                        </h5>
                        <?php if ($access_granted && ($user_role === 'admin' || $user_role === 'sadmin' || $routine_role === 'user')): ?>
                        <a href="create_transport_request.php" class="btn btn-success btn-sm">
                            <i class="fas fa-plus me-1"></i>New Transport Request
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($display_requests)): ?>
                            <div class="empty-state py-5">
                                <div class="empty-state-icon">
                                    <i class="fas fa-car fa-4x"></i>
                                </div>
                                <h3 class="mt-4 mb-3">No transport requests found</h3>
                                <p class="text-muted mb-4">
                                    <?php 
                                    if (!$access_granted) {
                                        echo 'You do not have access to view transport requests.';
                                    } else {
                                        echo 'No transport requests found with current filters.';
                                    }
                                    ?>
                                </p>
                                <?php if ($access_granted): ?>
                                <button onclick="clearFilters()" class="btn btn-secondary">
                                    <i class="fas fa-filter me-1"></i>Clear Filters
                                </button>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Transport ID</th>
                                            <th>Request Date</th>
                                            <th>Employee ID</th>
                                            <th>Employee Name</th>
                                            <th>Contact No</th>
                                            <th>Departure Date</th>
                                            <th>Time</th>
                                            <th>Visiting Place</th>
                                            <th>Destination</th>
                                            <th>Visitors</th>
                                            <th>Vehicle Status</th>
                                            <th>Approval Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($display_requests as $request): ?>
                                            <tr>
                                                <td>
                                                    <strong class="d-block">TR-<?php echo str_pad($request['id'], 6, '0', STR_PAD_LEFT); ?></strong>
                                                    <small class="text-muted">#<?php echo $request['id']; ?></small>
                                                </td>
                                                <td><?php echo date('d/m/Y', strtotime($request['date'])); ?></td>
                                                <td><?php echo htmlspecialchars($request['emp_id'] ?? 'N/A'); ?></td>
                                                <td>
                                                    <div class="fw-medium"><?php echo htmlspecialchars($request['full_name']); ?></div>
                                                    <small class="text-muted"><?php echo htmlspecialchars($request['designation']); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($request['contact_no']); ?></td>
                                                <td>
                                                    <?php echo date('d/m/Y', strtotime($request['departure_date'])); ?>
                                                    <?php if (strtotime($request['departure_date']) < strtotime(date('Y-m-d'))): ?>
                                                        <span class="badge bg-danger ms-1" title="Past due">Past</span>
                                                    <?php elseif (strtotime($request['departure_date']) == strtotime(date('Y-m-d'))): ?>
                                                        <span class="badge bg-warning ms-1" title="Today">Today</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small><?php echo date('h:i A', strtotime($request['start_time'])); ?> - <?php echo date('h:i A', strtotime($request['end_time'])); ?></small>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 120px;" title="<?php echo htmlspecialchars($request['visiting_place']); ?>">
                                                        <?php echo htmlspecialchars($request['visiting_place']); ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 120px;" title="<?php echo htmlspecialchars($request['destination']); ?>">
                                                        <?php echo htmlspecialchars($request['destination']); ?>
                                                    </div>
                                                </td>
                                                <td><?php echo $request['no_of_visitor']; ?></td>
                                                <td>
                                                    <span class="badge 
                                                        <?php echo $request['v_provide_status'] === 'Yes' ? 'bg-success' : 
                                                               ($request['v_provide_status'] === 'No' ? 'bg-danger' : 'bg-warning'); ?>">
                                                        <?php echo htmlspecialchars($request['v_provide_status'] ?? 'Pending'); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge 
                                                        <?php echo $request['approval_status'] === 'Approved' ? 'bg-success' : 
                                                               ($request['approval_status'] === 'Rejected' ? 'bg-danger' : 'bg-warning'); ?>">
                                                        <?php echo htmlspecialchars($request['approval_status'] ?? 'Pending'); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <?php if ($isTransportSectionHead || $isTransportDivisionHead || $user_role === 'admin' || $user_role === 'sadmin'): ?>
                                                            <button type="button" class="btn btn-sm btn-success me-2" 
                                                                    onclick="openTransportModal(<?php echo htmlspecialchars(json_encode($request)); ?>)"
                                                                    title="Update Transport Details">
                                                                <i class="fas fa-edit"></i> Update
                                                            </button>
                                                        <?php endif; ?>
                                                        
                                                        <a href="view_transport_request.php?id=<?php echo $request['id']; ?>" 
                                                           class="btn btn-sm btn-info me-2" title="View Details">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>

                                                          <!-- Add this print button -->
                                                        <a href="print_transport_request.php?id=<?php echo $request['id']; ?>" 
                                                           class="btn btn-sm btn-danger me-2" title="Print Details" target="_blank">
                                                            <i class="fas fa-print"></i> Print
                                                        </a>
                                                        
                                                        <?php if ($user_role === 'admin' || $user_role === 'sadmin'): ?>
                                                            <a href="delete_transport_request.php?id=<?php echo $request['id']; ?>" 
                                                               class="btn btn-sm btn-danger" title="Delete"
                                                               onclick="return confirm('Are you sure you want to delete this transport request?')">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transport Update Modal -->
    <div class="modal fade" id="updateTransportModal" tabindex="-1" aria-labelledby="updateTransportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content transport-modal">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateTransportModalLabel">
                        <i class="fas fa-car me-2"></i>Update Transport Request
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateTransportForm" method="POST" action="update_transport_request.php">
                        <input type="hidden" name="transport_id" id="transport_id">
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Vehicle Provide Status</label>
                                <select class="form-select transport-field" name="v_provide_status" id="v_provide_status" required>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                    <option value="Pending">Pending</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Approval Status</label>
                                <select class="form-select transport-field" name="approval_status" id="approval_status" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Driver Name</label>
                                <input type="text" class="form-control transport-field" name="driver_name" id="driver_name">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Vehicle Number</label>
                                <input type="text" class="form-control transport-field" name="vehicle_no" id="vehicle_no">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Vehicle Exit Time</label>
                                <input type="datetime-local" class="form-control transport-field" name="vehicle_exit_time" id="vehicle_exit_time">
                            </div>
                            
                            <div class="col-md-6">
                                <label class="form-label">Vehicle Entry Time</label>
                                <input type="datetime-local" class="form-control transport-field" name="vehicle_entry_time" id="vehicle_entry_time">
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label">Additional Notes</label>
                                <textarea class="form-control transport-field" name="transport_notes" id="transport_notes" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Update Transport Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript for Transport Modal -->
    <script>
        function openTransportModal(request) {
            // Populate modal fields
            document.getElementById('transport_id').value = request.id;
            document.getElementById('v_provide_status').value = request.v_provide_status || 'Pending';
            document.getElementById('approval_status').value = request.approval_status || 'Pending';
            document.getElementById('driver_name').value = request.driver_name || '';
            document.getElementById('vehicle_no').value = request.vehicle_no || '';
            
            // Format dates for datetime-local input
            if (request.vehicle_exit_time) {
                const exitDate = new Date(request.vehicle_exit_time);
                document.getElementById('vehicle_exit_time').value = exitDate.toISOString().slice(0, 16);
            }
            
            if (request.vehicle_entry_time) {
                const entryDate = new Date(request.vehicle_entry_time);
                document.getElementById('vehicle_entry_time').value = entryDate.toISOString().slice(0, 16);
            }
            
            document.getElementById('transport_notes').value = request.transport_notes || '';
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('updateTransportModal'));
            modal.show();
        }
        
        // Form validation
        document.getElementById('updateTransportForm').addEventListener('submit', function(e) {
            const approvalStatus = document.getElementById('approval_status').value;
            const driverName = document.getElementById('driver_name').value;
            const vehicleNo = document.getElementById('vehicle_no').value;
            
            // If approved, require driver name and vehicle number
            if (approvalStatus === 'approved' && (!driverName.trim() || !vehicleNo.trim())) {
                e.preventDefault();
                alert('For approved requests, Driver Name and Vehicle Number are required.');
                return false;
            }
            
            return true;
        });
        
        function clearFilters() {
            window.location.href = 'incoming_w_req_transport.php';
        }
    </script>
</body>
</html>