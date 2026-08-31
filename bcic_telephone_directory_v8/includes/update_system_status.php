<?php
session_name('bcic_tel_db');
if(session_status()===PHP_SESSION_NONE) session_start();
require '../db/db.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$system_status = isset($_POST['system_status']) ? mysqli_real_escape_string($conn, $_POST['system_status']) : '';

$valid_statuses = ['pending', 'approved', 'rejected', 'resignation', 'dismissed'];

if($id > 0 && in_array($system_status, $valid_statuses)){
    $stmt = $conn->prepare("UPDATE emp_tbl SET system_status=? WHERE id=?");
    $stmt->bind_param("si", $system_status, $id);
    if($stmt->execute()){
        echo "System status updated to " . ucfirst($system_status) . " successfully";
    }else{
        echo "Error updating system status";
    }
    $stmt->close();
}else{
    echo "Invalid ID or status";
}
?>