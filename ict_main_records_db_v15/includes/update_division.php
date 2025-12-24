<?php
    include_once('../db/db.php');
    $id=$_GET['id'];
 
    //$id=$_POST['id'];
    $division=$_POST['division'];
 
    mysqli_query($conn,"update `division` set division='$division' where id='$id'");
    header('location:add_division.php');
?>