<?php 
session_name('dfms');
session_start();
$table=$_SESSION['username'];
$user_type=$_SESSION['user_type'];
echo $user_type;
// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}
require_once('../db/db.php');
include('../include/header.php');
?>  
    <div class="container mt-3">
        <?php //include('../view/message.php'); ?>
        <div class="row">
           <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="text-uppercase fw-bold fw-bolder text-muted"><b>Add User</b>
                            <a href="manage_user.php" class="btn btn-danger float-end"><i class="fa fa-arrow-left"></i> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="manage_user-code.php" method="POST">

                            <div class="mb-3">
                                <label for="username">User Name</label>
                                <input type="text" name="username" id="username" class="form-control" required> 
                            </div>
                            <div class="mb-3">
                               <label for="email">Email</label>
                            <input type="email" name="email" id="email"  class="form-control">
                            </div>
                            <div class="mb-3">
                               <label for="password">Password</label>
                            <input type="text" name="password" id="password"  class="form-control" required>
                            </div>
                            <div class="mb-3">
                                 <div class="form-group">
                                    <label>User Type</label>
                                    <select class="form-select form-select-lg mb-3 " name="user_type" aria-label=".form-select-lg example">
                                    <option value="" disabled selected>--Select--</option>
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div> 
                              <div class="mb-3">
                                 <div class="form-group">
                                    <label>Product Type</label>
                                    <select class="form-select form-select-lg mb-3 " name="product_type" aria-label=".form-select-lg example">
                                    <option value="" disabled selected>--Select--</option>
                                    <option value="urea">Urea</option>
                                    <option value="non-urea">Non-Urea</option>
                                    </select>
                                </div>
                            </div>  
                            <div class="mb-3">
                                <button type="submit" name="save" class="btn btn-block btn-primary"><i class="fa fa-save"></i></span> Save</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
        </div>
    </div>

<?php 

include('../include/footer.php');?>