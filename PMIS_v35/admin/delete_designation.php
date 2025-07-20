<?php
   include_once('../db/db.php');
    $id=$_GET['id'];
	mysqli_query($conn,"delete from `designation` where id='$id'");
	header('location:add_designation.php?deleted=successfully');
?>