<?php
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}

// Check the user role
$role = $_SESSION['role'];

// Display different content based on the user role
if ($role === 'sadmin') {
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
    <title>Super Admin approval</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

  </head>
  <body>
   
<?php include_once('navbar.php');?>
	<div class="container border shadow rounded pt-3 my-2">
	  <!-- Content here --> 
	  <div class="row"><h1 class='text-center my-1 text-uppercase text-secondary'>Welcome, Super Admin!</h1>
	  	<div class="col-sm-2"><hr>
	  		<!-- <p>
	  			<center><a class="btn btn-primary " href="manage_user.php">Manage User</a></center>
				
	  		</p>
			<p>
			<center><a class="btn btn-primary " href="manage_admin.php">Manage Admin</a></center>
			</p> -->
	  		<center><a class="btn btn-primary " href="super-admin_dashboard.php">Privious Page</a></center>
        <p class="text-center text-danger text-bold" marque="blink" ><b>Here Showing All Pending Request </b></p>
	  	</div>
	  	<div class="col-sm-8">
	  		<h1 class="text-white text-center text-uppercase bg-dark bg-outline-info rounded"> Dashboard</h1>
	  	
	  	<table class="table table-bordered table-striped text-center" id="users">
		  <thead>
		    <tr>
		      <th>#</th>
		      <th>Username</th>
		      <th>Email</th>
			  <th>Role</th>
			  <th>Status</th>
		      <th>Action</th>
		    </tr>
		  </thead>
      <?php
    // $sql="select * from users_tbl where status='pending' ORDER BY ASC";
    $sql="SELECT * From users_tbl where status ='pending' order by id ASC";
  $result=mysqli_query($conn,$sql);
  while($row = mysqli_fetch_array($result)) {
    ?>
  <tbody>

    <tr>
      <td><?php echo $row['id'];?></td>
      <td><?php echo $row['username'];?></td>
      <td><?php echo $row['email'];?></td>
	  <td><?php echo $row['role'];?></td>
      <td><?php echo $row['status'];?></td>
      <td>
      	<form action="super-admin_dashboard.php" method="POST">
      		<input type="hidden" name="id" value="<?php echo $row['id'];?>"/>
      		<input type="submit" name='approve' class="btn btn-primary btn-xs"  value="Approve"/>
      		<input type="submit" name='deny' class="btn btn-danger btn-xs" value="Deny"/>
			<input type="submit" name='admin' class="btn btn-danger btn-xs" value="Admin"/>

      	</form>
      </td>
    </tr>
    
  </tbody>
  <?php
}
?>
</table>

	  	</div>
	  	<div class="col-sm-2"><hr><center><a class="btn btn-danger " href="logout.php">Logout</a></center></div>

</div>	  

</div>
<?php
if(isset($_POST['approve'])){
  $id=$_POST['id'];

  $sql="UPDATE  users_tbl set status='approved' where id='$id'";
	$result=mysqli_query($conn,$sql);
	echo '<script type="text/javascript">';
  echo 'alert("User Approved");';
  echo 'window.location.href="super-admin_dashboard.php"';
  echo '</script>';
}
elseif(isset($_POST['admin'])){

  //if(isset($_POST['admin'])){
  $id=$_POST['id'];

  $sql="UPDATE  users_tbl set role='admin' where id='$id'";
  $result=mysqli_query($conn,$sql);
  echo '<script type="text/javascript">';
  echo 'alert("User is now Admin!");';
  echo 'window.location.href="super-admin_dashboard.php"';
  echo '</script>';
}

else{

  if(isset($_POST['deny'])){
  $id=$_POST['id'];

    $sql="DELETE from users_tbl where id='$id'";
  $result=mysqli_query($conn,$sql);
  echo '<script type="text/javascript">';
  echo 'alert("User Denied!");';
  echo 'window.location.href="super-admin_dashboard.php"';
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


