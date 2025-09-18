<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $stmt = $conn->prepare("INSERT INTO friends 
        (name,mobile,alt_mobile,email,occupation,jobplace,address,status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");

    if (!$stmt) {
        echo "Prepare failed: " . $conn->error;
        exit;
    }

    $name = $_POST['name'] ?? '';
    $mobile = $_POST['mobile'] ?? '';
    $alt_mobile = $_POST['alt_mobile'] ?? '';
    $email = $_POST['email'] ?? '';
    $occupation = $_POST['occupation'] ?? '';
    $jobplace = $_POST['jobplace'] ?? '';
    $address = $_POST['address'] ?? '';

    $stmt->bind_param("sssssss", $name, $mobile, $alt_mobile, $email, $occupation, $jobplace, $address);

    if ($stmt->execute()) {
        echo "Request submitted. Awaiting admin approval.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    exit;
}
echo "Invalid request";
