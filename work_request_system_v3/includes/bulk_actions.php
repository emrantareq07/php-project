<?php
// bulk_actions.php
session_name('factory_work_request_db');
session_start();
header('Content-Type: application/json');

// Check if user is logged in and is admin/sadmin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Check if user has admin privileges
$user_role = $_SESSION['role'] ?? 'user';
if ($user_role !== 'admin' && $user_role !== 'sadmin') {
    echo json_encode(['success' => false, 'message' => 'Insufficient permissions']);
    exit;
}

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';
$user_ids = $data['users'] ?? [];

if (empty($action) || empty($user_ids)) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

// Include database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'factory_work_request_db';

// Create connection
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Remove current user from list
$current_user_id = $_SESSION['user_id'];
$user_ids = array_filter($user_ids, function($id) use ($current_user_id) {
    return $id != $current_user_id;
});

if (empty($user_ids)) {
    echo json_encode(['success' => false, 'message' => 'No valid users selected']);
    exit;
}

// Prepare SQL based on action
switch ($action) {
    case 'activate':
        $sql = "UPDATE users SET status = 'active', updated_at = NOW() WHERE id IN (" . implode(',', $user_ids) . ")";
        break;
    case 'deactivate':
        $sql = "UPDATE users SET status = 'inactive', updated_at = NOW() WHERE id IN (" . implode(',', $user_ids) . ")";
        break;
    case 'delete':
        $sql = "DELETE FROM users WHERE id IN (" . implode(',', $user_ids) . ")";
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        exit;
}

if ($conn->query($sql)) {
    echo json_encode([
        'success' => true, 
        'message' => ucfirst($action) . 'd ' . count($user_ids) . ' user(s) successfully'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
}

$conn->close();
?>