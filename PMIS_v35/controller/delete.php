<?php
include('../db/database.php');
include('../db/db.php');
// include('database.php');
include("function.php");

if(isset($_POST["user_id"]))
{
 $image = get_image_name($_POST["user_id"]);
 if($image != '')
 {
  unlink($image);
 }
  $user_id=$_POST["user_id"];
  $statement = $connection->prepare("SELECT emp_id FROM basic_info WHERE id = '$user_id'");
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row) {
  $emp_id1= $row["emp_id"];
 }


 $statement = $connection->prepare(
  "DELETE FROM basic_info WHERE id = :id"
 );
 $result = $statement->execute(
  array(
   ':id' => $_POST["user_id"]
  )
 );
 
 if(!empty($result))
 {
 $statement = $connection->prepare(
  "DELETE FROM users WHERE emp_id = :emp_id"
 );
 $result = $statement->execute(
  array(
   ':emp_id' => $emp_id1
  )
 );

  echo 'Data Deleted';
 }
}



?>