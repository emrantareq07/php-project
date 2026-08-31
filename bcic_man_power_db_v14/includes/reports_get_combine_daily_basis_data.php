<?php
session_name('man_power_db');
session_start();

include('../db/db.php');

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check authentication
if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Please login again.']);
    exit;
}

$logged_in_user = trim($_SESSION['username']);
$month_key = $_POST['month_key'] ?? '';

if (empty($month_key)) {
    echo json_encode(['success' => false, 'message' => 'Month parameter is required.']);
    exit;
}

// Check if table exists (try different possible names)
$table_name = '';
$table_check = $conn->query("SHOW TABLES LIKE 'daily_basis_tbl'");
if ($table_check->num_rows > 0) {
    $table_name = 'daily_basis_tbl';
} else {
    $table_check = $conn->query("SHOW TABLES LIKE 'daily_basis'");
    if ($table_check->num_rows > 0) {
        $table_name = 'daily_basis';
    } else {
        // If no specific daily_basis table, use workers_tbl but filter by type
        $table_name = 'workers_tbl';
        $use_type_filter = true;
    }
}

if ($use_type_filter ?? false) {
    // Query from workers_tbl with type filter
    $query = "SELECT 
                id, 
                factory_name, 
                date, 
                designation,
                grade,
                sanctioned_post,
                male,
                female,
                total
              FROM workers_tbl 
              WHERE LOWER(TRIM(factory_name)) = LOWER(TRIM(?)) 
              AND DATE_FORMAT(date, '%Y-%m') = ? 
              AND LOWER(designation) LIKE '%দৈনিক%'
              ORDER BY grade, designation";
    
    $stmt = $conn->prepare($query);
} else {
    // Query from daily_basis table
    // First, get columns to see what fields exist
    $columns_query = "SHOW COLUMNS FROM `$table_name`";
    $columns_result = $conn->query($columns_query);
    $columns = [];
    while ($col = $columns_result->fetch_assoc()) {
        $columns[] = $col['Field'];
    }
    
    // Build query based on available columns
    $select_fields = "id, factory_name, date";
    
    if (in_array('designation', $columns)) $select_fields .= ", designation";
    if (in_array('grade', $columns)) $select_fields .= ", grade";
    if (in_array('sanctioned_post', $columns)) $select_fields .= ", sanctioned_post";
    if (in_array('male', $columns)) $select_fields .= ", male";
    if (in_array('female', $columns)) $select_fields .= ", female";
    if (in_array('total', $columns)) $select_fields .= ", total";
    if (in_array('category1', $columns)) $select_fields .= ", category1 as male";
    if (in_array('category2', $columns)) $select_fields .= ", category2 as female";
    
    $query = "SELECT $select_fields FROM `$table_name` 
              WHERE LOWER(TRIM(factory_name)) = LOWER(TRIM(?)) 
              AND DATE_FORMAT(date, '%Y-%m') = ? 
              ORDER BY factory_name";
    
    $stmt = $conn->prepare($query);
}

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
    exit;
}

if ($use_type_filter ?? false) {
    $stmt->bind_param("ss", $logged_in_user, $month_key);
} else {
    $stmt->bind_param("ss", $logged_in_user, $month_key);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    // Ensure required fields exist
    if (!isset($row['male'])) $row['male'] = 0;
    if (!isset($row['female'])) $row['female'] = 0;
    if (!isset($row['total'])) $row['total'] = ($row['male'] + $row['female']);
    if (!isset($row['designation'])) $row['designation'] = 'দৈনিক ভিত্তিক';
    if (!isset($row['grade'])) $row['grade'] = '-';
    if (!isset($row['sanctioned_post'])) $row['sanctioned_post'] = 0;
    
    $data[] = $row;
}

$stmt->close();

echo json_encode([
    'success' => true,
    'records_count' => count($data),
    'data' => $data,
    'logged_in_user' => $logged_in_user,
    'month' => $month_key
]);
exit;
?>