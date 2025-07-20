  
<?php
session_start();

include('database.php');
include('function.php');
if(isset($_POST["emp_id"]))
{
 $output = array();
 $statement = $connection->prepare(
  "SELECT * FROM users 
  WHERE emp_id = '".$_POST["emp_id"]."' 
  LIMIT 1"
 );
 $statement->execute();
 $result = $statement->fetchAll();

 echo json_encode($output);
}

   
// include('database.php');
// include("function.php");

// if(isset($_POST["user_id"]))
// {

 // $statement = $connection->prepare(
  // "Select * FROM users WHERE id = :id"
 // );
 // $result = $statement->execute(
  // array(
   // ':id' => $_POST["user_id"]
  // )
 // );
 

// }



?>