<?php
// db settings
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'pmis_db';

// db connection
$con = mysqli_connect($hostname, $username, $password, $database) or die("Error " . mysqli_error($con));

// fetch records
// $sql = "SELECT * FROM `basic` WHERE status = 'In Service' ORDER BY `seniority_no` DESC";

 // $sql="SELECT * from basic_info u,  job_desc j where u.emp_id=j.emp_id ";
// $sql = "select * from users GROUP BY status = 'In Service' ORDER BY `seniority_no` DESC";
$sql = "SELECT * from basic_info u inner join job_desc j ON u.emp_id=j.emp_id AND j.job_status = 'In Service' ORDER BY u.seniority_no DESC ";
$result = mysqli_query($con, $sql);

  // if(mysqli_num_rows($result) > 0){
while($row = mysqli_fetch_assoc($result)) {
    $array[] = $row;
}
// }
$dataset = array(
    "echo" => 1,
    "totalrecords" => count($array),
    "totaldisplayrecords" => count($array),
    "data" => $array
);

echo json_encode($dataset);
?>