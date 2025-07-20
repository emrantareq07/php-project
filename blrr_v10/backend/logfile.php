<?php 
require_once("config.php");
require_once("../db/database.php");

$query = "SELECT * from log_table ";
    // Run the query
$query_run = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($query_run)){
// echo "ID"."<br>";
echo $row['id'].".&nbsp;";
echo "&nbsp;" . $row['username'];
echo "&nbsp;" .$row['password'];
echo "&nbsp;" .$row['user_type'];
echo "&nbsp;" .$row['Ip'];
echo "&nbsp;" .$row['login_date_time'];
echo "&nbsp;" .$row['logout_date_time'];
echo "<br>";
}

?>