<?php
session_start();
ob_start(); // Start output buffering
$role = $_SESSION['role'];
  
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

// include_once"includes/header.php";
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <title>BCIC LEGAL</title>
    <link rel="stylesheet" href="css/style1.css">
    <link rel="icon" type="image/gif/png" href="images/BCIC_logo.jpg">
        <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&family=Rubik:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style type="text/css">
    ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    ul li {
        display: inline-block;
        position: relative;
    }

    ul li a {
        display: block;
        padding: 0px;
        text-decoration: none;
        color: #fff; /* Set text color to white */
    }

    ul li:hover ul.dropdown-content {
        display: block;
    }

    ul ul.dropdown-content {
        display: none;
        position: absolute;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 5px; /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        z-index: 1;
        margin-left: 0; /* Remove left margin */
    }

    ul ul.dropdown-content li {
        display: block;
        min-width: 150px;
        margin-left: 10px; /* Remove left margin */
    }

    ul ul.dropdown-content li a {
        padding: 5px;
        color: #000; /* Set text color for dropdown items */
    }

    ul ul.dropdown-content li a:hover {
        background-color: #f1f1f1;
        /*margin-top: 5px;  Add top margin on hover */
    }

    .menu1{
        width: 400px;
        float: left;
        height: 70px;
        margin-left: 70px;
    }
    * {
        font-family: 'Open Sans', sans-serif;
          font-family: 'Tiro Bangla', serif;
      /*  font-family: 'Noto Sans Bengali', sans-serif;*/
          font-family: 'Nikosh', sans-serif;
          font-family: 'Nikosh', serif;
      }
</style>
</head>

<body>

<div class="main">
    <div class="navbar">
        <div class="icon">
            <h2 class="logo">BCIC LEGAL AFFAIRS</h2>
        </div>
    <div class="menu1">
       <ul>
        <li><a href="about.php?val=1">ABOUT</a></li>
        <li><a href="add_new.php">CASE_DETAILS</a></li>
        <li class="dropdown">
        <a href="#">REPORT</a>
        <ul class="dropdown-content">
            <li><a class="dropdown-item" href="all_info_case_report.php?val=PTAREQ!%b@IWORNE**7">বিচারাধীন</a></li>
            <li><a class="dropdown-item" href="all_info_case_report.php?val=@!TAREQ!%b@IWORNE**7">পক্ষে</a></li>
            <li><a class="dropdown-item" href="all_info_case_report.php?val=1**2SHANETAREQ">বিপক্ষে</a></li>
             <li><a class="dropdown-item" href="all_info_case_report.php?val=1*5*2SHANE*TAREQ">সকল মামলা</a></li>
            <li><a href="user-dashboard.php?val=STAREK!%b@IWORNE**4" class="dropdown-item ">খুজুন</a></li>
          </ul>
              </li>                    
              <li><a href="profile.php?val=1">CONTACT_US</a></li>
              <li><a href="logout.php">LOGOUT</a></li>
    </ul>
    </div>

    </div> 
    <div class="content">
        <h1>LAW<br><span>Is</span> <br>Equal For All</h1>

         <h3>Bangladseh Chemical Industries Corporation </h3>

   <form  id="downloadForm" action="dawnload_database.php" method="post">
    <button class="cn" type="submit" name="submit" > Download Database</button>
  </form>               

   <div class="form">
  <h2>OVER VIEW</h2>
       <input type="email" name="email" placeholder="TOTAL CASE: <?php echo htmlspecialchars($count4); ?>" readonly>
        <input type="password" name="" placeholder="IN FAVOUR: <?php echo htmlspecialchars($count1); ?>" readonly>
       <input type="password" name="" placeholder="AGAINST: <?php echo htmlspecialchars($count2); ?>" readonly>
          <input type="password" name="" placeholder="IN PROGESS: <?php echo htmlspecialchars($count3); ?>" readonly> 

        <p class="liw ">Design & Developed By ICT Division, BCIC.</p>

  </div>
       </div>
        </div>
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