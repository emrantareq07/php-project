<?php
    include_once('../db/db.php');
    $id=$_GET['id'];
 
    //$id=$_POST['id'];
    $place_of_posting=$_POST['place_of_posting'];
 
    mysqli_query($conn,"update `place_of_posting` set place_of_posting='$place_of_posting' where id='$id'");
    header('location:add_office_or_factory.php?updated=successfully"');
?>