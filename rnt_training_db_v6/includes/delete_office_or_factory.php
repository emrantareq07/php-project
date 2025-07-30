<?php
   include_once('../db/db.php');
    $id=$_GET['id'];
	mysqli_query($conn,"delete from `place_of_posting` where id='$id'");
	header('location:add_office_or_factory.php?deleted=successfully');
?>