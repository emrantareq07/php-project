<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

header('Content-Type: application/json');
$response = ['success' => false, 'message' => '', 'data' => null];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_POST['date'])) {
            throw new Exception('Date is required');
        }
        
        $factory_name = $_SESSION['username'];
        $date = $conn->real_escape_string($_POST['date']);
        
        // Get the month and year from the requested date
        $input_year = date('Y', strtotime($date));
        $input_month = date('m', strtotime($date));
        
        // Find the record for this month/year
        $sql = "SELECT * FROM workers_tbl WHERE YEAR(date) = ? AND MONTH(date) = ? AND factory_name = ?";
        $stmt = $conn->prepare($sql);
        
        if (!$stmt) {
            throw new Exception('Database error: ' . $conn->error);
        }
        
        $stmt->bind_param('iis', $input_year, $input_month, $factory_name);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $response['success'] = true;
            $response['data'] = $result->fetch_assoc();
            $response['message'] = 'Data loaded successfully';
        } else {
            $response['message'] = 'No data found for selected month';
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
        error_log("Worker Load Error: " . $e->getMessage());
    }
} else {
    $response['message'] = 'Invalid request method';
}

echo json_encode($response);
exit;
?>