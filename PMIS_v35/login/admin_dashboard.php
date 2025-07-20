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
if ($role === 'admin') {
  //echo "<h1 class='text-center my-2 text-uppercase text-secondary'>Welcome, Admin!</h1>";
  // Display admin-specific content
  //header("Location: admin_approval.php");
include 'db.php';
// // Get the pending request count
//   $sql = "SELECT COUNT(*) AS pending_count FROM users WHERE status = 'pending'";
//   $result = mysqli_query($conn, $sql);
//   $row = mysqli_fetch_assoc($result);
//   $pendingCount = $row['pending_count'];

//   // Get the pending request count
//   $sql1 = "SELECT COUNT(*) AS pending_count FROM users WHERE job_confirm_status = 'pending'";
//   $result1 = mysqli_query($conn, $sql1);
//   $row1 = mysqli_fetch_assoc($result1);
//   $pendingCountJobConfirm = $row1['pending_count'];

// Get the combined pending request counts
$sql = "SELECT
           SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
           SUM(CASE WHEN job_confirm_status = 'pending' THEN 1 ELSE 0 END) AS pending_count_job_confirm
        FROM users";

$result = mysqli_query($conn, $sql);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $pendingCount = $row['pending_count'];
    $pendingCountJobConfirm = $row['pending_count_job_confirm'];
} else {
    // Handle the query error
    echo "Error: " . mysqli_error($conn);
}

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BCIC PMIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
  
	<div class="container-fluid border shadow rounded my-2">
	  <!-- Content here --> 
	  <div class="row">
      <h1 class='text-center my-1 text-uppercase text-secondary'>Welcome, Admin!  </h1>
	  	<div class="col-sm-2"><hr>
        <p> <center>
          <!-- <a class="btn btn-primary " href="../dashboard.php">Dashboard</a> -->          
          <a href="../dashboard.php?role=<?php echo $_SESSION['role'] ?>" class="btn btn-primary "><i class="fa fa-gear"></i> Dashboard</a>
        </center></p> 
	  		<p>
	  			<center>
            <a class="btn btn-primary " href="manage_user.php"><i class="fa fa-users"></i> Manage User</a></center>
	  		
	  		</p>


        <p>
        <center><a class="btn btn-primary position-relative" href="job_confirm_status.php" ><i class="fa fa-exchange"></i>  Confirmation Status
           <!-- <span class="badge bg-danger"><?php echo $pendingCount; ?></span> -->
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
    <?php echo $pendingCountJobConfirm; ?>
    <!-- <span class="visually-hidden">unread messages</span> --></span>
        </a></center></p>
                <p>
        <center>
          <a class="btn btn-warning" href="overview.php"><i class="fa fa-eye"></i> Overview</a></center>
        </p>
        <center><a class="btn btn-danger" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></center></p>
        <br>
	  	</div>
	  	<div class="col-sm-10">
  	  <h1 class="text-white text-center text-uppercase bg-dark bg-outline-info rounded "> User Request List
        <a class="btn btn-custom position-relative float-end mt-1 me-1 text-white " >Pending Request      
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
        <?php echo $pendingCount; ?>
        </span>
        </a>
      </h1>

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
          <!-- <th>NID</th> -->          
          <th>Picture</th>
          <th>Action</th>
		    </tr>
		  </thead>
      <?php
    // $sql="select * from users_tbl where status='pending' ORDER BY ASC";
  $sql="SELECT * From users where status ='pending' order by id ASC";
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
      <!-- <td><?php echo $row['nid'];?></td> -->
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
      	<form action="admin_dashboard.php" method="POST">
      		<input type="hidden" name="id" value="<?php echo $row['id'];?>"/>
          <!-- <input type="submit" name='approve_exist' class="btn btn-primary btn-xs"  value="Approve"/>
      		<input type="submit" name='approve' class="btn btn-primary btn-xs"  value="Approve(N)"/>
      		<input type="submit" name='deny' class="btn btn-danger btn-xs" value="Deny"/> -->
          <button type="submit" name="approve_exist" class="btn btn-primary btn-xs">
          <i class="fa fa-check"></i> Approve
          </button>
          <button type="submit" name="approve" class="btn btn-primary btn-xs">
          <i class="fa fa-check"></i> Approve(N)
          </button>
          <button type="submit" name="deny" class="btn btn-danger btn-xs">
          <i class="fa fa-trash"></i> Deny
          </button>

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
// temp emp id status
$sql_pending="SELECT emp_id from users where job_confirm_status='pending'";
$result_pending = mysqli_query($conn, $sql_pending);
if (mysqli_num_rows($result_pending) == 1) {

    $emp_id='1';

  }else{

  $sql_max = "SELECT MAX(emp_id) AS emp_id FROM users where job_confirm_status='pending'";
  $result_max = mysqli_query($conn, $sql_max);
  $row_max = mysqli_fetch_array($result_max);
  $emp_id=(int)$row_max['emp_id']+1;

    }

  $id=$_POST['id'];

  $sql="UPDATE  users set status='approved', emp_id='$emp_id' where id='$id'";
	$result=mysqli_query($conn,$sql);

  $sql1="SELECT * From users where id='$id'";
  $result1=mysqli_query($conn,$sql1);
  
  $row = mysqli_fetch_array($result1);

    $emp_id = $row['emp_id'];
    // $name = mysqli_real_escape_string($conn, $row['name']);
    $name = $row['name'];
    $designation = $row['designation'];
    $division = $row['division'];
    $email = $row['email'];
    // $place_of_posting = $row['place_of_posting'];
    $mobile_no = $row['mobile_no'];
    $email = $row['email'];
    $nid = $row['nid'];

    $sql_insert_basic_info = "INSERT INTO basic_info(emp_id, name, designation, mobile_no,email,nid) VALUES('{$emp_id}', '{$name}', '{$designation}',  '{$mobile_no}', '{$email}', '{$nid}')";
    $result_basic_info = mysqli_query($conn, $sql_insert_basic_info);

  $to1=$email;
  $subject1='Here your Temp Emp ID. Plz use Temp Emp ID to login. After Job confirmation You getting real Emp ID';
  $msg1=$emp_id;

   // echo smtp_mailer('$to1','$subject1','$msg1');
   echo smtp_mailer($to1,$subject1,$msg1);

  	echo '<script type="text/javascript">';
    echo 'alert("User Approved Temporarily!!!");';
    echo 'window.location.href="admin_dashboard.php"';
    echo '</script>';
  }
  // for exist employee
  elseif(isset($_POST['approve_exist'])){
  $id=$_POST['id'];

  $sql="UPDATE  users set status='approved' where id='$id'";
  $result=mysqli_query($conn,$sql);

   $sql1="SELECT * From users where id='$id'";
  $result1=mysqli_query($conn,$sql1);
  
  $row = mysqli_fetch_array($result1);

    $emp_id = $row['emp_id'];
    // $name = mysqli_real_escape_string($conn, $row['name']);
    $name = $row['name'];
    $designation = $row['designation'];
    $division = $row['division'];
    $email = $row['email'];
    // $place_of_posting = $row['place_of_posting'];
    $mobile_no = $row['mobile_no'];
    $email = $row['email'];
    //$nid = $row['nid'];
    $employee_type = $row['employee_type'];
    $sub_cadre_header = $row['sub_cadre_header'];
    $image = $row['image'];

    $sql_insert_basic_info = "INSERT INTO basic_info(employee_type,emp_id, name, designation,sub_cadre_header,division, mobile_no,email,image) VALUES('{$employee_type}','{$emp_id}', '{$name}', '{$designation}', '{$sub_cadre_header}', '{$division}','{$mobile_no}', '{$email}', '{$image}')";
    $result_basic_info = mysqli_query($conn, $sql_insert_basic_info);

  $to1=$email;
  $subject1='You are Granted tor Update your Profile.';
  $msg1='Now Update your Profile';
// echo smtp_mailer('$to1','$subject1','$msg1');
   echo smtp_mailer($to1,$subject1,$msg1);
  echo '<script type="text/javascript">';
  echo 'alert("User Approved");';
  echo 'window.location.href="admin_dashboard.php"';
  echo '</script>';
}
  else{

  if(isset($_POST['deny'])){
  $id=$_POST['id'];

  $sql_users_img="SELECT * From users where id='$id'";
  $result_users_img=mysqli_query($conn,$sql_users_img);
  $row = mysqli_fetch_array($result_users_img);
   //$image=$row['image'];

  $sql_users="DELETE from users where id='$id'";
  $result_users=mysqli_query($conn,$sql_users);

 
  if ($row['image'] != '' && file_exists('' . $row['image'])) {
       unlink($row['image']);
    } 

 

  // $sql_basic_info="DELETE from basic_info where id='$id'";
  // $result_basic_info=mysqli_query($conn,$sql_basic_info);

  echo '<script type="text/javascript">';
  echo 'alert("User Denied!");';
  echo 'window.location.href="admin_dashboard.php"';
  echo '</script>';
    }

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


