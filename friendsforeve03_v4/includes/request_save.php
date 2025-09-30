<?php
require '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name       = $_POST['name'] ?? '';
    $mobile     = $_POST['mobile'] ?? '';
    $alt_mobile = $_POST['alt_mobile'] ?? '';
    $email      = $_POST['email'] ?? '';
    $occupation = $_POST['occupation'] ?? '';
    $jobplace   = $_POST['jobplace'] ?? '';
    $address    = $_POST['address'] ?? '';

    if ($name && $mobile && $address) {
        $stmt = $conn->prepare("INSERT INTO friends 
            (name, mobile, alt_mobile, email, occupation, jobplace, address, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("sssssss", $name, $mobile, $alt_mobile, $email, $occupation, $jobplace, $address);
        if ($stmt->execute()) {
            echo "✅ Contact request submitted. Waiting for admin approval.";
        } else {
            echo "❌ Error saving contact.";
        }
    } else {
        echo "⚠️ Please fill all required fields.";
    }
}
