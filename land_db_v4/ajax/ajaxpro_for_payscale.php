<?php
include('../db/db.php');

if (isset($_GET['id'])) {
    $sql = "SELECT * FROM districts WHERE division_id = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_GET['id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $json = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $json[$row['id']] = $row['bn_name'];
    }

    echo json_encode($json);
} else {
    echo json_encode([]);
}

?>
