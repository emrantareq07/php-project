<?php
include_once '../db/database.php';
//for update
$id=$_GET['id']; 
$office=$_POST['office'];
mysqli_query($conn,"update `office` set office='$office' where id='$id'");
header('location:add_office.php?updated=successfully"');
?>