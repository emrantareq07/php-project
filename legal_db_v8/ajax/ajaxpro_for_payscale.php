<?php
include('../db/db.php');

if (isset($_GET['id'])) {
    $sql = "SELECT * FROM case_type WHERE auto_id = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $_GET['id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $json = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $json[$row['id']] = $row['type'];
    }

    echo json_encode($json);
} else {
    echo json_encode([]);
}

?>
