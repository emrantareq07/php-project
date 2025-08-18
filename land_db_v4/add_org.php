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
  }
   include_once"includes/header.php";
?>
  
    <div class="container mt-3">
        <?php //include('../view/message.php'); ?>
        <div class="row">
           <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="text-uppercase fw-bold fw-bolder text-muted"><b>Add Organization</b>
                            <a href="org_details.php" class="btn btn-danger float-end"><i class="fa fa-arrow-left"></i> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="manage_user-code.php" method="POST">

                            <div class="mb-3">
                                <label for="org_name">Org. Name</label>
                                <input type="text" name="org_name" id="org_name" class="form-control"> 
                            </div>
                            <div class="mb-3">
                               <label for="address">Address</label>
                            <input type="text" name="address" id="address"  class="form-control">
                            </div>

                            <div class="mb-3">
                               <label for="establish_year">Establishment Year</label>
                            <input type="text" name="establish_year" id="establish_year"  class="form-control">
                            </div>                     

                            <div class="mb-3">
                                <button type="submit" name="save_org" class="btn btn-block btn-primary"><i class="fa fa-save"></i> Save</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
        </div>
    </div>

<?php 

include('includes/footer.php');?>