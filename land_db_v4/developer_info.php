<?php
session_start();
  
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
$val=0;     
if(isset($_GET['val'])){
 $val= $_GET['val']; 
  }
include_once"includes/header.php";
?> 

<div class="container my-4 ">        
<div class="row">
  <div class="col-sm-2"></div>
<div class="col-sm-8">
  <h2 class="text-center text-success fw-bold">Bangladesh Chemical Industries Corporation</h2>
  <div class="row">
    <div class="col-md-6 mb-3">
      <div class="card" style="width: 100%;">
        <img class="img-fluid w-100" style="height: 200px; width: 100px;" src="images/002.jpg" alt="">
        <div class="card-body">
          <h5 class="card-title text-info fw-bold">MD.TAREQ EMARN</h5>
          <span class="card-text">PROGRAMMER, </span>
          <span class="card-text">ICT DIVISION </span>
          <span class="card-text">01671583637 emrantareq09@gmail.com</span>
          <br>
          <a href="#" class="btn btn-primary">Details</a>
        </div>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="card" style="width: 100%;">
        <img class="img-fluid w-100" style="height: 200px; width: 100px;" src="images/001.jpg" alt="">
        <div class="card-body">
          <h5 class="card-title text-info fw-bold">SHANEWORN BHADRA</h5>
          <span class="card-text">ASSISTANT PROGRAMMER, </span>
          <span class="card-text">ICT DIVISION, </span>
          <span class="card-text">01878072812 shanewornbhadra@gmail.com</span>
          <a href="#" class="btn btn-primary">Details</a>
        </div>
      </div>
    </div>
  </div>
</div>

  <div class="col-sm-2 pt-5">
    <center>
      
        <a href="admin-dashboard.php" class="btn btn-primary btn-block text-center"><i class="fa fa-arrow-left" style="font-size:16px"></i>  Back</a>
       
    </center>
    
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
<div class="col-12 pt-0">
              <center>
            	<p class=".fs-6 text mb-0 my-1 fw-light font-monospace text-muted"><small>[--Design & Developed by ICT Division, BCIC--]</small></p>
            </center>
            </div>
            