<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "innovation_db"; // 🔴 change this

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Site name constant
define('SITE_NAME', 'BCIC Innovation Database');
?>