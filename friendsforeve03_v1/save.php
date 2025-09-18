<?php
session_name('friendsforeve03');
if(session_status()===PHP_SESSION_NONE) session_start();
require 'db.php';

$id = intval($_POST['id'] ?? 0);
$name = $_POST['name'] ?? '';
$mobile = $_POST['mobile'] ?? '';
$alt_mobile = $_POST['alt_mobile'] ?? '';
$email = $_POST['email'] ?? '';
$occupation = $_POST['occupation'] ?? '';
$jobplace = $_POST['jobplace'] ?? '';
$address = $_POST['address'] ?? '';
$status = 'pending';

if($id>0){
    // Update
    $stmt = $conn->prepare("UPDATE friends SET name=?, mobile=?, alt_mobile=?, email=?, occupation=?, jobplace=?, address=? WHERE id=?");
    $stmt->bind_param("sssssssi",$name,$mobile,$alt_mobile,$email,$occupation,$jobplace,$address,$id);
    echo $stmt->execute() ? "Record updated successfully" : "Error updating record";
}else{
    // Insert
    $stmt = $conn->prepare("INSERT INTO friends (name,mobile,alt_mobile,email,occupation,jobplace,address,status,created_at) VALUES (?,?,?,?,?,?,?,?,NOW())");
    $stmt->bind_param("ssssssss",$name,$mobile,$alt_mobile,$email,$occupation,$jobplace,$address,$status);
    echo $stmt->execute() ? "Record added successfully" : "Error adding record";
}
