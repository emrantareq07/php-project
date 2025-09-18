<?php
require '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect and sanitize input
    $name        = $_POST['name'] ?? '';
    $mobile      = $_POST['mobile'] ?? '';
    $alt_mobile  = $_POST['alt_mobile'] ?? '';
    $email       = $_POST['email'] ?? '';
    $occupation  = $_POST['occupation'] ?? '';
    $jobplace    = $_POST['jobplace'] ?? '';
    $address     = $_POST['address'] ?? '';
    $status      = 'pending'; // All public submissions are pending

    // Prepare and execute insert
    $stmt = $conn->prepare("INSERT INTO friends (name, mobile, alt_mobile, email, occupation, jobplace, address, status) VALUES (?,?,?,?,?,?,?,?)");
    $stmt->bind_param("ssssssss", $name, $mobile, $alt_mobile, $email, $occupation, $jobplace, $address, $status);

    if ($stmt->execute()) {
        echo "Your request submitted successfully. Awaiting for admin approval.";
    } else {
        echo "Submission failed: " . $conn->error;
    }
    exit;
}

echo "Invalid request.";
