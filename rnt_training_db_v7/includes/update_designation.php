<?php
    include_once('../db/db.php');
    $id=$_GET['id'];
 
    //$id=$_POST['id'];
    $designation=$_POST['designation'];
 
    mysqli_query($conn,"update `designation` set designation='$designation' where id='$id'");
    header('location:add_designation.php');
?>