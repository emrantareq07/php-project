<?php 
//session_start();
include_once '../db/database.php';
include_once 'header.php';
require_once("config.php");

// Check if the user is already logged in, if not, redirect to the login page
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

$username=$_SESSION['username']; 
$user_type=$_SESSION['user_type'];
$office=$_SESSION['office'];
$tenants_tbl = $_GET['tenants_tbl'] ?? ''; // Retrieve the tenants table from the URL

$sql_option_match = "SELECT * FROM company WHERE table_name LIKE '%" . mysqli_real_escape_string($conn, $tenants_tbl) . "%'";
$result = mysqli_query($conn, $sql_option_match);

$rowoption_match = mysqli_fetch_assoc($result);
$tenants_name = $rowoption_match['tenants_name']; 

?>
<!-- Top Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand text-white" href="#">
      <h2 class="concert-one-regular"><b>Rent Management System</b> <span class="text-warning" style="font-size: 12px; padding: 0; margin: 0; font-style: italic;">Office :
    <?= '-[' . $_SESSION['office'] . ']-' ?>
  </span> </h2>   
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto d-flex align-items-center">
        <li class="nav-item">
           <a href="add_tenants.php" class="btn btn-warning me-2 concert-one-regular">
            <i class="fa fa-plus" style="font-size:16px"></i> <span> Add </span>
          </a> 
        </li>

        <?php 
        if($tenants_tbl){
          ?>
          <li class="nav-item">
          <a href="tenants_details.php?tenants_tbl=<?php echo $_SESSION['tenants_tbl']; ?>" class="btn btn-warning position-relative me-2 concert-one-regular fw-bold">
            <i class="fa fa-arrow-left" ></i> Back             
          </a>
        </li>
          <?php
            }
        else{
          ?>
          <li class="nav-item">
          <a href="dashboard.php" class="btn btn-warning position-relative me-2 concert-one-regular fw-bold">
            <i class="fa fa-arrow-left" ></i> Back             
          </a>
        </li>
        <?php
        }
        ?>       

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
            <li><a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out" style="font-size:16px"></i> Sign Out</a></li>   
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>


