<?php
session_name('factory_work_request_db');
require_once '../../db/config.php';

$isAdmin = in_array($_SESSION['role'], ['admin','sadmin']);

$sql = "SELECT s.id, s.name, d.division 
        FROM section s 
        JOIN division d ON s.division_id=d.id
        ORDER BY d.division, s.name";

$res = $conn->query($sql);
$i = 1;

while ($row = $res->fetch_assoc()) {
    echo "<tr>
            <td>{$i}</td>
            <td>{$row['division']}</td>
            <td>{$row['name']}</td>";
    if ($isAdmin) {
        echo "<td>
                <button class='btn btn-sm btn-warning edit' data-id='{$row['id']}'>Edit</button>
                <button class='btn btn-sm btn-danger delete' data-id='{$row['id']}'>Delete</button>
              </td>";
    }
    echo "</tr>";
    $i++;
}
