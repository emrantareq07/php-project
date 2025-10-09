<?php
require '../db/db.php';

header('Content-Type: application/json');

$query = $_GET['q'] ?? '';
$department = $_GET['department'] ?? '';
$limit = intval($_GET['limit'] ?? 12);
$offset = intval($_GET['offset'] ?? 0);
$check_new = isset($_GET['check_new']);

try {
    // Build the main query
    $sql = "SELECT * FROM emp_tbl WHERE status = 'active'";
    $count_sql = "SELECT COUNT(*) as total FROM emp_tbl WHERE status = 'active'";
    $params = [];
    $types = "";
    
    // Add search conditions
    if (!empty($query)) {
        $search_condition = " AND (name LIKE ? OR designation LIKE ? OR department LIKE ? OR mobile LIKE ? OR email LIKE ? OR emp_id LIKE ?)";
        $sql .= $search_condition;
        $count_sql .= $search_condition;
        $search_param = "%$query%";
        for($i = 0; $i < 6; $i++) {
            $params[] = $search_param;
            $types .= "s";
        }
    }
    
    // Add department filter
    if (!empty($department)) {
        $sql .= " AND department = ?";
        $count_sql .= " AND department = ?";
        $params[] = $department;
        $types .= "s";
    }
    
    // Complete the main query
    $sql .= " ORDER BY name ASC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";
    
    // Execute main query
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $employees = $result->fetch_all(MYSQLI_ASSOC);
    
    // Get statistics
    $stats = getStatistics($conn);
    
    echo json_encode([
        'success' => true,
        'employees' => $employees,
        'stats' => $stats
    ]);
    
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error loading employees: ' . $e->getMessage(),
        'employees' => [],
        'stats' => []
    ]);
}

$conn->close();

function getStatistics($conn) {
    $stats = [];
    
    // Total employees
    $result = $conn->query("SELECT COUNT(*) as total FROM emp_tbl WHERE status = 'active'");
    $stats['total_employees'] = $result->fetch_assoc()['total'];
    
    // Active employees (same as total for now, since we're only showing active)
    $stats['active_employees'] = $stats['total_employees'];
    
    // Total departments
    $result = $conn->query("SELECT COUNT(DISTINCT department) as total FROM emp_tbl WHERE status = 'active' AND department IS NOT NULL AND department != ''");
    $stats['total_departments'] = $result->fetch_assoc()['total'];
    
    // Last updated
    $result = $conn->query("SELECT MAX(updated_at) as last_updated FROM emp_tbl WHERE status = 'active'");
    $last_updated = $result->fetch_assoc()['last_updated'];
    $stats['last_updated'] = $last_updated ? date('M j, Y g:i A', strtotime($last_updated)) : 'Never';
    
    return $stats;
}
?>