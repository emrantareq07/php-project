<?php
    include_once('../db/db.php');
    $id=$_GET['id']; 
    
    $t_name=$_POST['t_name'];
 
    mysqli_query($conn,"update `training_list` set t_name='$t_name' where id='$id'");
    header('location:add_training_title.php');
?>