<?php


   require_once('db/db.php');


   $sql = "SELECT * FROM section WHERE division_id LIKE '%".$_GET['id']."%'"; 

$result = mysqli_query($conn, $sql);
   //$result = $mysqli->query($sql);


   $json = [];

   while($row = $result->fetch_assoc()){

        $json[$row['id']] = $row['name'];

   }


   echo json_encode($json);

?>