<?php
//session_start();
//session_name('emd_rent_db');
include_once '../db/database.php';
include_once 'header.php';
require_once("config.php");

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}

$username=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];

$tenants_tbl = $_GET['tenants_tbl'] ?? ''; 

if($tenants_tbl!=''){
  $tenants_tbl = $_GET['tenants_tbl'] ?? ''; // Retrieve the tenants table from the URL
  $_SESSION['tenants_tbl']=$_GET['tenants_tbl'];
  $tenants_tbl=$_SESSION['tenants_tbl'];
  // Check if table name is valid to prevent SQL injection
// include_once 'table_list.php';
}

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
        <?php 
        if($tenants_tbl!=''){        
        ?>
        <li class="nav-item">
          <a href="bill_form.php?tenants_tbl=<?php echo $_SESSION['tenants_tbl']; ?>" class="btn btn-warning me-2 concert-one-regular fw-bold">
            <i class="fa fa-plus" style="font-size:16px"></i> <span> Add </span>
          </a>
        </li> 
        <!-- <li class="nav-item">
          <a href="tenants_details.php?tenants_tbl=<?php echo $_SESSION['tenants_tbl']; ?>" class="btn btn-warning me-2 concert-one-regular fw-bold">
            <i class="fa fa-arrow-left" style="font-size:16px"></i> <span> Back </span>
          </a>
        </li>  -->
         <li class="nav-item">
          <a href="search_date_range.php?tenants_tbl=<?php echo $_SESSION['tenants_tbl']; ?>" class="btn btn-warning position-relative me-2 concert-one-regular fw-bold ">
            <i class="fa fa-search" ></i> Search             
          </a>
        </li>

        <li class="nav-item">
          <a href="dashboard.php" class="btn btn-warning position-relative me-2 concert-one-regular fw-bold">
            <i class="fa fa-arrow-left" ></i> Back             
          </a>
        </li>
        <?php 
          }
        else{       
        ?>
         <li class="nav-item">
          <a href="tenants_list.php" class="btn btn-warning position-relative me-2 concert-one-regular ">
            <i class="fa fa-plus" ></i> Add Tenants             
          </a>
        </li>
        
       <li class="nav-item dropdown concert-one-regular single-column-dropdown fw-bold">
      <a href="#" class="btn btn-warning dropdown-toggle me-2" id="directorsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-building" style="font-size:16px; color: blue"></i> Tenants List
      </a>
      <!-- <ul class="dropdown-menu" aria-labelledby="directorsDropdown" style="width: 700px;"> -->
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="directorsDropdown" style="max-height: 700px; overflow-y: auto; width: 700px;">
      <?php
      // SQL query to fetch data from the 'company' table
      $sql_AA = "SELECT `table_name`, `tenants_name` FROM `company`";
      $result11 = mysqli_query($conn, $sql_AA);

      // Check if the query returned any rows
      if ($result11 && mysqli_num_rows($result11) > 0) {
          // Loop through each row and generate the dropdown menu items
          while ($row = mysqli_fetch_assoc($result11)) {
              $table_name = $row['table_name'];
              $tenants_name = $row['tenants_name'];

              // Check if the current table is not the active one
              if ($table != $table_name) {
                  $url = "tenants_details.php?tenants_tbl=" . urlencode($table_name) . "&tenants_name=" . urlencode($tenants_name);
                  echo "<li><a class='dropdown-item' href='$url'><i class='fa fa-building-o' style='font-size:16px; color: blue'></i> $tenants_name</a></li>";
              }
            }
          }
       ?>
      </ul>
    </li>
    <?php
      }    
    ?> 
      <li class="nav-item">
         <button type="button" class="btn btn-warning concert-one-regular" id="print"><i class="fa fa-print" style="font-size:16px"></i> Print</button>
      </li>&nbsp;&nbsp;
    <li class="nav-item">
      <form id="downloadForm" action="download_database.php" method="post">
          <button class="btn btn-warning" type="submit" name="submit">
              <i class="fa fa-download" style="font-size:16px"></i> Download Database
          </button>
      </form>
      </li>
      <li class="nav-item dropdown concert-one-regular">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="images/bcic_logo.png" alt="Profile Picture" class="navbar-profile-pic" width="30">
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
         <!-- <li><a class="dropdown-item" href="backend/search_date_range.php?table_name=<?= $_SESSION['username'] ?>&val=987"><i class="fa fa-eye" style="font-size:16px"></i> Show All</a></li> -->
          <li><a class="dropdown-item" href="#"><i class="fa fa-eye" style="font-size:16px"></i> Show All</a></li>
          <li><hr class="dropdown-divider"></li>
          <li><a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out" style="font-size:16px"></i> Sign Out</a></li>  
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
<style type="text/css">
.single-column-dropdown .dropdown-menu {
/*  display: block !important;*/
  column-count: 3; /* Ensure a single column */
}
</style>