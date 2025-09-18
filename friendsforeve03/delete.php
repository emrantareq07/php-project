<?php
session_name('friendsforeve03');
if(session_status()===PHP_SESSION_NONE) session_start();
require 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($id > 0){
    $stmt = $conn->prepare("DELETE FROM friends WHERE id=?");
    $stmt->bind_param("i",$id);
    if($stmt->execute()){
        header("Location: index.php?msg=Record deleted successfully");
        exit;
    }else{
        die("Error deleting record");
    }
}else{
    die("Invalid ID");
}
