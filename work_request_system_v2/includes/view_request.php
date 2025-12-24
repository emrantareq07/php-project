<?php
// view_request.php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: work_requests_list.php");
    exit;
}

$request_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? 'user';

// Include database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'factory_work_request_db';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get request details
$sql = "SELECT * FROM work_request_tbl WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $request_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    header("Location: work_requests_list.php");
    exit;
}

$request = $result->fetch_assoc();
$stmt->close();

// Check if user has permission to view
if ($user_role !== 'admin' && $user_role !== 'sadmin' && $request['requester_id'] != $user_id) {
    $conn->close();
    header("Location: work_requests_list.php");
    exit;
}

// Get request history if any
$history_sql = "SELECT * FROM work_request_history WHERE work_request_id = ? ORDER BY created_at DESC";
$history_stmt = $conn->prepare($history_sql);
$history_stmt->bind_param("i", $request_id);
$history_stmt->execute();
$history_result = $history_stmt->get_result();
$history = $history_result->fetch_all(MYSQLI_ASSOC);
$history_stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Work Request</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            margin-bottom: 20px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header h1 {
            font-size: 24px;
        }

        .request-id {
            background: rgba(255,255,255,0.2);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 18px;
        }

        .content {
            background: white;
            padding: 30px;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .detail-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
        }

        .detail-section h3 {
            color: #333;
            margin-bottom: 15px;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .detail-row {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e1e5eb;
        }

        .detail-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #666;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #333;
            font-size: 16px;
        }

        .badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-complete {
            background: #d4edda;
            color: #155724;
        }

        .badge-incomplete {
            background: #fff3cd;
            color: #856404;
        }

        .badge-normal {
            background: #d1ecf1;
            color: #0c5460;
        }

        .badge-urgent {
            background: #ffeaa7;
            color: #856404;
        }

        .badge-very-urgent {
            background: #f8d7da;
            color: #721c24;
        }

        .badge-type {
            background: #e2e3e5;
            color: #383d41;
        }

        .description-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }

        .remarks-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #6c757d;
            font-style: italic;
        }

        .history-section {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e1e5eb;
        }

        .history-item {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            border-left: 4px solid #ffc107;
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .history-date {
            font-weight: 600;
            color: #333;
        }

        .history-status {
            font-size: 14px;
        }

        .history-details {
            color: #666;
            font-size: 14px;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e1e5eb;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-secondary {
            background: #f0f0f0;
            color: #333;
        }

        .btn-back {
            background: #6c757d;
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .timestamps {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e1e5eb;
            color: #666;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
                justify-content: center;
            }
            
            .timestamps {
                flex-direction: column;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="header-content">
                <div>
                    <h1>Work Request Details</h1>
                    <p>Request Information and Status</p>
                </div>
                <div class="request-id">
                    WR-<?php echo str_pad($request['id'], 6, '0', STR_PAD_LEFT); ?>
                </div>
            </div>
        </div>
        
        <div class="content">
            <div class="details-grid">
                <div class="detail-section">
                    <h3>Request Information</h3>
                    <div class="detail-row">
                        <div class="detail-label">Request Date</div>
                        <div class="detail-value"><?php echo date('F j, Y', strtotime($request['date'])); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Request Type</div>
                        <div class="detail-value">
                            <span class="badge badge-type">
                                <?php echo $request['w_req_type']; ?>
                            </span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Urgency Level</div>
                        <div class="detail-value">
                            <span class="badge badge-<?php echo str_replace(' ', '-', $request['status']); ?>">
                                <?php echo ucfirst($request['status']); ?>
                            </span>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Completion Status</div>
                        <div class="detail-value">
                            <span class="badge badge-<?php echo $request['w_com_status']; ?>">
                                <?php echo ucfirst($request['w_com_status']); ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>Location & Contact</h3>
                    <div class="detail-row">
                        <div class="detail-label">Work Location</div>
                        <div class="detail-value"><?php echo htmlspecialchars($request['w_location']); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Concerned Division</div>
                        <div class="detail-value"><?php echo htmlspecialchars($request['w_com_division']); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Concerned Section</div>
                        <div class="detail-value"><?php echo htmlspecialchars($request['w_com_section']); ?></div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h3>Requester Information</h3>
                    <div class="detail-row">
                        <div class="detail-label">Full Name</div>
                        <div class="detail-value"><?php echo htmlspecialchars($request['full_name']); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Designation</div>
                        <div class="detail-value"><?php echo htmlspecialchars($request['designation']); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Division</div>
                        <div class="detail-value"><?php echo htmlspecialchars($request['division']); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Section</div>
                        <div class="detail-value"><?php echo htmlspecialchars($request['section']); ?></div>
                    </div>
                </div>
            </div>
            
            <div class="description-box">
                <h3>Work Description</h3>
                <div style="margin-top: 10px; white-space: pre-wrap; line-height: 1.6;">
                    <?php echo htmlspecialchars($request['w_description']); ?>
                </div>
            </div>
            
            <?php if (!empty($request['remarks'])): ?>
                <div class="remarks-box">
                    <h3>Remarks</h3>
                    <div style="margin-top: 10px; white-space: pre-wrap; line-height: 1.6;">
                        <?php echo htmlspecialchars($request['remarks']); ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (!empty($history)): ?>
                <div class="history-section">
                    <h3>Status History</h3>
                    <?php foreach ($history as $item): ?>
                        <div class="history-item">
                            <div class="history-header">
                                <div class="history-date">
                                    <?php echo date('F j, Y h:i A', strtotime($item['created_at'])); ?>
                                </div>
                                <div class="history-status">
                                    <span class="badge badge-<?php echo str_replace(' ', '-', $item['status']); ?>">
                                        <?php echo ucfirst($item['status']); ?>
                                    </span>
                                    <span class="badge badge-<?php echo $item['w_com_status']; ?>">
                                        <?php echo ucfirst($item['w_com_status']); ?>
                                    </span>
                                </div>
                            </div>
                            <?php if (!empty($item['remarks'])): ?>
                                <div class="history-details">
                                    <?php echo htmlspecialchars($item['remarks']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div class="timestamps">
                <div>
                    <strong>Created:</strong> 
                    <?php echo date('F j, Y h:i A', strtotime($request['created_at'])); ?>
                </div>
                <div>
                    <strong>Last Updated:</strong> 
                    <?php echo date('F j, Y h:i A', strtotime($request['updated_at'])); ?>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="work_requests_list.php" class="btn btn-back">
                    ← Back to List
                </a>
                <?php if ($user_role === 'admin' || $user_role === 'sadmin'): ?>
                    <a href="edit_request.php?id=<?php echo $request['id']; ?>" class="btn btn-primary">
                        ✏️ Edit Request
                    </a>
                    <a href="update_status.php?id=<?php echo $request['id']; ?>" class="btn btn-secondary">
                        🔄 Update Status
                    </a>
                <?php endif; ?>
                <?php if ($request['requester_id'] == $user_id || $user_role === 'admin' || $user_role === 'sadmin'): ?>
                    <a href="delete_request.php?id=<?php echo $request['id']; ?>" 
                       class="btn" style="background: #e74c3c; color: white;"
                       onclick="return confirm('Are you sure you want to delete this request?')">
                        🗑️ Delete Request
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>