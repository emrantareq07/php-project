
<?php
session_start();
//$_SESSION['emp_id']=emp_id;

include('../db/database_2.php');
include('function.php');
$query = '';
$output = array();

 $query .= "SELECT * FROM basic_info "; 
//
// $query .= "SELECT * FROM users WHERE status = ?"; status
if(isset($_POST["search"]["value"])){
 $query .= 'WHERE name LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR email LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR mobile_no LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR division LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR section LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR designation LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR emp_id LIKE "%'.$_POST["search"]["value"].'%" ';
 // $query .= 'OR job_status LIKE "%'.$_POST["search"]["value"].'%" ';
 // $query .= 'AND status LIKE "PRL"';
 
}
if(isset($_POST["order"])){
 $query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else{
 
 // $query .= 'ORDER BY seniority_no ';
	$query .= 'ORDER BY COALESCE(seniority_no, (SELECT MAX(seniority_no) FROM basic_info) + 1) ';

}
if($_POST["length"] != -1){
 $query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}


// $query .= "SELECT * FROM users WHERE name = 'Harun'";
$statement = $connection->prepare($query);

$statement->execute();
$result = $statement->fetchAll();
$data = array();
$filtered_rows = $statement->rowCount();
foreach($result as $row){
 $image = '';
 if($row["image"] != '') {
  $image = '<img src="'.$row['image'] .'" class="img-thumbnail" width="50" height="35" />';
 }
 else {
  $image = '';
 }

$designation = $row['designation'];
// $emp_id=$row["emp_id"];

// $statement = $connection->prepare("SELECT * FROM job_desc  where emp_id ='$emp_id'");
//  $statement->execute();
//  $result1 = $statement->fetchAll();
//  foreach($result1 as $row1) {
	$sub_cadre_header = $row['sub_cadre_header'];
 	 // $sub_cadre_header = '';
	 if($sub_cadre_header!= '') {

		$designation_header=$designation.' ('.$sub_cadre_header.')';
		 }
		 else {
		  $designation_header = $designation;
		 }

 //}

 $sub_array = array();
 $sub_array[] = $image;
 $sub_array[] = $row["emp_id"];
 $sub_array[] = $row["seniority_no"];
 $sub_array[] = $row["name"];

 $sub_array[] = $designation_header;

 $sub_array[] = $row["division"];
 $sub_array[] = $row["section"];
 // $sub_array[] = $row["job_status"];

  // $sub_array[] = $row["phone_home"];
 // $sub_array[] = $row["intercom"];
 $sub_array[] = $row["mobile_no"];
 $sub_array[] = $row["email"];
 $sub_array[] = '<button type="button" name="view" id="'.$row["emp_id"].'" class="btn btn-primary btn-xs view"><i class="fa fa-eye"></i> </button>';
 
 $sub_array[] = '<button type="button" name="update" id="'.$row["id"].'" class="btn btn-warning btn-xs update"><i class="fa fa-edit"></i> </button>';
 $sub_array[] = '<button type="button" name="delete" id="'.$row["id"].'" class="btn btn-danger btn-xs delete"><i class="fa fa-trash-o"></i> </button>';

 // $sub_array[] = '<button type="button" name="print" id="'.$row["emp_id"].'" class="btn btn-primary btn-xs print"><i class="fa fa-info-circle"></i></span> Bio Data</button>';
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