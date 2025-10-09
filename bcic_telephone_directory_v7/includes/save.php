<?php
session_start();
require '../db/db.php';

// Disable notices in production
error_reporting(0);

// Sanitize input
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Ensure request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Invalid request.";
    exit;
}

// Collect POST data
$id          = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$name        = sanitize($_POST['name'] ?? '');
$mobile      = sanitize($_POST['mobile'] ?? '');
$alt_mobile  = sanitize($_POST['alt_mobile'] ?? '');
$email       = sanitize($_POST['email'] ?? '');
$occupation  = sanitize($_POST['occupation'] ?? '');
$jobplace    = sanitize($_POST['jobplace'] ?? '');
$address     = sanitize($_POST['address'] ?? '');
$blood_group = sanitize($_POST['blood_group'] ?? '');

// Default status
$status = 'pending';

// Validate required fields
if (!$name || !$mobile || !$occupation || !$address || !$blood_group) {
    http_response_code(400);
    echo "Please fill all required fields.";
    exit;
}

// Handle image upload
$imagePath = '';
if (!empty($_FILES['image']['name'])) {
    $allowed = ['jpg','jpeg','png','gif'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed)) {
        echo "Invalid image format. Only JPG, PNG, GIF allowed.";
        exit;
    }

    $imageName = uniqid('profile_', true) . '.' . $ext;
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
    $imagePath = $uploadDir . $imageName;

    if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
        echo "Failed to upload image.";
        exit;
    }
}

// If editing → update, else insert
if ($id > 0) {
    // If no new image uploaded → keep old image
    if (!$imagePath) {
        $stmt = $conn->prepare("SELECT image FROM friends WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($oldImage);
        if ($stmt->fetch()) {
            $imagePath = $oldImage;
        }
        $stmt->close();
    }

    $stmt = $conn->prepare("UPDATE friends SET name=?, mobile=?, alt_mobile=?, email=?, occupation=?, jobplace=?, address=?, blood_group=?, image=? WHERE id=?");
    $stmt->bind_param("sssssssssi", $name,$mobile,$alt_mobile,$email,$occupation,$jobplace,$address,$blood_group,$imagePath,$id);

    if ($stmt->execute()) {
        echo "Friend updated successfully!";
    } else {
        http_response_code(500);
        echo "Error updating friend.";
    }
    $stmt->close();

} else {
    $stmt = $conn->prepare("INSERT INTO friends (name,mobile,alt_mobile,email,occupation,jobplace,address,blood_group,status,image) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssssss", $name,$mobile,$alt_mobile,$email,$occupation,$jobplace,$address,$blood_group,$status,$imagePath);

    if ($stmt->execute()) {
        echo "Your contact request has been submitted! Waiting for Admin Approval!!!";
    } else {
        http_response_code(500);
        echo "Error saving friend.";
    }
    $stmt->close();
}
?>
