<?php 
session_start();

// if(isset($_GET['val'])){
// $val=$_GET['val'];
// }

// if(isset($_SESSION['username'])){ 
//   $table=$_SESSION['username'];
// }

// $user_type=$_SESSION['user_type'];
// //echo $user_type;
$office_type=$_SESSION['office_type'];
// // Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
else{

  if($office_type== 'port_office' || $office_type == 'bcic_hq' || $office_type == 'factory_office'){
    $table=null;
  }
  else{
    $table=$_SESSION['username'];
  }
}

 require_once('../db/db.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>DFMS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
  <?php 

  if($_SESSION['username']=='bcic_mkt'){

    ?>
    <style>
.navbar-nav .nav-item .profile-picture {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  object-fit: cover;
}
</style>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><img src="../images/bcic_logo.png" alt="Profile" width="40" height="40" class="profile-picture">
    </a><h3 class="text-white montserrat-alternates-bold">BCIC-(SFMS)</h3>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav me-auto">
        <!-- <li class="nav-item">
          <a class="nav-link text-uppercase" href="monthly_demand_set.php?username=<?php echo $_SESSION['username']; ?>">Monthly Demand Set</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-uppercase" href="pipeline_details.php?username=<?=$_SESSION['username']?>">Pipeline Data</a>
        </li> -->
        <!-- <li class="nav-item">
          <a class="nav-link text-uppercase" href="#">Link</a>
        </li> -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-uppercase" href="#" role="button" data-bs-toggle="dropdown">Report</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="report_view.php">Daily Report (MT)</a></li>
            <li><a class="dropdown-item" href="#">Monthly Report</a></li>
            <li><a class="dropdown-item" href="#">Yearly Report</a></li>
          </ul>
        </li>
      </ul>
      <!-- <h1 class="text-center text-white">SFMS<b class="text-success text-uppercase"> (<?=$_SESSION['username'];?>)</b></h1> -->
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <img src="../images/fd.png" alt="Profile" class="profile-picture">
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#"><i class="fa fa-user" style="font-size:15px;color:black"></i> Profile</a></li>
            <li><a class="dropdown-item" href="#"><i class="fa fa-cog" style="font-size:15px;color:black"></i> Settings</a></li>
            <li><a class="dropdown-item" href="./logout.php"><i class="fa fa-sign-out" style="font-size:15px;color:black"></i> Sign Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
    <?php

  }

  ?>

 <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid text-center">
    <a class="navbar-brand " href="#">Smart Fertilizer Monitoring System (SFMS), BCIC.</a>
  </div>
</nav> 

<div class="container-fluid p-1 my-1 border rounded">
	<div class="row">
		<div class="col-sm-12">
			<h1 class="text-dark text-center text-uppercase">Welcome <b class="text-danger "> <?= $_SESSION['username'];?></b> Dashboard</h1>
      <h3 class="text-center text-uppercase">Pipeline Pending List</h3>
  			<!-- <p class="text-dark text-center"> You are a: <b><?= $_SESSION['user_type'];?></b></p> -->
		</div>
	</div>
	<div class="row">		
		<div class="col-sm-6"></div>
		<div class="col-sm-6">
        <span class="float-end">
          <?php 
        if($table!=null){
        ?>
       <a href="urea_form.php" class="btn btn-primary"><i class="fa fa-arrow-left" ></i> Previous Page</a>
        <?php 
        }
        ?>

        <?php 
        if($table==null){

        ?>
        <a href="user_dashboard.php" class="btn btn-primary"><i class="fa fa-arrow-left" ></i> Previous Page</a>
        <a href="add_pipeline_data.php?username=<?=$_SESSION['username']?>" class="btn btn-primary"><i class="fa fa-plus" ></i> Add Data</a>
        <a href="logout.php" class="btn btn-danger">Logout <i class="fa fa-sign-out" ></i></a>
        <?php 
        }
        ?>
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
                <table class='table table-hovered table-stripped table-bordered' id="form-tbl">
                   
                    <thead>
                        <tr>
                          <th class="text-center p-1">#</th>
                            <th class="text-center p-1">Date</th>
                            <th class="text-center p-1">From Factory/Port</th>
                            <th class="text-center p-1">To Buffer/Factory</th>
                            <th class="text-center p-1">Amount (MT)</th>
                            <th class="text-center p-1">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
             // if(isset($_SESSION['username'])){     
                if($table!=null){
                  $query="SELECT * FROM pipeline where to_office='$table' AND status='pending'";               
                  $query_run = mysqli_query($conn, $query);

                }
                else
                {
                    $query = "SELECT * FROM pipeline WHERE status='pending' AND from_office='".$_SESSION['username']."'";
    
                    $query_run = mysqli_query($conn, $query);
                }

                if(mysqli_num_rows($query_run) > 0){
                    foreach($query_run as $row){  
                              //$id=$row['id'];
            ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
                 <td><?php echo $row['date']; ?></td>
                 <td><?php echo $row['from_office']; ?></td>
                <td><?php echo $row['to_office']; ?></td>
                <td><?php echo $row['amount']; ?></td> 
                <td><?php echo $row['status']; ?></td>                                
                <td class="text-center">
                  <?php 
                  if($table!=null){

                  ?>
                  <a href="insert_urea.php?id=<?= $row['id']; ?>&to_office=<?= $row['to_office']; ?>" class="btn btn-primary btn-sm">
                      <i class="fa fa-ok"></i> Accept
                  </a>
                  <?php 
                  }
                  ?>                                
                <a href="pipeline-edit.php?id=<?= $row['id']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit" ></i> Edit</a>
                <a href="manage_user-code.php?id=<?= $row['id']; ?>&action=delete" class="btn btn-danger btn-sm"><i class="fa fa-trash" ></i> Delete</a>

                <!-- <form action="manage_user-code.php" method="POST" class="d-inline">
                    <button type="submit" name="delete_student" value="<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</button>
                </form> -->

                </td>
            </tr>
           
            <?php
                }
            }
            else
            {
                echo "<h5 class='text-danger text-center'> <b>No Record Found !!! </b></h5>";
            }
        ?>
                    </tbody>
                </table>
            </form>
            <!-- Table Form end -->
        </div>
        
    </div>

</div>

</body>

</html>
