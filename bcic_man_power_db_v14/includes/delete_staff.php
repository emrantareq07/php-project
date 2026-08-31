<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $id = $_POST['id'] ?? '';

    if (empty($id)) {
        throw new Exception('Record ID is required');
    }

    $factory_name = $_SESSION['username'];

    // Check if user owns this record
    $check_sql = "SELECT id FROM staffs_tbl WHERE id = ? AND factory_name = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param('is', $id, $factory_name);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows === 0) {
        throw new Exception('Record not found or access denied');
    }
    $check_stmt->close();

    // Delete record
    $sql = "DELETE FROM staffs_tbl WHERE id = ? AND factory_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $id, $factory_name);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response['success'] = true;
            $response['message'] = 'Staff record deleted successfully';
        } else {
            throw new Exception('Record not found');
        }
    } else {
        throw new Exception('Failed to delete record: ' . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>