<?php

include('database.php');
include("function.php");

if(isset($_POST["user_id"]))
{
 $image = get_image_name($_POST["user_id"]);
 if($image != '')
 {
  unlink("upload/" . $image);
 }
 $statement = $connection->prepare(
  "DELETE FROM tel_tbl WHERE id = :id"
 );
 $result = $statement->execute(
  array(
   ':id' => $_POST["user_id"]
  )
 );
 
 if(!empty($result))
 {
  echo 'Data Deleted';
 }
}



?>