<?php
require '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method'); 
}

// Get form data
$emp_id = $_POST['emp_id'] ?? '';
$name = $_POST['name'] ?? '';
$designation = $_POST['designation'] ?? '';
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
if (empty($emp_id) || empty($name) || empty($designation) || empty($mobile) || empty($division)) {
    die('Required fields are missing');
}

// Validate mobile number
if (!preg_match('/^01[3-9]\d{8}$/', $mobile)) {
    die('Invalid mobile number format. Must be 11 digits starting with 01.');
}

// Validate email format if provided
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die('Invalid email format.');
}

// Final server-side duplicate check (in case JavaScript was bypassed)
function checkDuplicate($conn, $field, $value) {
    // Check in pending requests
    $pending_sql = "SELECT id FROM emp_tbl WHERE $field = ? AND system_status = 'pending'";
    $pending_stmt = $conn->prepare($pending_sql);
    $pending_stmt->bind_param("s", $value);
    $pending_stmt->execute();
    $pending_result = $pending_stmt->get_result();
    
    if ($pending_result->num_rows > 0) {
        $pending_stmt->close();
        return "This $field is already in a pending approval request.";
    }
    $pending_stmt->close();
    
    // Check in approved employees
    $approved_sql = "SELECT id FROM emp_tbl WHERE $field = ? AND system_status = 'approved' AND status = 'active'";
    $approved_stmt = $conn->prepare($approved_sql);
    $approved_stmt->bind_param("s", $value);
    $approved_stmt->execute();
    $approved_result = $approved_stmt->get_result();
    
    if ($approved_result->num_rows > 0) {
        $approved_stmt->close();
        return "This $field is already registered in the directory.";
    }
    $approved_stmt->close();
    
    return null;
}

// Check duplicates
if ($error = checkDuplicate($conn, 'emp_id', $emp_id)) {
    die($error);
}

if ($error = checkDuplicate($conn, 'mobile', $mobile)) {
    die($error);
}

if (!empty($email) && ($error = checkDuplicate($conn, 'email', $email))) {
    die($error);
}

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

// Insert into emp_tbl with pending status
$sql = "INSERT INTO emp_tbl (
    emp_id, name, designation, phone_office, intercom, 
    mobile, email, department, division,blood_group, image, fax,address, 
    status, system_status, submitted_by, submitted_at
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?, 'active', 'pending', ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssssssssssssss", 
    $emp_id, 
    $name, 
    $designation, 
    $phone_office, 
    $intercom,
    $mobile, 
    $email, 
    $department, 
    $division, 
    $blood_group,
    $image_path, 
    $fax,
    $address,
    $submitted_by
);

if ($stmt->execute()) {
    echo 'Your contact information submitted successfully! Waiting for admin approval.';
} else {
    echo 'Error: ' . $stmt->error;
}

$stmt->close();
$conn->close();
?>