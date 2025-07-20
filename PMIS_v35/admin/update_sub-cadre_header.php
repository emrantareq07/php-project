<?php
    include_once('../db/db.php');
    $id=$_GET['id'];
 
    //$id=$_POST['id'];
    $header=$_POST['header'];
 
    mysqli_query($conn,"update `sub_cadre_header` set header='$header' where id='$id'");
    header('location:add_sub-cadre_header.php');
?>