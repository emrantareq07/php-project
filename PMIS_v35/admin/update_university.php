<?php
    include_once('../db/db.php');
    $id=$_GET['id'];
 
    //$id=$_POST['id'];
    $university_name=$_POST['university_name'];
 
    mysqli_query($conn,"update `university_list` set name='$university_name' where id='$id'");
    header('location:add_universityandinstitute.php');
?>