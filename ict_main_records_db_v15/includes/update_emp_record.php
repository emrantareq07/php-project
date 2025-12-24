<?php
include('../db/db.php');

$id = $_POST['id'];
$emp_id = $_POST['emp_id'];
$user_name = $_POST['user_name'];
$designation = $_POST['designation'];
$place_of_posting = $_POST['place_of_posting'];
$division_dept = $_POST['division_dept'];
$mobile_pabx = $_POST['mobile_pabx'];

$sql = "UPDATE employees 
        SET emp_id = ?, user_name = ?, designation = ?, place_of_posting = ?, division_dept = ?, mobile_pabx = ? 
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssssi', $emp_id, $user_name, $designation, $place_of_posting, $division_dept, $mobile_pabx, $id);

$response = [];

if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'Record updated successfully.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to update record.';
}

echo json_encode($response);

$conn->close();
?>
