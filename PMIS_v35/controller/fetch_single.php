   
<?php
include('../db/database.php');
include('function.php');
if(isset($_POST["user_id"])){
 $output = array();
 $statement = $connection->prepare(
  "SELECT * FROM basic_info 
  WHERE id = '".$_POST["user_id"]."' 
  LIMIT 1"
 );
 $statement->execute();
 $result = $statement->fetchAll();

 
 foreach($result as $row) {

$sub_cadre_header=$row["sub_cadre_header"];
if($sub_cadre_header==''){
  $sub_cadre_header='Select';
}
  $output["employee_type"] = $row["employee_type"];
  $output["emp_id"] = $row["emp_id"];
  $output["name"] = $row["name"];
  $output["seniority_no"] = $row["seniority_no"];
  $output["designation"] = $row["designation"];
  $output["sub_cadre_header"] = $row["sub_cadre_header"];
  $output["division"] = $row["division"];
  $output["section"] = $row["section"];
  // $output["job_status"] = $row["job_status"];
  $output["mobile_no"] = $row["mobile_no"];
  $output["email"] = $row["email"];
  
  if($row["image"] != '')  {
   $output['user_image'] = '<img src="../images/'.$row["image"].'" class="img-thumbnail" width="50" height="35" /><input type="hidden" name="hidden_user_image" value="'.$row["image"].'" />';
  }
  else  {
   $output['user_image'] = '<input type="hidden" name="hidden_user_image" value="" />';
  }
 }
 echo json_encode($output);
}
?>
   