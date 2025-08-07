<?php
//include('db/db.php');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "dfms_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
 
 if(isset($_POST['submit'])){
$factory_name=$_POST['factory_name'];
$daily=$_POST['daily'];
$date=$_POST['date'];
// $monthly=$_POST['monthly'];
// $yearly=$_POST['yearly'];
// $plant_load=$_POST['plant_load'];
// $stock_on_date=$_POST['stock_on_date'];
//$production_target=450000;
//$due=$production_target-$yearly;



$sql = "SELECT * FROM report_urea where `date` LIKE'{$date}%'";
	$result = mysqli_query($conn, $sql);
	 while($row = mysqli_fetch_array($result)) 
		{
		 $yearly = $row['yearly'];
		  $monthly = $row['monthly'];
		}
	 $monthly= $monthly + $daily;
	 $yearly=$yearly + $daily;
	 $due=$production_target-$yearly;
	$sql="INSERT INTO report_urea(factory_name,date,daily,monthly,yearly) VALUES('{$factory_name}','{$date}','{$daily}','{$monthly}','{$yearly}')";
	//echo "Succesfully insert data into database!!!";
	if (mysqli_query($conn, $sql)) {
  header("Location: success.php");
} else {
  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}
	

// if (mysqli_query($conn, $sql)) {
  // echo "New record created successfully";
// } else {
  // echo "Error: " . $sql . "<br>" . mysqli_error($conn);
// }

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
mysqli_close($conn);
 }
 ?>