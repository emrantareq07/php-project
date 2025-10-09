<?php
require '../db/db.php';

header('Content-Type: application/json');

$field = $_GET['field'] ?? '';
$value = $_GET['value'] ?? '';

if (empty($field) || empty($value)) {
    echo json_encode(['exists' => false, 'message' => '']);
    exit;
}

$allowed_fields = ['emp_id', 'mobile', 'email'];
if (!in_array($field, $allowed_fields)) {
    echo json_encode(['exists' => false, 'message' => 'Invalid field']);
    exit;
}

try {
    // Check in pending requests
    $pending_sql = "SELECT id, name FROM emp_tbl WHERE $field = ? AND system_status = 'pending'";
    $pending_stmt = $conn->prepare($pending_sql);
    $pending_stmt->bind_param("s", $value);
    $pending_stmt->execute();
    $pending_result = $pending_stmt->get_result();
    
    if ($pending_result->num_rows > 0) {
        $pending_data = $pending_result->fetch_assoc();
        echo json_encode([
            'exists' => true, 
            'message' => "This $field is already in a pending approval request.",
            'type' => 'pending'
        ]);
        $pending_stmt->close();
        $conn->close();
        exit;
    }
    $pending_stmt->close();
    
    // Check in approved employees
    $approved_sql = "SELECT id, name FROM emp_tbl WHERE $field = ? AND system_status = 'approved' AND status = 'active'";
    $approved_stmt = $conn->prepare($approved_sql);
    $approved_stmt->bind_param("s", $value);
    $approved_stmt->execute();
    $approved_result = $approved_stmt->get_result();
    
    if ($approved_result->num_rows > 0) {
        $approved_data = $approved_result->fetch_assoc();
        echo json_encode([
            'exists' => true, 
            'message' => "This $field is already registered for: " . $approved_data['name'],
            'type' => 'approved'
        ]);
        $approved_stmt->close();
        $conn->close();
        exit;
    }
    $approved_stmt->close();
    
    echo json_encode(['exists' => false, 'message' => '']);
    
} catch (Exception $e) {
    echo json_encode(['exists' => false, 'message' => 'Error checking availability']);
}

$conn->close();
?>