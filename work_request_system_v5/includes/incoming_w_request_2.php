<?php
// incoming_work_request.php - SIMPLE VERSION
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

// Get user info
$user_id = $_SESSION['user_id'];
$user_division = $_SESSION['division'] ?? '';
$user_section = $_SESSION['section'] ?? '';

// Get routine_role from users table
$stmt = $conn->prepare("SELECT routine_role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$routine_role = $result['routine_role'] ?? '';
$stmt->close();

// Check if user is IT division section_head
if (strtoupper($user_division) === 'IT' && $routine_role === 'section_head') {
    
    // SHOW ALL ICT INCOMPLETE REQUESTS FROM work_request_tbl
    $query = "SELECT * FROM work_request_tbl 
              WHERE LOWER(w_req_type) = 'ict' 
              AND w_com_status = 'incomplete' 
              ORDER BY created_at DESC";
    
    $result = $conn->query($query);
    $requests = $result->fetch_all(MYSQLI_ASSOC);
    
} else {
    // For other users, show empty or different view
    $requests = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICT Incomplete Requests</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 1200px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .user-info { background: #e9ecef; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .info-item { margin: 5px 0; }
        .info-label { font-weight: bold; color: #555; }
        .info-value { color: #333; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-it { background: #007bff; color: white; }
        .badge-section { background: #28a745; color: white; }
        .badge-ict { background: #17a2b8; color: white; }
        .badge-incomplete { background: #dc3545; color: white; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th { background: #343a40; color: white; padding: 12px; text-align: left; }
        .table td { padding: 10px; border-bottom: 1px solid #ddd; }
        .table tr:hover { background: #f8f9fa; }
        .empty-state { text-align: center; padding: 40px; color: #6c757d; }
        .empty-state h3 { color: #495057; }
        .urgent { color: #dc3545; font-weight: bold; }
        .normal { color: #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 ICT Incomplete Work Requests</h1>
        
        <div class="user-info">
            <h3>👤 Your Information</h3>
            <div class="info-item">
                <span class="info-label">User ID:</span>
                <span class="info-value"><?php echo htmlspecialchars($user_id); ?></span>
            </div>
            <div class="info-item">
                <span class="info-label">Division:</span>
                <span class="info-value">
                    <?php echo htmlspecialchars($user_division); ?>
                    <?php if (strtoupper($user_division) === 'IT'): ?>
                        <span class="badge badge-it">IT Division</span>
                    <?php endif; ?>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Section:</span>
                <span class="info-value">
                    <?php echo htmlspecialchars($user_section); ?>
                    <span class="badge badge-section">Section</span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Role:</span>
                <span class="info-value">
                    <?php echo htmlspecialchars($routine_role); ?>
                    <?php if ($routine_role === 'section_head'): ?>
                        <span style="color: #28a745;">✓ Section Head</span>
                    <?php endif; ?>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Viewing:</span>
                <span class="info-value">
                    <?php if (strtoupper($user_division) === 'IT' && $routine_role === 'section_head'): ?>
                        <span class="badge badge-ict">ICT</span> + 
                        <span class="badge badge-incomplete">Incomplete</span> Requests
                    <?php else: ?>
                        No special access
                    <?php endif; ?>
                </span>
            </div>
        </div>
        
        <?php if (strtoupper($user_division) === 'IT' && $routine_role === 'section_head'): ?>
            
            <h3>📊 Found <?php echo count($requests); ?> ICT Incomplete Request(s)</h3>
            
            <?php if (empty($requests)): ?>
                <div class="empty-state">
                    <h3>📭 No ICT Incomplete Requests Found</h3>
                    <p>There are currently no ICT requests with 'incomplete' status in the database.</p>
                    <p><small>Check if any ICT requests exist and if their status is 'incomplete'.</small></p>
                </div>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Urgency</th>
                            <th>Concerned Division</th>
                            <th>Concerned Section</th>
                            <th>Location</th>
                            <th>Requester</th>
                            <th>Created Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($requests as $request): ?>
                        <tr>
                            <td><strong>WR-<?php echo str_pad($request['id'], 6, '0', STR_PAD_LEFT); ?></strong></td>
                            <td><span class="badge badge-ict"><?php echo htmlspecialchars($request['w_req_type']); ?></span></td>
                            <td><span class="badge badge-incomplete"><?php echo htmlspecialchars($request['w_com_status']); ?></span></td>
                            <td class="<?php echo ($request['status'] === 'urgent' || $request['status'] === 'very urgent') ? 'urgent' : 'normal'; ?>">
                                <?php echo htmlspecialchars($request['status']); ?>
                            </td>
                            <td><?php echo htmlspecialchars($request['w_com_division']); ?></td>
                            <td><?php echo htmlspecialchars($request['w_com_section']); ?></td>
                            <td><?php echo htmlspecialchars(substr($request['w_location'], 0, 50)); ?><?php echo strlen($request['w_location']) > 50 ? '...' : ''; ?></td>
                            <td><?php echo htmlspecialchars($request['full_name']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($request['created_at'])); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- DEBUG INFO -->
                <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-left: 4px solid #007bff; font-size: 14px;">
                    <h4>ℹ️ Database Information</h4>
                    <?php
                    // Reconnect to show debug info
                    $conn2 = new mysqli('localhost', 'root', '', 'factory_work_request_db');
                    // Count all ICT requests
                    $total_ict = $conn2->query("SELECT COUNT(*) as count FROM work_request_tbl WHERE LOWER(w_req_type) = 'ict'")->fetch_assoc()['count'];
                    // Count ICT incomplete
                    $ict_incomplete = $conn2->query("SELECT COUNT(*) as count FROM work_request_tbl WHERE LOWER(w_req_type) = 'ict' AND w_com_status = 'incomplete'")->fetch_assoc()['count'];
                    // Count ICT complete
                    $ict_complete = $conn2->query("SELECT COUNT(*) as count FROM work_request_tbl WHERE LOWER(w_req_type) = 'ict' AND w_com_status = 'complete'")->fetch_assoc()['count'];
                    $conn2->close();
                    ?>
                    <p><strong>Total ICT requests in database:</strong> <?php echo $total_ict; ?></p>
                    <p><strong>ICT Incomplete:</strong> <?php echo $ict_incomplete; ?></p>
                    <p><strong>ICT Complete:</strong> <?php echo $ict_complete; ?></p>
                    <p><strong>Showing:</strong> All ICT incomplete requests (no division/section filter)</p>
                </div>
            <?php endif; ?>
            
        <?php else: ?>
            <div class="empty-state">
                <h3>⛔ Access Restricted</h3>
                <p>You need to be an <strong>IT Division Section Head</strong> to view this page.</p>
                <p><strong>Your current access:</strong></p>
                <ul>
                    <li>Division: <?php echo htmlspecialchars($user_division); ?> (needs to be: IT)</li>
                    <li>Role: <?php echo htmlspecialchars($routine_role); ?> (needs to be: section_head)</li>
                </ul>
                <p>Contact administrator if you need access.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>