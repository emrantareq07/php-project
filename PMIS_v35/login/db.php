<?php
//This is the name of your server where the MySQL database is running
$dbserver="localhost";
//username of the MySQL server
$dbusername="root";
//password
$dbpassword="";
//database name of the online Examination system
$dbname="pmis_db";

$conn = mysqli_connect($dbserver,$dbusername,$dbpassword);
mysqli_select_db($conn,$dbname);

//$conn = new PDO("mysql:host=localhost; dbname=pmis_db", "root", "");

//$con=mysql_connect("localhost","root","") or die("could not connect");
//mysql_select_db("test",$con);// or die("could not connect to database");
?>
 