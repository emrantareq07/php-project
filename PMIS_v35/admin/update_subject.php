<?php
    include_once('../db/db.php');
    $id=$_GET['id'];
 
    //$id=$_POST['id'];
    $subject=$_POST['subject'];
 
    mysqli_query($conn,"update `subject_graduation` set name='$subject' where id='$id'");
    header('location:add_subject.php');
?>