<?php
session_name('dfms');
session_start();
$table=$_SESSION['username'];
$user_type = $_SESSION['user_type'];

if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}

 $dbhost = 'localhost';
  $dbuser = 'root';
  $dbpass = "";
  $dbname = 'dfms_db';

  $link= mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);
  $conn =mysqli_select_db($link,$dbname);
// include('db/db.php');

if(isset($_POST['update'])){
$id =$_POST['id'];
$daily = $_POST['daily'];
$date  = $_POST['date'];
$product_produce=$_POST['product_produce']; 
$plant_load=$_POST['plant_load'];
$remarks = $_POST['remarks'];
$remarks = mysqli_real_escape_string($link, $remarks);
if ($_SESSION['user_type'] == 'sadmin') { 
mysqli_query($link, "UPDATE $table 
                     SET daily = '$daily', date= '$date', plant_load = '$plant_load',remarks = '$remarks'
                     WHERE id = $id");
}
else{
mysqli_query($link, "UPDATE $table 
                     SET daily = '$daily',plant_load = '$plant_load',remarks = '$remarks'
                     WHERE id = $id");
}

// header("location:edit_urea.php?updated=successfully");

  echo '<script type="text/javascript">';
  // echo 'alert("Data Updated successfully!!!");';
  echo 'window.location.href="view_urea_report.php"';
  echo '</script>'; 
  }

?>


