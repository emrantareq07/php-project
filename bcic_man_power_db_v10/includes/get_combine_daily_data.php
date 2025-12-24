<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

header('Content-Type: application/json');
date_default_timezone_set('Asia/Dhaka');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$username = $_SESSION['username'];
$table = 'daily_basis_tbl';

if ($_POST['action'] === 'get_combine_data' && isset($_POST['month_key'])) {
    $month_key = $conn->real_escape_string($_POST['month_key']);
    
    // Extract year and month from month_key (format: Y-m)
    list($year, $month) = explode('-', $month_key);
    
    // Get all records for the specified month
    $sql = "SELECT * FROM $table 
            WHERE YEAR(date) = '$year' AND MONTH(date) = '$month' 
            ORDER BY factory_name, date";
    
    $result = $conn->query($sql);
    
    $data = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $data,
        'month_key' => $month_key,
        'record_count' => count($data)
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request'
    ]);
}
?>