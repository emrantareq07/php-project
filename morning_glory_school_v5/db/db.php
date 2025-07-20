<?php
// Database configuration
$dbserver = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "morning_glory_db";

// MySQLi connection
$conn = mysqli_connect($dbserver, $dbusername, $dbpassword, $dbname);
if (!$conn) {
    die("MySQLi Connection failed: " . mysqli_connect_error());
}

// PDO connection
try {
    $pdo_conn = new PDO("mysql:host=$dbserver;dbname=$dbname", $dbusername, $dbpassword);
    $pdo_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}

// You can use either $mysqli_conn or $pdo_conn depending on your needs
?>