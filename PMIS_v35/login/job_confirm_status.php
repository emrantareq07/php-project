<?php
// Include PHPMailer library at the beginning of the script
include('smtp/PHPMailerAutoload.php');

// Define the smtp_mailer function
function smtp_mailer($to, $subject, $msg) {
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'tls';
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 587;
    $mail->IsHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Username = "pmisict@gmail.com";  
    $mail->Password = "jhuimijoiytxcbrw";
    //$mail->Password = "mbcdgmmjwiclhopo";
    $mail->SetFrom("pmisict@gmail.com");
    $mail->Subject = $subject;
    $mail->Body = $msg;
    $mail->AddAddress($to);
    $mail->SMTPOptions = array('ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => false
    ));

    // Uncomment the following lines if you want to debug SMTP
    if (!$mail->Send()) {
        echo $mail->ErrorInfo;
    } else {
        return 'Sent';
    }
}
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['emp_id'])) {
  header("Location: index.php");
  exit();
}

// Check the user role
$role = $_SESSION['role'];

// Display different content based on the user role
if ($role === 'admin'|| $role === 'sadmin') {
  //echo "<h1 class='text-center my-2 text-uppercase text-secondary'>Welcome, Admin!</h1>";
  // Display admin-specific content
  //header("Location: admin_approval.php");
include 'db.php';
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin apprival</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
   

	<div class="container-fluid border shadow rounded pt-3 my-2 p-3">
	  <!-- Content here --> 
	  <div class="row">
      <h1 class='text-center my-1 text-uppercase text-secondary'>Welcome, Admin!</h1>
	  	<div class="col-sm-2"><hr>
	  		<p>
	  			<center><a class="btn btn-primary " href="manage_user.php"><i class="fa fa-users"></i> Manage User</a></center>
	  		
	  		</p>
	  		<!-- <center><a class="btn btn-primary " href="manage_user.php">Pending Request</a>        
        </center> -->
        <!-- <p>
        <center><a class="btn btn-primary" href="job_confirm_status.php">Job Confirmation Status</a></center></p>
              -->   <p>
        <center><a class="btn btn-danger" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></center></p>
      <p>
        <center>

         <?php 
          if ($role === 'admin') {
              echo '<a href="admin_dashboard.php?role=' . $_SESSION["role"] . '" class="btn btn-primary "><i class="fa fa-arrow-left"></i> Previous Page</a>';
          }
          else {
              echo '<a href="super-admin_dashboard.php?role=' . $_SESSION["role"] . '" class="btn btn-primary "><i class="fa fa-arrow-left"></i> Previous Page</a>';
          }
          ?>
          </center></p>
	  	</div>
	  	<div class="col-sm-10">

	  		<h1 class="text-white text-center text-uppercase bg-dark bg-outline-info rounded"> Job Confirmation Status</h1>
	  	<div class="table-responsive">
	  	<table class="table table-bordered table-striped text-center" id="users">
		  <thead>
		    <tr>
		      <th>Emp ID</th>
		      <th>Emp Type</th>
          <th>Name</th>
		      <th>Designation</th>
		      <th>Office</th>
          <th>Mobile NO.</th>
          <th>Email</th>
          <th>NID</th>
          
          <th>Picture</th>
          <th>Action</th>
		    </tr>
		  </thead>
      <?php
    // $sql="select * from users_tbl where status='pending' ORDER BY ASC";
    $sql="SELECT * From users where job_confirm_status ='pending' order by id ASC";
  $result=mysqli_query($conn,$sql);
  while($row = mysqli_fetch_array($result)) {
    ?>
  <tbody>

    <tr>
      <td><?php echo $row['emp_id'];?></td>
      <td><?php echo $row['employee_type'];?></td>
      <td><?php echo $row['name'];?></td>
      <td><?php echo $row['designation'];?></td>
      <td><?php echo $row['place_of_posting'];?></td>
      <td><?php echo $row['mobile_no'];?></td>
      <td><?php echo $row['email'];?></td>
      <td><?php echo $row['nid'];?></td>
      <!-- <td><?php echo $row['image'];?></td> -->
      <td><?php 
        if ($row['image'] != '' && file_exists('' . $row['image'])) {
      echo "<img class='rounded rectangle border border-muted' src='" . $row['image'] . "' width='50' height='50'";
  } else {
      echo "<img class='rounded-circle border border-muted' src='image/avatar.png' width='30' height='30'>";
 }?></td>
      <!-- <td><?php echo $row['image'];?></td> -->
      <!-- <td><img src="image/<?php echo $row['image']; ?>" alt="Image"></td> -->
      <!-- <td><img src="<?php echo 'image/' . $row['image']; ?>" alt="Image"></td> -->
      <!-- <td><?php echo "<img class='rounded-circle border border-muted' src='image/" . $row['image'] . "' width='30' height='30'>"; ?></td> -->
      
      <td>
      	<form action="job_confirm_status.php" method="POST">
      		<input type="hidden" name="id" value="<?php echo $row['id'];?>"/>
      		<input type="submit" name='approve' class="btn btn-primary btn-xs"  value="Approve"/>
      		<input type="submit" name='deny' class="btn btn-danger btn-xs" value="Deny"/>

      	</form>
      </td>
    </tr>
  
  <?php
}
?>
 </tbody>
  </table>
</div>

	  	</div>
	  <!-- 	<div class="col-sm-2">
        <hr><center></center></div> -->

</div>	  

</div>


<?php
if(isset($_POST['approve'])){

$sql3 = "SELECT MAX(emp_id) AS emp_id FROM basic_info";
$result3 = mysqli_query($conn, $sql3);
$row3 = mysqli_fetch_array($result3);

// Check if a record is found before extracting emp_id
if ($row3 && isset($row3['emp_id'])) {
    $emp_id = $row3['emp_id'];

} else {
    $emp_id = '5620-0';
    echo "No record found in the database. Using default emp_id: $emp_id<br>";
}

$explodeEmpId1 = explode("-", $emp_id);
// Get the first part of the exploded array
$explodeEmpId = $explodeEmpId1[0]+1;
// Output the result
//echo $explodeEmpId ."<br>";
// Separate the four digits
$first_digits = substr($explodeEmpId, 0, 1);
$second_digits = substr($explodeEmpId, 1, 1);
$third_digits = substr($explodeEmpId, 2, 1);
$fourth_digits = substr($explodeEmpId, 3, 1);
    // Output concatenated digits for debugging
    //echo $first_digits. "<br>" . $second_digits . "<br>" . $third_digits . "<br>" . $fourth_digits. "<br>";
// }

// 2nd step
$first_digit_2nd_step = (int)$first_digits[0]; // Assuming you want the first element
//echo $first_digit_2nd_step . "<br>";

$second_digit_2nd_step = (int)$second_digits[0] * 2; // Assuming you want the first element
//echo $second_digit_2nd_step . "<br>";

$third_digit_2nd_step = (int)$third_digits[0]; // Assuming you want the first element
//echo $third_digit_2nd_step . "<br>";

$fourth_digit_2nd_step = (int)$fourth_digits[0] * 2; // Assuming you want the first element
//echo $fourth_digit_2nd_step . "<br>";

function sum($num) {
    $sum = 0;
    for ($i = 0; $i < strlen($num); $i++) {
        $sum += $num[$i];
    }
    return $sum;
}

$num = "$second_digit_2nd_step";
$sum_2nd_digit = sum($num);

function sum1($num1) {
    $sum = 0;
    for ($i = 0; $i < strlen($num1); $i++) {
        $sum += $num1[$i];
    }
    return $sum;
}

$num1 = "$fourth_digit_2nd_step";
$sum_4th_digit = sum1($num1);

$result = (int)$first_digit_2nd_step + $sum_2nd_digit + (int)$third_digit_2nd_step + $sum_4th_digit;

if ($result <= 10) {
    $last_digit = 10 - $result;
} elseif ($result <= 20) {
    $last_digit = 20 - $result;
} elseif ($result <= 30) {
    $last_digit = 30 - $result;
} elseif ($result <= 40) {
    $last_digit = 40 - $result;
}

// echo $first_digits[0] . $second_digits[0] . $third_digits[0] . $fourth_digits[0] . '-' . $last_digit;

 $formattedString = $first_digits[0] . $second_digits[0] . $third_digits[0] . $fourth_digits[0] . '-' . $last_digit;
  // echo $formattedString;
  // // Close the database connection if needed
  //  mysqli_close($conn);

  

  $id=$_POST['id'];

  $sql4="SELECT * From users where id='$id'";
  $result4=mysqli_query($conn,$sql4);
  $row = mysqli_fetch_array($result4);

    $emp_id1 = $row['emp_id'];
    // $name = mysqli_real_escape_string($conn, $row['name']);
    // $name = $row['name'];
    // $designation = $row['designation'];
    // $division = $row['division'];
    $email = $row['email'];
    // $place_of_posting = $row['place_of_posting'];

  $sql="UPDATE  users set job_confirm_status='approved', emp_id='$formattedString' where id='$id'";
	$result=mysqli_query($conn,$sql);

  // $sql1="SELECT * From users where id='$id'";
  // $result1=mysqli_query($conn,$sql1);
  
  //$row = mysqli_fetch_array($result1);

    $sql_insert_basic_info="UPDATE  basic_info set emp_id='$formattedString' where emp_id='$emp_id1'";
    $result_basic_info = mysqli_query($conn, $sql_insert_basic_info);
    $sql_insert_job_desc="UPDATE  job_desc set emp_id='$formattedString' where emp_id='$emp_id1'";
     // $sql_insert_basic_info = "INSERT INTO basic_info(emp_id) VALUES('{$emp_id}')";
    $result_job_desc = mysqli_query($conn, $sql_insert_job_desc);

    $sql_insert_edu_info="UPDATE  edu_info set emp_id='$formattedString' where emp_id='$emp_id1'";
     // $sql_insert_basic_info = "INSERT INTO basic_info(emp_id) VALUES('{$emp_id}')";
    $result_edu_info = mysqli_query($conn, $sql_insert_edu_info);


    // $sql_insert_job_desc = "INSERT INTO job_desc(emp_id, place_of_posting) VALUES('{$emp_id}','{$place_of_posting}')";
    // $result_job_desc = mysqli_query($conn, $sql_insert_job_desc);
  
// include('smtp/PHPMailerAutoload.php');
$to1=$email;
$subject1='Here your Emp ID. Plz use Emp ID to login.';
$msg1=$emp_id;

 // echo smtp_mailer('$to1','$subject1','$msg1');
 echo smtp_mailer($to1,$subject1,$msg1);

	echo '<script type="text/javascript">';
  echo 'alert("User Approved!!!");';
  echo 'window.location.href="job_confirm_status.php"';
  echo '</script>';
}
else{

// delete by temp id
  if(isset($_POST['deny'])){
  $id=$_POST['id'];

  $sql_users_img="SELECT * From users where id='$id'";
  $result_users_img=mysqli_query($conn,$sql_users_img);
  
  $row = mysqli_fetch_array($result_users_img);
  $emp_id=$row['emp_id'];

  $sql_users="DELETE from users where emp_id='$emp_id'";
  $result_users=mysqli_query($conn,$sql_users);

  $sql_basic_info="DELETE from basic_info where emp_id='$emp_id'";
  $result_basic_info=mysqli_query($conn,$sql_basic_info);

  $sql_job_desc="DELETE from job_desc where emp_id='$emp_id'";
  $result_job_desc=mysqli_query($conn,$sql_job_desc);

  $sql_edu_info="DELETE from edu_info where emp_id='$emp_id'";
  $result_edu_info=mysqli_query($conn,$sql_edu_info);

  $sql_award_and_penalty="DELETE from award_and_penalty where emp_id='$emp_id'";
  $result_award_and_penalty=mysqli_query($conn,$sql_award_and_penalty);

  $sql_childs_info="DELETE from childs_info where emp_id='$emp_id'";
  $result_childs_info=mysqli_query($conn,$sql_childs_info);

  $sql_childs_info="DELETE from childs_info where emp_id='$emp_id'";
  $result_childs_info=mysqli_query($conn,$sql_childs_info);

  $sql_childs_info="DELETE from childs_info where emp_id='$emp_id'";
  $result_childs_info=mysqli_query($conn,$sql_childs_info);

  $sql_childs_info="DELETE from childs_info where emp_id='$emp_id'";
  $result_childs_info=mysqli_query($conn,$sql_childs_info);
  
   if ($row['image'] != '' && file_exists('' . $row['image'])) {
       unlink($row['image']);
    } 

  echo '<script type="text/javascript">';
  echo 'alert("User Denied!");';
  echo 'window.location.href="admin_dashboard_1.php"';
  echo '</script>';
}


//   $username=$_POST['username'];
//   $email=$_POST['email']; 

// $sql="select * from users_tbl where email='$email'";
// $result=mysqli_query($conn,$sql);
}
?>



 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
  
  </body>
</html>


<?php
} else {
  echo "<h1>Welcome, User!</h1>";
  // Display user-specific content
  header("Location: user_dashboard.php");
}
?>


