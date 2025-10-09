<?php
//This is the name of your server where the MySQL database is running
$dbserver="localhost";
//username of the MySQL server
$dbusername="root";
//password
$dbpassword="";
//database name of the online Examination system
$dbname="bcic_tel_db";

$conn = mysqli_connect($dbserver,$dbusername,$dbpassword)or die("could not connect");
mysqli_select_db($conn,$dbname) or die("could not connect to database");


//$con=mysql_connect("localhost","root","") or die("could not connect");
//mysql_select_db("test",$con);// or die("could not connect to database");
?>
 