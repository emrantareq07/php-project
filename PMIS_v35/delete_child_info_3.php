<?php
//$connect = mysqli_connect("localhost", "root", "", "testing");
include('database_connection.php');
if(isset($_POST["id"]))
{
 $query = "DELETE FROM childs_info WHERE id = '".$_POST["id"]."'";
 if(mysqli_query($connect, $query))
 {
  echo 'Data Deleted';
 }
}
?>