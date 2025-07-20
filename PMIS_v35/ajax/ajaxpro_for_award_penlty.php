<?php


   //require_once('db/db.php');
include('db/db.php');

$i=$_GET['id']


//for SSC
   // $sql = "SELECT * FROM subject_ssc WHERE subject_id LIKE '%".$_GET['id']."%'"; 
$sql = "SELECT * FROM award WHERE auto_id LIKE '%".$i."%'"; 
$result = mysqli_query($conn, $sql);
   //$result = $mysqli->query($sql);


   $json = [];

   while($row = $result->fetch_assoc()){

        $json[$row['auto_id']] = $row['award_or_penalty'];

   }


   echo json_encode($json);

//    //for SSC Dakhil
//    $sql = "SELECT * FROM subject_ssc WHERE dakhil_exam_id LIKE '%".$_GET['id']."%'"; 

// $result = mysqli_query($conn, $sql);
//    //$result = $mysqli->query($sql);


//    $json = [];

//    while($row = $result->fetch_assoc()){

//         $json[$row['id']] = $row['name'];

//    }


//    echo json_encode($json);

// $sql = "SELECT * FROM subject_ssc";
// $result = mysqli_query($conn, $sql);

// $id = $_GET['id'];
// $whereClause = "";

// $json = [];

// while ($row = $result->fetch_assoc()) {
//     $id_db1 = $row['sscexam_id'];
//     $id_db2 = $row['dakhil_exam_id'];
//     $id_db3 = $row['sscequip_id'];
//     $id_db4 = $row['sscvoc_id'];
//     $id_db5 = $row['olevel_id'];
//     $id_db6 = $row['dakhil_voc_id'];
//     $name = $row['name'];

//     if ($id === $id_db1) {
//         $whereClause = "sscexam_id LIKE '%" . $_GET['id'] . "%'";
//         $json[$id_db1] = $name;
//     } elseif ($id === $id_db2) {
//         $whereClause = "dakhil_exam_id LIKE '%" . $_GET['id'] . "%'";
//         $json[$id_db2] = $name;
//     }elseif ($id === $id_db3) {
//         $whereClause = "sscequip_id LIKE '%" . $_GET['id'] . "%'";
//         $json[$id_db3] = $name;
//     }elseif ($id === $id_db4) {
//         $whereClause = "sscvoc_id LIKE '%" . $_GET['id'] . "%'";
//         $json[$id_db4] = $name;
//     }elseif ($id === $id_db5) {
//         $whereClause = "olevel_id LIKE '%" . $_GET['id'] . "%'";
//         $json[$id_db5] = $name;
//     }elseif ($id === $id_db6) {
//         $whereClause = "dakhil_voc_id LIKE '%" . $_GET['id'] . "%'";
//         $json[$id_db6] = $name;
//     }
// }

// if (!empty($whereClause)) {
//     $sql = "SELECT * FROM subject_ssc WHERE " . $whereClause;
//     $result = mysqli_query($conn, $sql);

//     while ($row = $result->fetch_assoc()) {
//         $json[$row['id']] = $row['name'];
//     }
// }

// echo json_encode($json);

// $sql = "SELECT * FROM subject_ssc";
// $result = mysqli_query($conn, $sql);

// $id = $_GET['id'];
// $whereClause = "";

// $json = [];

// while ($row = $result->fetch_assoc()) {
//     $id_db = $row['subject_id'];
    // $id_db2 = $row['dakhil_exam_id'];
    // $id_db3 = $row['sscequip_id'];
    // $id_db4 = $row['sscvoc_id'];
    // $id_db5 = $row['olevel_id'];
    // $id_db6 = $row['dakhil_voc_id'];
    //$name = $row['name'];

    //if ($id == $id_db) {
       // $whereClause = "subject_id LIKE '%" . $_GET['id'] . "%'";
        //$json[$id_db] = $name;
    // } elseif ($id === $id_db2) {
    //     $whereClause = "dakhil_exam_id LIKE '%" . $_GET['id'] . "%'";
    //     $json[$id_db2] = $name;
    // }elseif ($id === $id_db3) {
    //     $whereClause = "sscequip_id LIKE '%" . $_GET['id'] . "%'";
    //     $json[$id_db3] = $name;
    // }elseif ($id === $id_db4) {
    //     $whereClause = "sscvoc_id LIKE '%" . $_GET['id'] . "%'";
    //     $json[$id_db4] = $name;
    // }elseif ($id === $id_db5) {
    //     $whereClause = "olevel_id LIKE '%" . $_GET['id'] . "%'";
    //     $json[$id_db5] = $name;
    // }elseif ($id === $id_db6) {
    //     $whereClause = "dakhil_voc_id LIKE '%" . $_GET['id'] . "%'";
    //     $json[$id_db6] = $name;
    // }
//}

// if (!empty($whereClause)) {
//     $sql = "SELECT * FROM subject_ssc WHERE " . $whereClause;
//     $result = mysqli_query($conn, $sql);

//     while ($row = $result->fetch_assoc()) {
//         $json[$row['id']] = $row['name'];
//     }
// }

// echo json_encode($json);

?>