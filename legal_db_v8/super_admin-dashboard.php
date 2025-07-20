<?php
session_start();
$code = $_SESSION['code'];   
require_once("config/config.php");
require_once("db/db.php");
if(isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
  include_once(ROOT_PATH.'/libs/function.php');
  $usercredentials=new DB_con();

  //fetching username from either session or cookies condition
  $uname = $uun = $uup = "";
  if (isset($_SESSION["uname"])) {
    $uname  = $_SESSION['uname'];
  }
  if (isset($_COOKIE['user_login'])) {
    $uname  = $_COOKIE['user_login'];
  } 

  $query="SELECT*FROM tblusers  WHERE Username='$uname'";
      $result= $usercredentials->runBaseQuery($query);
      foreach ($result as $k => $v){
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
      }

$query1 = "SELECT count(*) AS user_count FROM legal_tbl where hearing_result like '%পক্ষে%' ";
$result1 = mysqli_query($conn, $query1);
$row1= mysqli_fetch_assoc($result1);
$count1 = $row1['user_count'];

$query2 = "SELECT count(*) AS user_count FROM legal_tbl where hearing_result like '%বিপক্ষে%'";
$result2 = mysqli_query($conn, $query2);
$row2 = mysqli_fetch_assoc($result2);
$count2 = $row2['user_count'];

$query3 = "SELECT count(*) AS user_count FROM legal_tbl where hearing_result like '%বিচারাধীন%'";
$result3 = mysqli_query($conn, $query3);
$row3 = mysqli_fetch_assoc($result3);
$count3 = $row3['user_count'];

$query4 = "SELECT count(*) AS user_count FROM legal_tbl";
$result4 = mysqli_query($conn, $query4);
$row4 = mysqli_fetch_assoc($result4);
$count4 = $row4['user_count'];

 include_once"includes/header.php";
?>     
<style type="text/css">
  body {
    width: 100%;
    background: linear-gradient(to top, rgba(0,0,0,0.5)50%,rgba(0,0,0,0.5)50%), url(images/11.jpg);
    background-repeat:no-repeat;
    background-position: center;
    background-size: cover;
    height: 92vh;
  }
</style>
<div class="container-fluid mt-3">
    <div class="row">
      <div class="col-sm-4"><h1 class="text-secondary  text-white rubik-gemstones-regular "><b>BCIC LEGAL AFFAIRS</b></h1></div>
      <div class="col-sm-8">

        <ul class="nav float-end">
          <li class="nav-item"><a class="nav-link text-white" href="about.php?val=2">ABOUT</a></li>
          <li class="nav-item"><a class="nav-link text-white" href="#">CASE DETAILS</a></li>
<!--           <li class="nav-item">
             <div class="dropdown">
              <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                Report
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="all_info_case_report.php?val=PTAREQ!%b@IWORNE**7">বিচারাধীন</a></li>
                <li><a class="dropdown-item" href="all_info_case_report.php?val=@!TAREQ!%b@IWORNE**7">পক্ষে</a></li>
                <li><a class="dropdown-item" href="all_info_case_report.php?val=1**2SHANETAREQ">বিপক্ষে</a></li>
               <li><a class="dropdown-item" href="all_info_case_report.php?val=1*5*2SHANE*TAREQ">সকল মামলা</a></li>
                <li><a href="user-dashboard.php?val=STAREK!%b@IWORNE**4" class="dropdown-item ">খুজুন</a></li>
              </ul>
            </div>
          </li> -->
          <li class="nav-item"><a class="nav-link text-white" href="manage_user.php?val=2">MANAGE USER</a></li>
          <!-- <li class="nav-item"><a class="nav-link text-white" href="profile.php?val=2">SETTINGS</a></li> -->                    
          <li class="nav-item"><a class="nav-link text-white" href="profile.php?val=2">CONTACT US</a></li>
          <!-- <li class="nav-item"><a class="nav-link text-white" href="logout.php">LOGOUT</a></li> -->
          <li class="nav-item"><a href="goout.php?code=<?php echo $code; ?>" class="nav-link text-white">LOGOUT</a></li>
        </ul>


    </div>
</div>

<br><br>
<div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-6">
    
    <h1 class="text-left text-white fw-5 shadow display-5">LAW<br><span>Is</span> <br>Equal For All</h1>
    <br><br><br>
    <h3 class="text-left text-white text-uppercase">Bangladseh Chemical Industries Corporation </h3>

       <form class="text-left text-white " id="downloadForm" action="dawnload_database.php" method="post">
        <button class="btn btn-warning" type="submit" name="submit" > Download Database</button>
      </form>    
  </div>
  
  <div class="col-sm-4">
    <div class="">
  <!-- <h2 class="badge bg-success text-white">OVER VIEW</h2><br> -->
  <ul class="list-group">

    <li class="list-group-item list-group-item-primary"><h2 class="text-center "><b>OVERVIEW</b></h2></li>

  <li class="list-group-item list-group-item-secondary "><span class="" ><b>TATAL CASE: <?php echo htmlspecialchars($count4); ?></b></span></li>
  <li class="list-group-item list-group-item-secondary"><span class=""><b>IF FAVOUR: <?php echo htmlspecialchars($count1); ?></b></span></li>
  <li class="list-group-item list-group-item-secondary"><span class=""><b>AGAINST: <?php echo htmlspecialchars($count2); ?></b></span></li>
  <li class="list-group-item list-group-item-secondary"><span class=""><b>IN PROGESS: <?php echo htmlspecialchars($count3); ?></b></span></li>
  <!-- <li class="list-group-item list-group-item-danger">Danger item</li>
  <li class="list-group-item list-group-item-primary">Primary item</li> -->
  <li class="list-group-item list-group-item-light"><p class="text-center ">Design & Developed By ICT Division, BCIC.</p></li>
  
</ul>
    

    </div>
  </div>
  <div class="col-sm-1"></div> 
  
  </div>
</div>

    <script src="https://unpkg.com/ionicons@5.4.0/dist/ionicons.js"></script>
</body>
</html>
<?php } else{
  // header('location:login/logout.php');
   header('location:index.php');
  } 
?>