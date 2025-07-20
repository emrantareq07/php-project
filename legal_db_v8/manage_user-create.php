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
                        <h4 class="text-uppercase fw-bold fw-bolder text-muted"><b>Add User</b>
                            <a href="manage_user.php" class="btn btn-danger float-end"><i class="fa fa-arrow-left" style="font-size:16px"></i>  BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="manage_user-code.php" method="POST">

                            <div class="mb-3">
                                <label for="username">User Name</label>
                                <input type="text" name="username" id="username" class="form-control"> 
                            </div>
                            <div class="mb-3">
                               <label for="password">Password</label>
                            <input type="text" name="password" id="password"  class="form-control">
                            </div>

                            <div class="mb-3">
                                 <div class="form-group"><label>Role</label>
                                    <select class="form-control" id="role" name="role" >
                                        
                                    <option value="" disabled selected>--Select--</option>
                                         
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                    
                                    </select>
                                </div>
                            </div>                          

                            <div class="mb-3">
                                <button type="submit" name="save_student" class="btn btn-block btn-primary"><i class="fa fa-save" style="font-size:16px"></i>  Save</button>
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