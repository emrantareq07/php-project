<?php
session_start();

$code = $_SESSION['code'];
$role=$_SESSION['role'];
require_once("config/config.php");
require_once("db/db.php");
if(isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
  include_once(ROOT_PATH.'/libs/function.php');
  $usercredentials = new DB_con();

  // fetching username from either session or cookies condition
  $uname = $uun = $uup = "";
  if (isset($_SESSION["uname"])) {
    $uname  = $_SESSION['uname'];
  }
  if (isset($_COOKIE['user_login'])) {
    $uname  = $_COOKIE['user_login'];
  } 
  $query = "SELECT * FROM tblusers WHERE Username='$uname'";
  $result = $usercredentials->runBaseQuery($query);

  foreach ($result as $k => $v) {
    $uun = $result[$k]['Username'];
    $uup = $result[$k]['Password'];
    //$role = $result[$k]['role'];
  }
  //include_once "includes/header.php";
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BCIC Land DB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chango&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Chango&family=Mochiy+Pop+P+One&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Akaya+Telivigala&family=Alkatra:wght@400..700&family=Chango&family=Mochiy+Pop+P+One&display=swap" rel="stylesheet">
    <style type="text/css">
      body {
        width: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.5) 50%, rgba(0,0,0,0.5) 50%), url(images/5.jpg);
        background-repeat: no-repeat;
        background-position: center;
        background-size: cover;
        height: 92vh;
      }
      .chango-regular {
        font-family: "Chango", sans-serif;
        font-weight: 400;
        font-style: normal;
      }
      .mochiy-pop-p-one-regular {
        font-family: "Mochiy Pop P One", sans-serif;
        font-weight: 400;
        font-style: normal;
      }
      .akaya-telivigala-regular {
        font-family: "Akaya Telivigala", system-ui;
        font-weight: 400;
        font-style: normal;
      }
      .dropdown-menu-columns {
            columns: 2;
            column-gap: 1rem;
        }

    </style>
    <link rel="icon" type="image/gif/png" href="images/BCIC_logo.jpg">
</head>
<body>
<div class="container-fluid mt-3">
    <div class="row">
      <div class="col-sm-4"><h2 class="text-light mochiy-pop-p-one-regular"><b>BCIC EMD</b></h2></div>
      <div class="col-sm-8 ">
        <ul class="nav float-end akaya-telivigala-regular">
          <li class="nav-item">
            <a class="nav-link text-white" href="about.php">ABOUT</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white text-uppercase" href="add_new.php">Land DETAILS</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              REPORT
            </a>
            <ul class="dropdown-menu dropdown-menu-columns" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="all_info_land_report.php?val=PBCICH.O.!%b@IWORNE**7">BCIC H.O.</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=PTAREQ!%b@IWORNE**7">SFCL</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=@!TAREQ!%b@IWORNE**7">JFCL</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=1*22SHANETAREQ3">GPUFPLC</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=1*22SAFCCLRQ%3">AFCCL</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=8*22SH$$$CUFLEQ*3">CUFL</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=1*22S@DAPFCLEQ%3">DAPFCL</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=5*22SH***TSPCLREQ$3">TSPCL</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=0*22@@@SKPMLAREQ3">KPML</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=6*22SHA^^UGSFLAEQ)3">UGSFL</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=1*22SHA!TICIEQ@3">TICI</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=3*22SHDLCLQ*3">DLCL</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=1*22$*%^CCCLLQ$*3">CCCL</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=3*22SKNMLQ*%3">KNML</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=5*22SKHBMLQ*3">KHBM</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=7*22S@@CCCQ*3">CCC</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=6*22S@@CCCQ*5">BISFL</a></li>
              <li><a class="dropdown-item" href="all_info_land_report.php?val=8*22SHANETAREQ">All Info. Same Page</a></li>
              <div class="dropdown-divider"></div>
                    <a href="user-dashboard.php?val=STAREK!%b@IWORNE**4" class="dropdown-item">Search by Mouza</a>              
            </ul>
          </li>
          <?php if($_SESSION['role']=='sadmin'){?>
          <li class="nav-item">
            <a class="nav-link text-white" href="manage_user.php?val=2">MANAGE USER</a>
          </li>
          <?php }?>
          <li class="nav-item">
            <a class="nav-link text-white text-uppercase" href="org_details.php">Add Org.</a>
          </li>
          <?php if($_SESSION['role']=='sadmin'){?>
          <li class="nav-item">
            <a class="nav-link text-white text-uppercase" href="logfile.php" target="_blank">Log file</a>
          </li>
          <?php }?>
          <li class="nav-item">
            <a class="nav-link text-white text-uppercase" href="developer_info.php">Developer</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="logout.php" >LOGOUT</a>
          </li>
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-1"></div>
      <div class="col-sm-10 text-center">
        <br><br><br><br><br><br><br><br><br>
        <h3 class="text-center text-white text-uppercase">Bangladesh Chemical Industries Corporation </h3>
        <form class="text-left text-white" id="downloadForm" action="download_database.php" method="post">
          <button class="btn btn-warning" type="submit" name="submit"><i class="fa fa-download" style="font-size:16px"></i> Download Database</button>
        </form>    
      </div>
      <div class="col-sm-1"></div> 
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
} else {
  header('location:index.php');
} 
?>
