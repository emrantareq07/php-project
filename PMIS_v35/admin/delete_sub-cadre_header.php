<?php
   include_once('../db/db.php');
    $id=$_GET['id'];
	mysqli_query($conn,"delete from `sub_cadre_header` where id='$id'");
	header('location:add_sub-cadre_header.php?deleted=successfully');
?>