  
<?php
session_start();

include('database.php');
include('function.php');
if(isset($_POST["id"])){
 $output = array();
 $statement = $connection->prepare(
  "SELECT * FROM legal_tbl 
  WHERE id = '".$_POST["id"]."' 
  LIMIT 1"
 );
 $statement->execute();
 $result = $statement->fetchAll();

 echo json_encode($output);
}




?>

<h5> ID: '.$row['id'].'</h5>

<h5> First Name:'.$row['id'].'</h5>

 <h5>