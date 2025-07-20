<?php
function get_total_all_records(){
  include('db/database_2.php');
  //include('../db/db.php');
  // $statement = $connection->prepare("SELECT * FROM basic_info ORDER BY COALESCE(seniority_no, (select max(seniority_no) from basic_info)+1)");
  
  $statement = $connection->prepare("SELECT * FROM legal_tbl");
//$statement = $connection->prepare("SELECT * FROM job_desc j INNER JOIN basic_info b where j.emp_id=b.emp_id");

 $statement->execute();
 $result = $statement->fetchAll();
 return $statement->rowCount();
}
// function get_filter_records(){
//   include('../db/database_2.php');
//  $statement = $connection->prepare("SELECT * FROM basic_info where email='ferdous@gmail.com'");
//  $statement->execute();
//  $result = $statement->fetchAll();
//  return $statement->rowCount();
// }


?>
   