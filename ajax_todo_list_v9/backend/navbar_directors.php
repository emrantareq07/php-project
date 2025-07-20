 <?php 

$table=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
 
// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
include_once '../db/database.php';

$today_date=date("Y-m-d");
//$result = mysqli_query($conn,"SELECT * FROM ajax_todo_tbl order by date DESC");
$result = mysqli_query($conn,"SELECT COUNT(*) AS upcoming_meeting_count FROM $table where date>'$today_date'"); 
$row = mysqli_fetch_array($result);
$upcoming_meeting_count = $row['upcoming_meeting_count'];
    // echo $upcoming_meeting_count ;
?>
<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand text-white concert-one-regular" href="#">
      <h2>DAILY <b>MEETING</b> SCHEDULE</h2>
    </a>
    
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto d-flex align-items-center">        
        
         <li class="nav-item concert-one-regular">
          <a href="../dashboard.php" class="btn btn-warning me-2" >
            <i class="fa fa-home" style="font-size:16px"></i> <span> Home Page</span>
          </a>
        </li>        
        <?php
          if($user_type=='user'){
          ?>
           <!-- <li class="nav-item">
          <a href="directors_meeting.php?dir_tbl=chairman" class="btn btn-warning me-2">
            <i class="fa fa-user" style="font-size:16px"></i> <span> Chairman Sir</span>
          </a>
        </li> -->
          <!-- <a href="backend/directors_meeting.php?dir_tbl=chairman" class="btn btn-outline-primary" ><i class="fa fa-building-o" style="font-size:16px"></i> <span>
          <span>Chairman Sir</span></a> -->
          <?php 
          }
          ?>

        <li class="nav-item concert-one-regular">
          <!-- <a href="backend/upcoming_meeting.php" class="btn btn-warning position-relative me-2">
            <i class="fa fa-print" style="font-size:20px;"></i> Print            
          </a> -->
           <button type="button" class="btn btn-warning" id="print"><i class="fa fa-print" style="font-size:16px"></i> Print</button>
        </li>
  
        <li class="nav-item dropdown concert-one-regular">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
             <img src="images/bcic_logo.png" alt="Profile Picture" class="navbar-profile-pic" width="30">
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
            
            <!-- <li><a class="dropdown-item" href="#"><i class="fa fa-trash" style="font-size:16px"></i> Multiple Delete</a></li>
            <li><a class="dropdown-item" href="#"><i class="fa fa-eye" style="font-size:16px"></i> Show All</a></li>
            <li><hr class="dropdown-divider"></li> -->
            <li><a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out" style="font-size:16px"></i> Sign Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>

