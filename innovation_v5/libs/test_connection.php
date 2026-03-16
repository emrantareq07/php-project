<?php
require_once("../config/config.php");
require_once("../db/db.php");

echo "<h2>Database Connection Test</h2>";

if ($conn) {
    echo "<p style='color:green'>✓ Database connected successfully</p>";
    
    // Check tables
    $tables = ['tbl_users', 'tbl_innovation', 'fiscal_year'];
    
    foreach ($tables as $table) {
        $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
        if (mysqli_num_rows($result) > 0) {
            echo "<p style='color:green'>✓ Table '$table' exists</p>";
        } else {
            echo "<p style='color:red'>✗ Table '$table' does not exist</p>";
        }
    }
    
} else {
    echo "<p style='color:red'>✗ Database connection failed: " . mysqli_connect_error() . "</p>";
}
?>