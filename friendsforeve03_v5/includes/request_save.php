<?php
session_start();
require '../db/db.php';

// Enable error reporting for debugging (remove in production)
error_reporting(0);

// Function to sanitize input
function sanitize($data) {
    return htmlspecialchars(trim($data));
}

// Check POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Invalid request';
    exit;
}

// Collect form data
$name        = sanitize($_POST['name'] ?? '');
$mobile      = sanitize($_POST['mobile'] ?? '');
$alt_mobile  = sanitize($_POST['alt_mobile'] ?? '');
$email       = sanitize($_POST['email'] ?? '');
$occupation  = sanitize($_POST['occupation'] ?? '');
$jobplace    = sanitize($_POST['jobplace'] ?? '');
$address     = sanitize($_POST['address'] ?? '');
$blood_group = sanitize($_POST['blood_group'] ?? '');
$status      = 'pending'; // default pending

// Validate required fields
if (!$name || !$mobile || !$occupation || !$address || !$blood_group) {
    http_response_code(400);
    echo 'Please fill all required fields.';
    exit;
}

// Handle image upload
$imagePath = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
    $allowed = ['jpg','jpeg','png','gif'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        echo 'Invalid image format. Only JPG, PNG, GIF allowed.';
        exit;
    }

    $imageName = uniqid('profile_', true) . '.' . $ext;
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $imagePath = $uploadDir . $imageName;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        echo 'Failed to upload image.';
        exit;
    }
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO friends (name,mobile,alt_mobile,email,occupation,jobplace,address,blood_group,status,image) VALUES (?,?,?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssssss",$name,$mobile,$alt_mobile,$email,$occupation,$jobplace,$address,$blood_group,$status,$imagePath);

if ($stmt->execute()) {
    echo 'Your contact request has been submitted! Waiting for Admin Approval!!!';
} else {
    http_response_code(500);
    echo 'Error saving request.';
}
?>
