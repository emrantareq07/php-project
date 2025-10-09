<?php
require '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

// Get form data
$name = $_POST['name'] ?? '';
$designation = $_POST['designation'] ?? '';
$office = $_POST['office'] ?? '';
$phone_office = $_POST['phone_office'] ?? '';
$intercom = $_POST['intercom'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$email = $_POST['email'] ?? '';
$department = $_POST['department'] ?? '';
$division = $_POST['division'] ?? '';
$fax = $_POST['fax'] ?? '';
$blood_group = $_POST['blood_group'] ?? '';
$address = $_POST['address'] ?? '';
$submitted_by = $_POST['submitted_by'] ?? 'public';

// Validate required fields
if (empty($name) || empty($designation) || empty($mobile) || empty($department) || empty($address)) {
    die('Required fields are missing');
}

// Validate mobile number
if (!preg_match('/^01[3-9]\d{8}$/', $mobile)) {
    die('Invalid mobile number format. Must be 11 digits starting with 01.');
}

// Check if mobile already exists in pending requests
$checkStmt = $conn->prepare("SELECT id FROM emp_tbl WHERE mobile = ? AND system_status = 'pending'");
$checkStmt->bind_param("s", $mobile);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();
if ($checkResult->num_rows > 0) {
    die('A request with this mobile number is already pending approval.');
}
$checkStmt->close();

// Check if mobile exists in approved employees
$checkEmpStmt = $conn->prepare("SELECT id FROM emp_tbl WHERE mobile = ? AND system_status = 'approved' AND status = 'active'");
$checkEmpStmt->bind_param("s", $mobile);
$checkEmpStmt->execute();
$checkEmpResult = $checkEmpStmt->get_result();
if ($checkEmpResult->num_rows > 0) {
    die('This mobile number is already registered in the directory.');
}
$checkEmpStmt->close();

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
        $filename = 'emp_pending_' . time() . '_' . uniqid() . '.' . $file_extension;
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

// Generate unique EMP ID for pending request
$emp_id = 'PENDING_' . date('Ymd') . '_' . substr(uniqid(), -6);

// Insert into emp_tbl with pending status
$sql = "INSERT INTO emp_tbl (
    emp_id, name, designation, office, phone_office, intercom, 
    mobile, email, department, division, image, fax, 
    status, system_status, submitted_by, submitted_at
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', 'pending', ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssssssssss", 
    $emp_id, $name, $designation, $office, $phone_office, $intercom,
    $mobile, $email, $department, $division, $image_path, $fax, $submitted_by
);

if ($stmt->execute()) {
    echo 'Your contact information has been submitted successfully! It will appear in the directory after admin approval.';
} else {
    echo 'Error: ' . $stmt->error;
}

$stmt->close();
$conn->close();
?>