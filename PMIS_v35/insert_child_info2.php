<?php
$connect = mysqli_connect("localhost", "root", "", "pmis_db");
if(isset($_POST["name"], $_POST["dob"], $_POST["gender"]))
{
 $name = mysqli_real_escape_string($connect, $_POST["name"]);
 $dob = mysqli_real_escape_string($connect, $_POST["dob"]);
 $gender = mysqli_real_escape_string($connect, $_POST["gender"]);
 $query = "INSERT INTO childs_info(name, dob,gender) VALUES('$name', '$dob', '$gender')";
 if(mysqli_query($connect, $query))
 {
  echo 'Data Inserted';
 }
}
?>