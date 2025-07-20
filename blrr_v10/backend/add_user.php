<?php 
session_name('blrr');
session_start();
$table=$_SESSION['username'];
$user_type=$_SESSION['user_type'];
echo $user_type;

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: ../index.php");
  exit();
}
include_once '../db/database.php';
include_once 'header.php';
?>
  
    <div class="container mt-3">
        <?php //include('../view/message.php'); ?>
        <div class="row">
           <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="text-uppercase fw-bold fw-bolder text-muted"><b>Add User</b>
                            <a href="manage_user.php" class="btn btn-danger float-end"><i class="fa fa-arrow-left" ></i> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="manage_user-code.php" method="POST">
                            <div class="mb-3">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" class="form-control" required> 
                            </div>
                            
                            <div class="mb-3">
                               <label for="password">Password</label>
                            <input type="text" name="password" id="password"  class="form-control" required>
                            </div>

                            <div class="mb-3">
                                 <div class="form-group">
                                    <label>User Type</label>
                                    <select class="form-select form-select mb-3 " name="user_type" aria-label=".form-select example" required>
                                    <option value="" disabled selected>--Select--</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                 <div class="form-group">
                                    <label>Office </label>
                                    <select name="office" id="office" class="form-control">
                                    <option value="" disabled selected>--Select--</option>
                                        <?php
                                        $sql_office = "SELECT division FROM division";
                                        $result_office  = mysqli_query($conn, $sql_office);
                                        while($row  = mysqli_fetch_array($result_office)){
                                         echo "<option value='".$row['division']."'>".$row['division']."</option>";
                                        }?> 
                                        </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-group">
                               <label for="office_title">Office Level Title</label>
                                <input type="text" name="office_title" id="office_title"  class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                 <div class="form-group">
                                    <label>Table Name </label>
                                    <select name="table_name" id="table_name" class="form-control">
                                    <option value="" disabled selected>--Select--</option>
                                        <?php
                                        $sql_office = "SELECT table_name FROM division";
                                        $result_office  = mysqli_query($conn, $sql_office);
                                        while($row  = mysqli_fetch_array($result_office)){
                                         echo "<option value='".$row['table_name']."'>".$row['table_name']."</option>";
                                        }?> 
                                        </select>
                                </div>
                            </div>                            

                            <div class="mb-3">
                                <button type="submit" name="save" class="btn btn-block btn-primary"><i class="fa fa-save" ></i> Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
        </div>
    </div>

<?php 

include('footer.php');?>