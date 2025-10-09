   
<?php
include('database.php');
include('function.php');
if(isset($_POST["user_id"]))
{
 $output = array();
 $statement = $connection->prepare(
  "SELECT * FROM tel_tbl 
  WHERE id = '".$_POST["user_id"]."' 
  LIMIT 1"
 );
 $statement->execute();
 $result = $statement->fetchAll();
 foreach($result as $row)
 {
  $output["name"] = $row["name"];
  $output["designation"] = $row["designation"];
  $output["division_name"] = $row["division_name"];
  $output["section_name"] = $row["section_name"];
  $output["phone_office"] = $row["phone_office"];
  $output["phone_home"] = $row["phone_home"];
  $output["intercom"] = $row["intercom"];
  $output["mobile"] = $row["mobile"];
  $output["email"] = $row["email"];
  if($row["image"] != '')
  {
   $output['user_image'] = '<img src="upload/'.$row["image"].'" class="img-thumbnail" width="50" height="35" /><input type="hidden" name="hidden_user_image" value="'.$row["image"].'" />';
  }
  else
  {
   $output['user_image'] = '<input type="hidden" name="hidden_user_image" value="" />';
  }
 }
 echo json_encode($output);
}
?>
   