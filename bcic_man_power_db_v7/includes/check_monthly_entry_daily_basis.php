<?php
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header('Content-Type: application/json');
    echo json_encode(['exists' => false]);
    exit;
}

$username = $_SESSION['username'];
$table = 'daily_basis_tbl';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'] ?? date('Y-m-d');
    $factory_name = $_POST['factory_name'] ?? $username;
    
    $year_month = date('Y-m', strtotime($date));
    
    $sql = "SELECT id, date FROM $table WHERE factory_name = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $factory_name, $year_month);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $exists = $result->num_rows > 0;
    $month_year = date('F Y', strtotime($date));
    
    $stmt->close();
    $conn->close();
    
    header('Content-Type: application/json');
    echo json_encode([
        'exists' => $exists,
        'month_year' => $month_year
    ]);
}
?>