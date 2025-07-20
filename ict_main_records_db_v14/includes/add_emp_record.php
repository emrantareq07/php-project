<?php
include('../db/db.php');

$emp_id = $_POST['emp_id'];
$user_name = $_POST['user_name'];
$designation = $_POST['designation'];
$place_of_posting = $_POST['place_of_posting'];
$division_dept = $_POST['division_dept'];
$mobile_pabx = $_POST['mobile_pabx'];

$response = [];

$sql = "INSERT INTO employees (emp_id, user_name, designation, place_of_posting, division_dept, mobile_pabx)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param('ssssss', $emp_id, $user_name, $designation, $place_of_posting, $division_dept, $mobile_pabx);

if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'Employee added successfully.';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Failed to add employee.';
}

echo json_encode($response);

$conn->close();
?>
