<?php
   include_once('../db/db.php');
    $id=$_GET['id'];
	mysqli_query($conn,"delete from `section` where id='$id'");
	header('location:add_division.php?deleted=successfully');
?>