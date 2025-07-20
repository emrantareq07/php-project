
<?php

include('db.php');

if(isset($_POST['emp_id'])){
 $emp_id=$_POST['emp_id'];

 $checkEmp_id=" SELECT emp_id FROM users WHERE emp_id='$emp_id' ";

 $query=mysqli_query($conn,$checkEmp_id);

 if(mysqli_num_rows($query)>0) {
  //echo "<span class='text-success'>OK</span>";
  echo "Sorry! Employee ID has already taken. Please try another.";
 }
 else {
  //echo "Employee ID Does not Match";
  //echo "<span class='text-success'> Employee ID Available</span>";
 }
 exit();
}


?>