<?php
session_name('friendsforeve03');
if(session_status() === PHP_SESSION_NONE) session_start();
require '../db/db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id > 0){
    // First, get the image path
    $stmt = $conn->prepare("SELECT image FROM friends WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imagePath);
    $stmt->fetch();
    $stmt->close();

    // Delete the record
    $stmt = $conn->prepare("DELETE FROM friends WHERE id=?");
    $stmt->bind_param("i", $id);
    if($stmt->execute()){
        // Unlink image if exists
        if(!empty($imagePath) && file_exists($imagePath)){
            unlink($imagePath);
        }
        header("Location: dashboard.php");
        exit;
    } else {
        die("Error deleting record");
    }
} else {
    die("Invalid ID");
}
?>
