<?php
   include_once('../db/db.php');
    $id=$_GET['id'];
	mysqli_query($conn,"delete from `cadre` where id='$id'");
	header('location:catsubcat-form.php?deleted=successfully');
?>