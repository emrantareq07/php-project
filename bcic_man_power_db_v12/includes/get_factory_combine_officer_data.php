<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

header('Content-Type: application/json');
date_default_timezone_set('Asia/Dhaka');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['month_key']) && isset($_POST['factory_name'])) {
    $month_key = $conn->real_escape_string($_POST['month_key']);
    $factory_name = $conn->real_escape_string($_POST['factory_name']);
    
    // Get all records for this month and factory from officers_tbl
    $sql = "SELECT * FROM officers_tbl 
            WHERE DATE_FORMAT(date, '%Y-%m') = '$month_key' 
            AND factory_name = '$factory_name'
            ORDER BY date DESC";
    
    $result = $conn->query($sql);
    
    $data = [];
    $records_count = 0;
    
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $records_count = $result->num_rows;
    }
    
    echo json_encode([
        'success' => true,
        'data' => $data,
        'records_count' => $records_count,
        'month' => date("F Y", strtotime($month_key . '-01')),
        'factory_name' => $factory_name
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>