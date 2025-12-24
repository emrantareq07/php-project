<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "viva_exam_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
