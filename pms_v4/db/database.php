<?php
//This is the name of your server where the MySQL database is running
$dbserver="localhost";
//username of the MySQL server
$dbusername="root";
//password
$dbpassword="";
//database name of the online Examination system
$dbname="pms_db";

$conn = mysqli_connect($dbserver,$dbusername,$dbpassword);
mysqli_select_db($conn,$dbname);


//$con=mysql_connect("localhost","root","") or die("could not connect");
//mysql_select_db("test",$con);// or die("could not connect to database");
?>
<?php
$host = 'localhost';
$dbname = 'pms_db';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
