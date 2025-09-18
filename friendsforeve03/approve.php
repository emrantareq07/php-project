<?php
session_name('friendsforeve03');
if(session_status()===PHP_SESSION_NONE) session_start();
require 'db.php';

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if($id > 0){
    $stmt = $conn->prepare("UPDATE friends SET status='approved' WHERE id=?");
    $stmt->bind_param("i",$id);
    if($stmt->execute()){
        echo "Record approved successfully";
    }else{
        echo "Error approving record";
    }
}else{
    echo "Invalid ID";
}
