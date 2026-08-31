<?php
session_start();
require '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

// Get form data
$emp_id = $_POST['emp_id'] ?? '';
$name = $_POST['name'] ?? '';
$designation = $_POST['designation'] ?? '';
//$office = $_POST['office'] ?? '';
$phone_office = $_POST['phone_office'] ?? '';
$intercom = $_POST['intercom'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$email = $_POST['email'] ?? '';
$department = $_POST['department'] ?? '';
$division = $_POST['division'] ?? '';
$fax = $_POST['fax'] ?? '';
$status = $_POST['status'] ?? 'active';
$blood_group = $_POST['blood_group'] ?? '';
$address= $_POST['address'] ?? '';

// Validate required fields
if (empty($emp_id) || empty($name) || empty($designation) || empty($mobile)) {
    die('Required fields are missing');
}

// Check if EMP ID already exists
$checkSql = "SELECT id FROM emp_tbl WHERE emp_id = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("s", $emp_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();
if ($checkResult->num_rows > 0) {
    die('Employee ID already exists');
}
$checkStmt->close();

// Handle file upload
$image_path = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../uploads/employees/';
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    $file_extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    
    if (in_array(strtolower($file_extension), $allowed_extensions)) {
        $filename = 'emp_' . $emp_id . '_' . time() . '.' . $file_extension;
        $upload_path = $upload_dir . $filename;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image_path = 'uploads/employees/' . $filename;
        } else {
            die('Error uploading image');
        }
    } else {
        die('Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.');
    }
}

// Insert into database
$sql = "INSERT INTO emp_tbl (
    emp_id, name, designation, office, phone_office, intercom, 
    mobile, email, department, division,blood_group, image, fax, address,status,system_status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?, ?,?, ?,'approved')";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssssssssssss", 
    $emp_id, $name, $designation, $office, $phone_office, $intercom,
    $mobile, $email, $department, $division,$blood_group, $image_path, $fax, $address, $status
);

if ($stmt->execute()) {
    echo 'Employee added successfully!';
} else {
    echo 'Error: ' . $stmt->error;
}

$stmt->close();
$conn->close();
?>