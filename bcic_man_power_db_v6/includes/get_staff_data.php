<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$response = ['success' => false, 'message' => '', 'data' => null];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $id = $_POST['id'] ?? '';

    if (empty($id)) {
        throw new Exception('Record ID is required');
    }

    $factory_name = $_SESSION['username'];

    // Fetch record
    $sql = "SELECT * FROM staffs_tbl WHERE id = ? AND factory_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $id, $factory_name);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $data = $result->fetch_assoc();
        $response['success'] = true;
        $response['data'] = $data;
        $response['message'] = 'Data loaded successfully';
    } else {
        throw new Exception('Record not found');
    }

    $stmt->close();

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>