<?php
session_name('man_power_db');
session_start();
include('../db/db.php');

echo "<h3>Current Login Status</h3>";
$sql = "SELECT id, username, role, login_status, 
        CASE WHEN login_status = 1 THEN '🟢 Online' ELSE '⚪ Offline' END as status_display
        FROM users 
        WHERE role IN ('admin', 'user')
        ORDER BY id DESC";

$result = $conn->query($sql);
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>ID</th><th>Username</th><th>Role</th><th>Status</th></tr>";
while($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['username']}</td>";
    echo "<td>{$row['role']}</td>";
    echo "<td>{$row['status_display']}</td>";
    echo "</tr>";
}
echo "</table>";
?>