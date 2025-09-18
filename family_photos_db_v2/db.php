<?php
$host = "localhost";
$user = "root";    // your DB username
$pass = "";        // your DB password
$dbname = "photo_app";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
?>
