<?php
header('Content-Type: application/json');
require '../db/db.php';

// Default response
$response = [
    'success' => false,
    'employees' => [],
    'stats' => [],
    'debug_info' => []
];

try {
    // Read parameters safely
    $query      = isset($_GET['q']) ? trim($_GET['q']) : '';
    $department = isset($_GET['department']) ? trim($_GET['department']) : '';
    $limit      = isset($_GET['limit']) ? intval($_GET['limit']) : 12;
    $offset     = isset($_GET['offset']) ? intval($_GET['offset']) : 0;

    // Base SQL
    $sql = "SELECT emp_id, name, designation, division, department, mobile, phone_office, 
                   intercom, email, image, status, system_status
            FROM emp_tbl 
            WHERE 1 ";

    // Search filter
    if ($query !== '') {
        $q = $conn->real_escape_string($query);
        $sql .= "AND (name LIKE '%$q%' OR designation LIKE '%$q%' OR department LIKE '%$q%' 
                  OR mobile LIKE '%$q%' OR email LIKE '%$q%' OR emp_id LIKE '%$q%') ";
    }

    // Department filter
    if ($department !== '') {
        $dep = $conn->real_escape_string($department);
        $sql .= "AND department = '$dep' ";
    }

    // Public view should show only Active + Approved
    $sql .= "ORDER BY name ASC LIMIT $limit OFFSET $offset";

    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Query error: " . $conn->error);
    }

    $employees = [];
    while ($row = $result->fetch_assoc()) {
        // Determine approval state
        $row['fully_approved'] = ($row['status'] === 'active' && $row['system_status'] === 'approved') ? 1 : 0;
        $employees[] = $row;

        // Debug info for console
        $response['debug_info'][] = [
            'name' => $row['name'],
            'status' => $row['status'],
            'system_status' => $row['system_status'],
            'fully_approved' => $row['fully_approved']
        ];
    }

    // Build stats
    $stats = [
        'total_employees' => 0,
        'active_employees' => 0,
        'pending_employees' => 0,
        'total_departments' => 0,
        'last_updated' => '-'
    ];

    // Get employee counts
    $countSql = "SELECT 
                    COUNT(*) AS total_employees,
                    SUM(CASE WHEN status='active' THEN 1 ELSE 0 END) AS active_employees1,
                    SUM(CASE WHEN system_status='pending' THEN 1 ELSE 0 END) AS pending_employees
                 FROM emp_tbl";
    $countRes = $conn->query($countSql);
    if ($countRes && $countRes->num_rows > 0) {
        $stats = array_merge($stats, $countRes->fetch_assoc());
    }

    // Count System Status Approval users
    $active_employees = "SELECT 
                            COUNT(system_status) AS active_employees
                         FROM emp_tbl
                         WHERE system_status = 'approved'";

    $active_employeesRes = $conn->query($active_employees);

    if ($active_employeesRes && $active_employeesRes->num_rows > 0) {
        $stats = array_merge($stats, $active_employeesRes->fetch_assoc());
    }

    // Count departments
    $deptRes = $conn->query("SELECT COUNT(DISTINCT department) AS total_departments FROM emp_tbl WHERE department IS NOT NULL AND department <> ''");
    if ($deptRes && $deptRes->num_rows > 0) {
        $stats['total_departments'] = $deptRes->fetch_assoc()['total_departments'];
    }

    // Last updated timestamp
    $timeRes = $conn->query("SELECT MAX(updated_at) AS last_updated FROM emp_tbl WHERE status = 'active' AND system_status = 'approved'");
    if ($timeRes && $timeRes->num_rows > 0) {
        $last = $timeRes->fetch_assoc()['last_updated'];
        $stats['last_updated'] = $last ? date('d M Y, h:i A', strtotime($last)) : '-';
    }

    $response['success'] = true;
    $response['employees'] = $employees;
    $response['stats'] = $stats;

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response, JSON_PRETTY_PRINT);
