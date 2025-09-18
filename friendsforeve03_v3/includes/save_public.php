<?php
require '../db/db.php';

$name = $_POST['name'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$alt_mobile = $_POST['alt_mobile'] ?? '';
$email = $_POST['email'] ?? '';
$occupation = $_POST['occupation'] ?? '';
$jobplace = $_POST['jobplace'] ?? '';
$address = $_POST['address'] ?? '';

$stmt = $pdo->prepare("INSERT INTO friends 
    (name, mobile, alt_mobile, email, occupation, jobplace, address, status) 
    VALUES (?,?,?,?,?,?,?,'pending')");
$stmt->bind_param("sssssss", $name, $mobile, $alt_mobile, $email, $occupation, $jobplace, $address);

if($stmt->execute()){
    echo "Thank you! Your info is submitted and awaiting admin approval.";
} else {
    echo "Error: Could not save your info.";
}
?>
