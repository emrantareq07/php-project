<?php
    include_once('../db/db.php');
    $id=$_GET['id'];
 
    //$id=$_POST['id'];
    $name=$_POST['name'];
 
    mysqli_query($conn,"update `section` set name='$name' where id='$id'");
    header('location:add_division.php?updated=successfully');
?>