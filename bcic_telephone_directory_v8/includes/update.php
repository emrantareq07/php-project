<?php
session_start();
require '../db/db.php';

// Enable error reporting for debugging
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

$id          = intval($_POST['id'] ?? 0);
$name        = sanitize($_POST['name'] ?? '');
$mobile      = sanitize($_POST['mobile'] ?? '');
$alt_mobile  = sanitize($_POST['alt_mobile'] ?? '');
$email       = sanitize($_POST['email'] ?? '');
$occupation  = sanitize($_POST['occupation'] ?? '');
$jobplace    = sanitize($_POST['jobplace'] ?? '');
$address     = sanitize($_POST['address'] ?? '');
$blood_group = sanitize($_POST['blood_group'] ?? '');
$status      = sanitize($_POST['status'] ?? 'approved');

// Validate required fields
if (!$id || !$name || !$mobile || !$occupation || !$address || !$blood_group) {
    http_response_code(400);
    echo 'Please fill all required fields.';
    exit;
}

// Fetch existing record to check old image
$stmt = $conn->prepare("SELECT image FROM friends WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$oldImage = '';
if ($row = $res->fetch_assoc()) {
    $oldImage = $row['image'];
}
$stmt->close();

// Handle image upload
$imagePath = $oldImage; // default keep old
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

    if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        // remove old file if exists
        if ($oldImage && file_exists($oldImage)) {
            unlink($oldImage);
        }
    } else {
        echo 'Failed to upload new image.';
        exit;
    }
}

// Update query
$stmt = $conn->prepare("UPDATE friends 
    SET name=?, mobile=?, alt_mobile=?, email=?, occupation=?, jobplace=?, address=?, blood_group=?, status=?, image=? 
    WHERE id=?");
$stmt->bind_param("ssssssssssi", 
    $name,$mobile,$alt_mobile,$email,$occupation,$jobplace,$address,$blood_group,$status,$imagePath,$id);

if ($stmt->execute()) {
    echo 'Friend updated successfully!';
} else {
    http_response_code(500);
    echo 'Error updating record.';
}
$stmt->close();
$conn->close();
?>
