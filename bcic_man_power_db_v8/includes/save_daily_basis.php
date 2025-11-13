<?php
error_reporting(0);
ini_set('display_errors', 0);
ob_start();

session_name('man_power_db');
session_start();
include('../db/db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['username'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$username = $_SESSION['username'];
$table = 'daily_basis_tbl';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? '';
    $factory_name = $_POST['factory_name'] ?? $username;
    
    // Check monthly restriction
    $year_month = date('Y-m', strtotime($date));
    $check_sql = "SELECT id FROM $table WHERE factory_name = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('ss', $factory_name, $year_month);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'An entry already exists for ' . date('F Y', strtotime($date))]);
        exit;
    }
    
    // Continue with your existing save logic...
    $designation = implode(',', $_POST['designation']);
    $grade = implode(',', $_POST['grade']);
    $sanctioned_post = implode(',', $_POST['sanctioned_post']);
    $male = implode(',', $_POST['male']);
    $female = implode(',', $_POST['female']);
    $total = implode(',', $_POST['total']);
    
    $sql = "INSERT INTO $table (date, factory_name, designation, grade, sanctioned_post, male, female, total, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssss', $date, $factory_name, $designation, $grade, $sanctioned_post, $male, $female, $total);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Daily Basis data saved successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving data: ' . $stmt->error]);
    }
    
    $stmt->close();
    $conn->close();
}
?>