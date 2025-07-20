
 
 
 <?php
// $servername = "localhost";
// $username = "root";
// $password = "";

// try {
  // $conn = new PDO("mysql:host=$servername;dbname=dfms_db", $username, $password);
  // // set the PDO error mode to exception
  // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // //echo "Connected successfully";
// } catch(PDOException $e) {
  // echo "Connection failed: " . $e->getMessage();
// }

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
 
 
 
 
 
 // $con = mysql_connect("localhost","root","");
 // if (!$con)
 // {
 // die('Could not connect: ' . mysql_error());
 // exit();
 // }
 
 // if (mysql_query("CREATE DATABASE fc_db",$con))
 // {
 // echo "Database created";
 // }
 // else
 // {
 // echo "Error creating database: " . mysql_error();
 // }
 //Create table
 // mysql_select_db("fc_db", $con);
 // $sql = "CREATE TABLE fc_details
 // (
 // Admin varchar(15),
 // Com varchar(15),
 // Account varchar(15)
 // )";
 
 // Execute query
 //mysql_query($sql,$con);
 
 //mysql_close($con);
 
 // $con = mysql_connect("localhost","root","");
 // if (!$con)
 // {
 // die('Could not connect: ' . mysql_error());
 // exit();
 // }
 
 // mysql_select_db("dfms_db", $con);
 ?>
 
