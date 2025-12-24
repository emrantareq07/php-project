<?php
session_name('factory_work_request_db');
require_once '../../db/config.php';

$isAdmin = in_array($_SESSION['role'], ['admin','sadmin']);

$res = $conn->query("SELECT * FROM designation ORDER BY designation");
$i = 1;

while ($row = $res->fetch_assoc()) {
    echo "<tr>
            <td>{$i}</td>
            <td>{$row['designation']}</td>";

    if ($isAdmin) {
        echo "<td>
                <button class='btn btn-sm btn-warning edit' data-id='{$row['id']}'>Edit</button>
                <button class='btn btn-sm btn-danger delete' data-id='{$row['id']}'>Delete</button>
              </td>";
    }

    echo "</tr>";
    $i++;
}
