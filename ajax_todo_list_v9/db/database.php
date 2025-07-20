<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "todolist_db";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$conn->set_charset("utf8");
?>