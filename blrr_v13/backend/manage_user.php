<?php 
session_name('blrr');
session_start();
$table=$_SESSION['username'];
$user_type=$_SESSION['user_type'];
$office=$_SESSION['office'];

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
include_once '../db/database.php';
//include_once 'backend/header.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>BLRR</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    @font-face {
        font-family: 'Nikosh', Times, serif;
        src: url(Nikosh.ttf);
    }
    
    .imgcontainer {
      text-align: center;
      margin: 5px 0 12px 0;
      position: relative;
    }
    
    img.avatar {
      width: 25%;
      border-radius: 50%;
    }

    * {
      font-family: 'Open Sans', sans-serif;
      font-family: 'Tiro Bangla', serif;
      font-family: 'Nikosh', sans-serif;
    }
  </style>
</head>
<body>
 <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid text-center">
    <!-- <a class="navbar-brand " href="#">Smart Fertilizer Monitoring System (SFMS), BCIC.</a> -->
    <h2 class="text-light text-center"><b>বিসিআইসি পত্র প্রাপ্তি রেজিস্টার</b> </h2>
          <span class="text-primary fw-bold"><small>[--<?php echo $office; ?>--]</small></span>
  </div>
</nav> 
<div class="container-fluid p-1 my-1 border rounded">
	<div class="row">
		<div class="col-sm-12">
			<h1 class="text-dark text-center ">Welcome <b class="text-danger"> <?= $_SESSION['username'];?></b> Dashboard</h1>
  			<!-- <p class="text-dark text-center"> You are a: <b><?= $_SESSION['user_type'];?></b></p> -->
		</div>
	</div>
	<div class="row">		
		<div class="col-sm-6"><h3 class="text-center text-uppercase">Users List</h3></div>
		<div class="col-sm-6">
        <span class="float-end">
        <a href="sadmin_dashboard.php" class="btn btn-primary"><i class="fa fa-arrow-left" ></i> Previous Page</a>
         <a href="add_user.php" class="btn btn-primary"><i class="fa fa-plus" ></i> Add User</a>
        <a href="add_office.php" class="btn btn-primary"><i class="fa fa-plus" ></i> Add Office</a>
        <a href="logout.php" class="btn btn-danger">Logout <i class="fa fa-sign-out" ></i></a>
        </span>
		</div>
	</div>

    <div class="row">
        <div class="col-12">
            <!--  -->
            <hr>
        </div>
        
        <div class="col-12">
            <!-- Table Form start -->
            <form action="" id="form-data">
                <input type="hidden" name="id" value="">
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
              <table class='table table-hover table-striped table-bordered' id="form-tbl">
                <thead>
                  <tr>
                    <th class="text-center p-1">#</th>
                    <th class="text-center p-1">Username</th>
                    <th class="text-center p-1">Password</th>
                    <th class="text-center p-1">User Type</th>
                    <th class="text-center p-1">Office</th>
                    <th class="text-center p-1">Office Title</th>
                    <th class="text-center p-1">Table Name</th>
                    <th class="text-center p-1">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  if (isset($_SESSION['username'])) {                
                    $query = "SELECT * FROM users";             
                  }       

                  $query_run = mysqli_query($conn, $query);
                  if (mysqli_num_rows($query_run) > 0) {
                    foreach ($query_run as $row) {
                  ?>
                  <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['password']; ?></td>
                    <td><?php echo $row['user_type']; ?></td> 
                    <td><?php echo $row['office']; ?></td> 
                    <td><?php echo $row['office_title']; ?></td>  
                    <td><?php echo $row['table_name']; ?></td>                             
                    <td class="text-center">                
                      <a href="manage_user-edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
                      <a href="manage_user-code.php?id=<?= $row['id']; ?>&action=delete" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Delete</a>
                    </td>
                  </tr>           
                  <?php
                    }
                  } else {
                    echo "<h5 class='text-danger'><b>No Record Found!!!</b></h5>";
                  }
                  ?>
                </tbody>
              </table>
            </div>

            </form>
            <!-- Table Form end -->
        </div>
        
    </div>

</div>

</body>

</html>
