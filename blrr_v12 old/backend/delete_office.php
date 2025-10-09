<?php
include_once('../db/db.php');
    
$id=$_GET['id'];
mysqli_query($conn,"delete from `office` where id='$id'");
header('location:add_office_or_factory.php?deleted=successfully');

?>