<?php
include('db/db.php');

// if (isset($_POST['emp_id_check'])) {
	// //if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['emp_id_check']) && $_POST['emp_id_check'] == 1) {
	
  	// $emp_id = mysql_real_escape_string($_POST['emp_id']);
  	// $sql = "SELECT emp_id FROM users WHERE emp_id='$emp_id'";
  	// $results = mysql_query($sql,$con);
  	// if (mysql_num_rows($results) > 0) {
  	  // echo "taken";	
  	// }else{
  	  // echo 'not_taken';
  	// }
  // exit();
  // }

$statusMsg = '';

// File upload path
$targetDir = "uploads/";
$fileName = basename($_FILES["file"]["name"]);
$targetFilePath = $targetDir . $fileName;
$fileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);


if(isset($_POST['submit']) && !empty($_FILES["file"]["name"])){
 $name=$_POST['name'];
 $designation=$_POST['designation'];

 $division_id=$_POST['division'];
 $sql_div_name="select name from division where id ='$division_id'"; 
 $result=mysqli_query($conn,$sql_div_name);
 while($row = mysqli_fetch_array($result))
					{
						
						$division_name=$row['name'];
						
					}
 
 $section_id=$_POST['section'];
 $sql_sec_name="select name from section where id ='$section_id'"; 
 $result=mysqli_query($conn,$sql_sec_name);
 while($row = mysqli_fetch_array($result))
					{
						
						$section_name=$row['name'];
						
					}
 
 
 $phone_office=$_POST['phone_office'];
 $phone_home=$_POST['phone_home'];
 
 $intercom=$_POST['intercom'];
 $mobile=$_POST['mobile'];
 $email=$_POST['email'];
$location="Dhaka";


// Allow certain file formats
    $allowTypes = array('jpg','png','jpeg','gif','pdf');
    if(in_array($fileType, $allowTypes)){
        // Upload file to server
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)){
			
 $sql="INSERT INTO tel_tbl(name,designation,division_name,section_name,phone_office,phone_home,intercom, mobile,email,location,image) 
 VALUES('{$name}','{$designation}','{$division_name}','{$section_name}','{$phone_office}','{$phone_home}','{$intercom}','{$mobile}',
 '{$email}','{$location}','{$fileName}')";
 // $sql="INSERT INTO tel_tbl(name,designation) VALUES('{$name}','{$designation}')";
 // $result=mysqli_query($conn,$sql);
 //echo"<script> window.open('../welcome.php','_self')</script>";
// echo "Insert Successfully!!!";
$result=mysqli_query($conn,$sql);
if($result){
                //$statusMsg = "The file ".$fileName. " has been uploaded successfully.";
				header("location:dashboard.php?submitted=successfully");
            }else{
                $statusMsg = "File upload failed, please try again.";
            } 
        }else{
            $statusMsg = "Sorry, there was an error uploading your file.";
        }
    }else{
        $statusMsg = 'Sorry, only JPG, JPEG, PNG, GIF, & PDF files are allowed to upload.';
    }
}else{
    $statusMsg = 'Please select a file to upload.';
}

// Display status message
echo $statusMsg;
	// echo "<div class='alert alert-danger alert-dismissible'>
				// <button type='button' class='close' data-dismiss='alert'> × </button>
				// Whoops! some error encountered. Please try again.";

	// if(mysqli_query($conn,$sql))
	// {
			// //header("Location: home.php");
			// header("location:dashboard.php?submitted=successfully");
			// //echo "Insert Successfully!!!";
	// }
	// else
		// print mysqli_error();
//}

?>