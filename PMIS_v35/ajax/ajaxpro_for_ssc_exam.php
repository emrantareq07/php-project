<?php

include('db/db.php');

// $id = (isset($_POST['id'])) ? $_POST['id'] : '';

// $i = 0;
// if ($id == 1 || $id == 2 || $id == 4 || $id == 5) {
//     $i = 1;
// } else {
//     $i = 2;
// }

// $output='';
// $sql="SELECT * from subject_ssc where subject_id='".$_POST['id']."' ";
// $result = mysqli_query($conn, $sql);
// $output.='<option value="" disabled selected>--Select--</option>';
// while ($row = mysqli_fetch_array($result)) {
// $output.='<option value="'.$row["id"].'">'.$row["name"].'</option>';

//   }

//   echo $output;
$id = (isset($_GET['id'])) ? $_GET['id'] : '';

$i = 0;
if ($id == 1 || $id == 2 || $id == 4 || $id == 5) {
    $i = 1;
} else {
    $i = 2;
}

$sql = "SELECT * FROM subject_ssc WHERE subject_id LIKE '%$i%'";
$result = mysqli_query($conn, $sql);

$json = [];

while ($row = $result->fetch_assoc()) {
    $json[$row['id']] = $row['name'];
}

echo json_encode($json);



   //require_once('db/db.php');
// include('db/db.php');
// $i=0;
// if($_GET['id']==1 || $_GET['id']==2 || $_GET['id']==4 || $_GET['id']==5){

//     $i=1;

// }

// else{
//     $i=2;

// }
//for SSC
   // $sql = "SELECT * FROM subject_ssc WHERE subject_id LIKE '%".$_GET['id']."%'"; 
// $sql = "SELECT * FROM subject_ssc WHERE subject_id LIKE '%".$i."%'"; 
// $result = mysqli_query($conn, $sql);
//    //$result = $mysqli->query($sql);


//    $json = [];

//    while($row = $result->fetch_assoc()){

//         $json[$row['id']] = $row['name'];

//    }


//    echo json_encode($json);

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