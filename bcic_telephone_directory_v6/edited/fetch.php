<?php
include('database.php');
include('function.php');
$query = '';
$output = array();
$query .= "SELECT * FROM tel_tbl ";
if(isset($_POST["search"]["value"]))
{
 $query .= 'WHERE name LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR email LIKE "%'.$_POST["search"]["value"].'%" ';
}
if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
 $query .= 'ORDER BY id DESC ';
}
if($_POST["length"] != -1)
{
 $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}
$statement = $connection->prepare($query);
$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
foreach($result as $row)
{
 $image = '';
 if($row["image"] != '')
 {
  $image = '<img src="upload/'.$row["image"].'" class="img-thumbnail" width="50" height="35" />';
 }
 else
 {
  $image = '';
 }
 $sub_array = array();
 $sub_array[] = $image;
 $sub_array[] = $row["name"];
 $sub_array[] = $row["designation"];
  $sub_array[] = $row["division_name"];
 $sub_array[] = $row["section_name"];
 // $sub_array[] = $row["phone_office"];
  // $sub_array[] = $row["phone_home"];
 // $sub_array[] = $row["intercom"];
  $sub_array[] = $row["mobile"];
 $sub_array[] = $row["email"];
 $sub_array[] = '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-success btn-xs update"><span class="glyphicon glyphicon-edit "></span> Update</button>';
 $sub_array[] = '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger btn-xs delete"><span class="glyphicon glyphicon-trash"></span> Delete</button>';
 $data[] = $sub_array;
}
$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>  $filtered_rows,
 "recordsFiltered" => get_total_all_records(),
 "data"    => $data
);
echo json_encode($output);
?>