<?php
 require_once('../db/db.php');


$sql = "SELECT * FROM sub_cadre WHERE cadre_id LIKE '%".$_GET['id']."%'"; 

$result = mysqli_query($conn, $sql);
   //$result = $mysqli->query($sql);


   $json = [];

   while($row = $result->fetch_assoc()){

        $json[$row['id']] = $row['sub_cadre'];

   }


   echo json_encode($json);

?>