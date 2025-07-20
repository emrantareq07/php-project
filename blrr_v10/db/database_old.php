<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blrr_db_old";
$conn_old = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn_old) {
    die("Connection failed: " . mysqli_connect_error());
}

$conn_old->set_charset("utf8");
$conn_old->set_charset("utf8mb4");
?>