<?php
// db settings
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'pmis_db';

// db connection
$conn = mysqli_connect($hostname, $username, $password, $database) or die("Error " . mysqli_error($con));

// fetch records
$sql = "SELECT * from users where status ='PRL'";
// $sql = "select * from users where status ='PRL' ORDER BY name DESC";
$result = mysqli_query($conn, $sql);


while($row = mysqli_fetch_assoc($result)) {
	// $array = array();
	//  // $sub_array[] = $image;
 // $array[] = $row["emp_id"];
 // $array[] = $row["seniority_no"];
 // $array[] = $row["name"];
 
 //  $array[] = $row["mobile_no"];
 // $array[] = $row["email"];

// $array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row["id"].'">Delete</button>';

    $array[] = $row;
}

// $data = array();

// while($row = mysqli_fetch_assoc($result))
// {
//  $sub_array = array();
 // $sub_array[] = $row["id"];
 // $sub_array[] = $row["name"];
 // $sub_array[] = $row["email"];
 
 // $sub_array[] = '<div contenteditable class="update" data-id="'.$row["id"].'" data-column="name">' . $row["name"] . '</div>';
 // $sub_array[] = '<div contenteditable class="update" data-id="'.$row["id"].'" data-column="email">' . $row["email"] . '</div>';
 // $sub_array[] = '<button type="button" name="delete" class="btn btn-danger btn-xs delete" id="'.$row["id"].'">Delete</button>';
//  $data[] = $sub_array;
// }



// $dataset = array(
//     "echo" => 1,
//     "totalrecords" => count($data),
//     "totaldisplayrecords" => count($data),
//     "data" => $data
// );
$dataset = array(
    "echo" => 1,
    "totalrecords" => count($array),
    "totaldisplayrecords" => count($array),
    "data" => $array
);

echo json_encode($dataset);
?>