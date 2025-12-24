<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$response = ['exists' => false, 'month_year' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $date = $_POST['date'] ?? '';
    $factory_name = $_SESSION['username'];

    if (empty($date)) {
        throw new Exception('Date is required');
    }

    $year_month = date('Y-m', strtotime($date));
    $month_year = date('F Y', strtotime($date));

    $sql = "SELECT id FROM staffs_tbl WHERE factory_name = ? AND DATE_FORMAT(date, '%Y-%m') = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $factory_name, $year_month);
    $stmt->execute();
    $result = $stmt->get_result();

    $response['exists'] = $result->num_rows > 0;
    $response['month_year'] = $month_year;

    $stmt->close();

} catch (Exception $e) {
    $response['error'] = $e->getMessage();
} finally {
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>