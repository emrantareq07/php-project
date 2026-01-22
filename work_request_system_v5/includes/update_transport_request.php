<?php
// update_transport_request.php
session_name('factory_work_request_db');
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: ../index.php");
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'factory_work_request_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user info
$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'] ?? '';
$routine_role = $_SESSION['routine_role'] ?? '';

// Check if user is Transport Section Head, Division Head, or Admin
$stmt = $conn->prepare("SELECT routine_role, division, section FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result()->fetch_assoc();
$routine_role = $user_result['routine_role'] ?? '';
$user_db_division = $user_result['division'] ?? '';
$user_db_section = $user_result['section'] ?? '';

$isTransportSectionHead = ($user_db_division === 'Administration Division' && $routine_role === 'section_head' && $user_db_section === 'Transport');
$isTransportDivisionHead = ($user_db_division === 'Administration Division' && $routine_role === 'division_head');

if (!$isTransportSectionHead && !$isTransportDivisionHead && $user_role !== 'admin' && $user_role !== 'sadmin') {
    header("Location: incoming_w_req_transport.php?error=Unauthorized");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transport_id = $_POST['transport_id'] ?? '';
    $v_provide_status = $_POST['v_provide_status'] ?? '';
    $approval_status = $_POST['approval_status'] ?? '';
    $driver_name = $_POST['driver_name'] ?? '';
    $vehicle_no = $_POST['vehicle_no'] ?? '';
    $vehicle_exit_time = $_POST['vehicle_exit_time'] ?? '';
    $vehicle_entry_time = $_POST['vehicle_entry_time'] ?? '';
    $transport_notes = $_POST['transport_notes'] ?? '';
    
    // Validate required fields
    if (empty($transport_id) || empty($v_provide_status) || empty($approval_status)) {
        header("Location: incoming_w_req_transport.php?error=Missing required fields");
        exit;
    }
    
    // Additional validation for approved requests
    if ($approval_status === 'approved' && (empty($driver_name) || empty($vehicle_no))) {
        header("Location: incoming_w_req_transport.php?error=Driver name and vehicle number required for approval");
        exit;
    }
    
    // Update transport request
    $sql = "UPDATE transport_w_req_tbl SET 
            v_provide_status = ?,
            approval_status = ?,
            driver_name = ?,
            vehicle_no = ?,
            vehicle_exit_time = ?,
            vehicle_entry_time = ?,
            transport_notes = ?,
            updated_by = ?,
            updated_at = NOW()
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssi",
        $v_provide_status,
        $approval_status,
        $driver_name,
        $vehicle_no,
        $vehicle_exit_time,
        $vehicle_entry_time,
        $transport_notes,
        $user_id,
        $transport_id
    );
    
    if ($stmt->execute()) {
        header("Location: incoming_w_req_transport.php?success=Transport request updated successfully");
    } else {
        header("Location: incoming_w_req_transport.php?error=Failed to update transport request");
    }
    
    $stmt->close();
} else {
    header("Location: incoming_w_req_transport.php");
}

$conn->close();
exit;