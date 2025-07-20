<?php

//fetch.php
session_start();
// include('database_connection.php');
//include('db/db.php');
// $column = array("id", "name", "dob", "gender");

// $query = "SELECT * FROM childs_info ";

// if(isset($_POST["search"]["value"]))
// {
// 	$query .= '
// 	WHERE name LIKE "%'.$_POST["search"]["value"].'%" 
// 	OR dob LIKE "%'.$_POST["search"]["value"].'%" 
// 	OR gender LIKE "%'.$_POST["search"]["value"].'%" 
// 	';
// }

// if(isset($_POST["order"]))
// {
// 	$query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
// }
// else
// {
// 	$query .= 'ORDER BY id DESC ';
// }

// $query1 = '';

// if($_POST["length"] != -1)
// {
// 	$query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
// }

// $statement = $connect->prepare($query);

// $statement->execute();

// $number_filter_row = $statement->rowCount();

// $result = $connect->query($query . $query1);

// $data = array();

// foreach($result as $row)
// {
// 	$sub_array = array();
// 	$sub_array[] = $row['id'];
// 	$sub_array[] = $row['emp_id'];
// 	$sub_array[] = $row['name'];
// 	$sub_array[] = $row['dob'];
// 	$sub_array[] = $row['gender'];
// 	$data[] = $sub_array;
// }

// function count_all_data($connect)
// {
// 	$query = "SELECT * FROM childs_info";

// 	$statement = $connect->prepare($query);

// 	$statement->execute();

// 	return $statement->rowCount();
// }

// $output = array(
// 	'draw'		=>	intval($_POST['draw']),
// 	'recordsTotal'	=>	count_all_data($connect),
// 	'recordsFiltered'	=>	$number_filter_row,
// 	'data'		=>	$data
// );

// echo json_encode($output);



//----------------


//fetch.php

include('../db/database_connection.php');

$column = array("id", "emp_id", "name", "dob", "gender");

$query = "SELECT * FROM childs_info ";

if(isset($_POST["search"]["value"]))
{
	$query .= '
	WHERE (name LIKE "%'.$_POST["search"]["value"].'%" 
	OR dob LIKE "%'.$_POST["search"]["value"].'%" 
	OR gender LIKE "%'.$_POST["search"]["value"].'%")
	AND emp_id = "'.$_SESSION['emp_id'].'"
	';
}
else
{
	$query .= 'WHERE emp_id = "'.$_SESSION['emp_id'].'" ';
}

if(isset($_POST["order"]))
{
	$query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY id DESC ';
}

$query1 = '';

if($_POST["length"] != -1)
{
	$query1 = 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$number_filter_row = $statement->rowCount();

$result = $connect->query($query . $query1);

$data = array();

foreach($result as $row)
{
	$sub_array = array();
	$sub_array[] = $row['id'];
	$sub_array[] = $row['emp_id'];
	$sub_array[] = $row['name'];
	$sub_array[] = $row['dob'];
	$sub_array[] = $row['gender'];
	$data[] = $sub_array;
}

function count_all_data($connect)
{
	$query = "SELECT * FROM childs_info WHERE emp_id = '".$_SESSION['emp_id']."'";

	$statement = $connect->prepare($query);

	$statement->execute();

	return $statement->rowCount();
}

$output = array(
	'draw'		=>	intval($_POST['draw']),
	'recordsTotal'	=>	count_all_data($connect),
	'recordsFiltered'	=>	$number_filter_row,
	'data'		=>	$data
);

echo json_encode($output);



?>
