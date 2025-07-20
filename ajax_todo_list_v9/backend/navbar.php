 <?php 
 // session_name('PROJECT1SESSION');
$table=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
 
// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
include_once 'db/database.php';

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
    <a class="navbar-brand text-white" href="#">
      <h2 class="concert-one-regular"><b>DAILY MEETING SCHEDULE</b> <span class="text-warning" style="font-size: 12px; padding: 0; margin: 0; font-style: italic;">Office :
    <?= '-[' . $_SESSION['office'] . ']-' ?>
  </span> </h2>   
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto d-flex align-items-center">
        <li class="nav-item">
          <a href="#addEmployeeModal" class="btn btn-warning me-2 concert-one-regular" data-toggle="modal">
            <i class="fa fa-plus" style="font-size:16px"></i> <span> Add Meeting</span>
          </a>
        </li> 

        <li class="nav-item dropdown concert-one-regular">
        <a href="#" class="btn btn-warning dropdown-toggle me-2 " id="directorsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="fa fa-users" style="font-size:16px; color: blue"></i> Directors & Division
        </a>
        <ul class="dropdown-menu" aria-labelledby="directorsDropdown">
          <?php
          $directors = [
            'chairman' => 'Chairman Sir',
            'dir_com' => 'Director (Commercial)',
            'dir_fin' => 'Director (Finance)',
            'dir_te' => 'Director (T&E)',
            'dir_pi' => 'Director (P&I)',
            'dir_pr' => 'Director (P&R)',
            'ict' => 'ICT Division',
          ];

          foreach ($directors as $key => $label) {
            if ($table != $key) {
               $url = "backend/directors_meeting.php?dir_tbl=$key";
              echo "<li><a class='dropdown-item' href='$url'><i class='fa fa-building-o' style='font-size:16px; color: blue'></i> $label</a></li>";
            }
          }
          ?>
        </ul>
      </li>

        <li class="nav-item">
          <a href="backend/upcoming_meeting.php" class="btn btn-warning position-relative me-2 concert-one-regular">
            <i class="fa fa-clock-o" style="font-size:20px;color:red"></i> Upcoming Meeting 
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
              <?php echo $upcoming_meeting_count; ?>
            </span>
          </a>
        </li>

        <li class="nav-item">
          <!-- <a href="backend/upcoming_meeting.php" class="btn btn-warning position-relative me-2">
            <i class="fa fa-print" style="font-size:20px;"></i> Print            
          </a> -->
           <button type="button" class="btn btn-warning concert-one-regular" id="print"><i class="fa fa-print" style="font-size:16px"></i> Print</button>
        </li>

        <li class="nav-item dropdown concert-one-regular">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <img src="images/bcic_logo.png" alt="Profile Picture" class="navbar-profile-pic" width="30">

          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

           <!--  <a href="backend/search_new.php?table_name=<?= $_SESSION['username'] ?>&val=987" class="btn btn-outline-primary">
              <i class="fa fa-search" style="font-size:16px"></i> 
              <span> Search</span>
          </a> -->
            
            <!-- <li><a class="dropdown-item" href="#"><i class="fa fa-trash" style="font-size:16px"></i> Multiple Delete</a></li> -->
            <li><a class="dropdown-item" href="backend/search_date_range.php?table_name=<?= $_SESSION['username'] ?>&val=987"><i class="fa fa-eye" style="font-size:16px"></i> Show All</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="backend/logout.php"><i class="fa fa-sign-out" style="font-size:16px"></i> Sign Out</a></li>
            

          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>


