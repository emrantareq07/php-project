<?php
session_start();
require '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request method');
}

// Get form data
$id = $_POST['id'] ?? '';
$emp_id = $_POST['emp_id'] ?? '';
$name = $_POST['name'] ?? '';
$designation = $_POST['designation'] ?? '';
$blood_group = $_POST['blood_group'] ?? '';
$phone_office = $_POST['phone_office'] ?? '';
$intercom = $_POST['intercom'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$email = $_POST['email'] ?? '';
$department = $_POST['department'] ?? '';
$division = $_POST['division'] ?? '';
$fax = $_POST['fax'] ?? '';
$status = $_POST['status'] ?? 'active';
$system_status = $_POST['system_status'] ?? 'approved';
$address= $_POST['address'] ?? '';
// Validate required fields
if (empty($id) || empty($emp_id) || empty($name) || empty($designation) || empty($mobile)) {
    die('Required fields are missing');
}

// Check if EMP ID already exists for other employees
$checkSql = "SELECT id FROM emp_tbl WHERE emp_id = ? AND id != ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("si", $emp_id, $id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();
if ($checkResult->num_rows > 0) {
    die('Employee ID already exists for another employee');
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
            
            // Delete old image if exists
            $oldImageSql = "SELECT image FROM emp_tbl WHERE id = ?";
            $oldImageStmt = $conn->prepare($oldImageSql);
            $oldImageStmt->bind_param("i", $id);
            $oldImageStmt->execute();
            $oldImageResult = $oldImageStmt->get_result();
            $oldImageRow = $oldImageResult->fetch_assoc();
            $oldImage = $oldImageRow['image'] ?? '';
            $oldImageStmt->close();
            
            if ($oldImage && file_exists('../' . $oldImage)) {
                unlink('../' . $oldImage);
            }
        } else {
            die('Error uploading image');
        }
    } else {
        die('Invalid file type. Only JPG, JPEG, PNG, GIF, and WEBP are allowed.');
    }
}

// Prepare SQL query
if (!empty($image_path)) {
    // Update with new image
    $sql = "UPDATE emp_tbl SET 
        emp_id = ?, name = ?, designation = ?, phone_office = ?, 
        intercom = ?, mobile = ?, email = ?, department = ?, division = ?, blood_group = ?, 
        image = ?, fax = ?, address= ?,status = ?, system_status = ?, updated_at = NOW() 
        WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssssssssssssi", // Fixed: Added missing comma and corrected parameter count
        $emp_id, $name, $designation, $phone_office, $intercom,
        $mobile, $email, $department, $division,$blood_group, $image_path, $fax, $address,$status, $system_status, $id
    );
} else {
    // Update without changing image
    $sql = "UPDATE emp_tbl SET 
        emp_id = ?, name = ?, designation = ?, phone_office = ?, 
        intercom = ?, mobile = ?, email = ?, department = ?, division = ?, blood_group = ?,
        fax = ?,address= ?, status = ?, system_status = ?, updated_at = NOW() 
        WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssssssssssi", // Fixed: Corrected parameter count
        $emp_id, $name, $designation, $phone_office, $intercom,
        $mobile, $email, $department, $division, $blood_group,$fax, $address,$status, $system_status, $id
    );
}

if ($stmt->execute()) {
    echo 'Employee updated successfully!';
} else {
    echo 'Error: ' . $stmt->error;
}

$stmt->close();
$conn->close();
?>