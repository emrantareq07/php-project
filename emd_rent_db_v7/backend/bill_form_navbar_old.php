<?php 
// Start output buffering
ob_start();
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

// Set tenant name based on table using switch case
$tenants_name = '';
switch ($tenants_tbl) {
   case 'abbankho_tbl':
        $tenants_name = 'AB Bank H.O.';
        break;
    case 'poton_tbl':
        $tenants_name = 'Poton Traders';
        break;
    case 'mollik_tbl':
        $tenants_name = 'Mollik Traders';
        break;

    case 'erres_tbl':
        $tenants_name = 'E. R. Resourses';
        break;

    case 'mrtrading_tbl':
        $tenants_name = 'M. R. Trading';
        break;
    case 'motalibasso_tbl':
        $tenants_name = 'Motalib & Associates';
        break;
    case 'romanaent_tbl':
        $tenants_name = 'Romana Enterprise';
        break;
    case 'rafirachi_tbl':
        $tenants_name = 'Rafi & Rachi';
        break;
    case 'oyasistec_tbl':
        $tenants_name = 'Oyasis Technologies';
        break;
    case 'mehedient_tbl':
        $tenants_name = 'Mehedi Enterprise';
        break;
    case 'multiwaysmkt_tbl':
        $tenants_name = 'Multi-Ways Marketing';
        break;
    case 'demano_tbl':
        $tenants_name = 'Demano Ltd.';
        break;
    case 'beximco_tbl':
        $tenants_name = 'BEXIMCO';
        break;
    case 'bdg_tbl':
        $tenants_name = 'Bangladesh Development Group';
        break;
    case 'creativep_tbl':
        $tenants_name = 'Creative Papers';
        break;
    case 'pp_tbl':
        $tenants_name = 'Petroleum Products';
        break;
    case 'sadg_tbl':
        $tenants_name = 'South Asia Dev. Group';
        break;
    case 'gp_tbl':
        $tenants_name = 'Grameen Phone';
        break;
    case 'carbon_tbl':
        $tenants_name = 'Carbon Holdings';
        break;
    case 'bcicsamiti_tbl':
        $tenants_name = 'BCIC Samiti';
        break;
    case 'deshb_tbl':
        $tenants_name = 'Desh Builders';
        break;
    case 'hirajheelh_tbl':
        $tenants_name = 'Hirajheel Hotel';
        break;
    case 'cccl_tbl':
        $tenants_name = 'Chatak Cement Pro';
        break;
    case '13buf_tbl':
        $tenants_name = '13 Buffer Project';
        break;
    case '34buf_tbl':
        $tenants_name = '34 Buffer Project';
        break;
    case 'daycare_tbl':
        $tenants_name = 'Day Care';
        break;
    case 'pdb_tbl':
        $tenants_name = 'BPDB';
        break;
    case 'fahiment_tbl':
        $tenants_name = 'Fahim Enterprise';
        break;
    case 'shamin_tbl':
        $tenants_name = 'Sharmin Akter';
        break;
    case 'chainntrad_tbl':
        $tenants_name = 'Chaina Traders';
        break;
    case 'nment_tbl':
        $tenants_name = 'N M Enterprise';
        break;
    case 'rajobali_tbl':
        $tenants_name = 'Rajob Ali';
        break;
    case 'arjufood_tbl':
        $tenants_name = 'Arju Food & Restaurant';
        break;
    case 'rajuk_tbl':
        $tenants_name = 'Rajuk';
        break;

        case 'rajuk148_tbl':
        $tenants_name = 'Rajuk 148/Ka';
        break;
    case 'abbankpb_tbl':
        $tenants_name = 'AB Bank Principle Branch';
        break;
    case 'dami1':
        $tenants_name = 'Dami';
        break;
    case 'royelton_tbl':
        $tenants_name = 'Royelton Leaker Coating';
        break;
    case 'leaker_tbl':
        $tenants_name = 'Leaker Coating';
        break;
    case 'pusti_tbl':
        $tenants_name = 'Super Oil Rifinary (Pusti)';
        break;
    case 'kpml_tbl':
        $tenants_name = 'KPML';
        break;
    case 'nbpm_tbl':
        $tenants_name = 'NBPM';
        break;
    default:
        die("Invalid tenants table.");
}
$valid_tables = [
    'abbankho_tbl', 'poton_tbl', 'mollik_tbl', 'mrtrading_tbl', 'motalibasso_tbl', 
    'romanaent_tbl', 'rafirachi_tbl', 'oyasistec_tbl', 'mehedient_tbl', 
    'multiwaysmkt_tbl', 'demano_tbl', 'beximco_tbl', 'bdg_tbl', 'creativep_tbl', 
    'pp_tbl', 'sadg_tbl', 'gp_tbl', 'carbon_tbl', 'bcicsamiti_tbl', 'deshb_tbl', 
    'hirajheelh_tbl', 'cccl_tbl', '13buf_tbl', '34buf_tbl', 'daycare_tbl', 
    'pdb_tbl', 'fahiment_tbl', 'shamin_tbl', 'chainntrad_tbl', 'nment_tbl', 
    'rajobali_tbl', 'arjufood_tbl', 'erres_tbl', 'rajuk_tbl', 'rajuk148_tbl', 
    'abbankpb_tbl', 'dami1', 'royelton_tbl', 'leaker_tbl', 'pusti_tbl', 
    'kpml_tbl', 'nbpm_tbl'
];
if (!in_array($tenants_tbl, $valid_tables)) {
    die("Invalid table name.");
}
ob_end_flush(); 
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
          <!-- <a href="bill_form.php?tenants_tbl=<?php echo $_SESSION['tenants_tbl']; ?>" class="btn btn-warning me-2 concert-one-regular">
            <i class="fa fa-plus" style="font-size:16px"></i> <span> Add </span>
          </a> -->
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
        ob_end_flush(); 
        ?> 
        <!-- <li class="nav-item">          
           <button type="button" class="btn btn-warning concert-one-regular fw-bold" id="print"><i class="fa fa-print" style="font-size:16px"></i> Print</button>
        </li> -->
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


