<?php
   //require_once('db/db.php');
// include('../db/db.php');

// $sql = "SELECT * FROM basic WHERE scale_id LIKE '%".$_GET['id']."%'"; 

// $result = mysqli_query($conn, $sql);
//    //$result = $mysqli->query($sql);

// $json = [];

//    while($row = $result->fetch_assoc()){

//         $json[$row['id']] = $row['basic'];

//    }


//    echo json_encode($json);


include('../db/db.php');

if (isset($_GET['id'])) {
    $sql = "SELECT * FROM basic WHERE scale_id = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $_GET['id']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $json = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $json[$row['id']] = $row['basic'];
    }

    echo json_encode($json);
} else {
    echo json_encode([]);
}



?>