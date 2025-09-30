<?php
include 'db.php';

$sql = "SELECT * FROM chairman";
$result = mysqli_query($conn, $sql);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $row['action'] = 
        '<button class="btn btn-sm btn-primary editBtn" data-id="'.$row['id'].'">Edit</button>
         <button class="btn btn-sm btn-danger deleteBtn" data-id="'.$row['id'].'">Delete</button>';
    $data[] = $row;
}

echo json_encode(['data' => $data]);
