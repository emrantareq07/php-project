<?php
session_start();
$role=$_SESSION['role'];
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
  <div class="col-sm-8 ">    
      <h2 class="text-center text-primary fw-bold shadow border border-2 border-success">Bangladseh Chemical Industries Corporation</h2>       

<div class="my-2">    
 <p class="text-muted pt-2" style="text-align: justify;">Bangladesh Chemical Industries Corporation (BCIC), fully owned by the Government, was established in July, 1976 by a presidential Order. The Corporation is now managing 13 large and medium size industrial enterprises engaged in producing a wide range of products like Urea, TSP, DAP, Paper, Cement, Glass sheet, Hardboard, Sanitary ware & Insulator etc<br><br>
Bangladesh Chemical Industries Corporation (BCIC) housed in its own 20 storied building at 30-31, Dilkusha C/A, Dhaka is one of the largest conglomerates of the country, fully owned by the Govt. came into being on 1st July  1976 through merger of three erstwhile  corporations viz Bangladesh Fertilizer, Chemical and Pharmaceutical Corporation (BFCPC), Bangladesh Paper and Board Corporation (BPBC) and Bangladesh Tanneries Corporation (BTC). Bangladesh Chemical Industries Corporation is entrusted with the task of supervision, co-ordination & control of the enterprises under its management & for developing new industries in the Chemical & allied Sector.<br><br>
BCIC started functioning in 1976 with 88 nationalized enterprises under its administrative control. Since then as per Govt. policy a large number of small & medium size enterprises have been either been sold out to the private entrepreneurs transfered to their former owners. At present BCIC is managing 13 large & medium size industrial enterprises- Eight fertilizer factories, one Paper mill & Four other chemical & allied industrial units producing Urea,TSP, DAP, Paper, Cement, Glass sheet, Hardboard, Sulphuric Acid, Sanitary ware, Insulator, Tiles & Fire bricks etc.

           </p>
      </div>
  </div>
  <div class="col-sm-2 pt-0">
    <center>
      <?php 
      if($_SESSION['role']=='admin' || $_SESSION['role']=='sadmin'){
        ?>
        <a href="admin-dashboard.php" class="btn btn-primary btn-block text-center"><i class="fa fa-arrow-left" style="font-size:16px"></i>  Back</a>
       <?php
        }        
      ?>
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