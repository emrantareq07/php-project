<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

header('Content-Type: application/json');
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    try {
        $id = intval($_POST['id']);
        $factory_name = $_SESSION['username'];
        
        $sql = "DELETE FROM workers_tbl WHERE id = ? AND factory_name = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $id, $factory_name);
        
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = 'Worker record deleted successfully!';
        } else {
            throw new Exception('Failed to delete record');
        }
        
        $stmt->close();
    } catch (Exception $e) {
        $response['message'] = $e->getMessage();
    }
}

echo json_encode($response);
exit;
?>