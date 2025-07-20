<?php 
session_start();
// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['emp_id'])) {
    header("Location: login.php");
    exit();
}

// Check the user role
$role = $_SESSION['role'];
include('includes/header.php');
// Display different content based on the user role
// if ($role === 'admin') {
//     // if (($role === 'admin') || ($role === 'sadmin')) {
//     include 'db.php';

//     // Handle search functionality
//     $search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
    
//     if (!empty($search_query)) {
//         // If a search query is present, execute the specific search query
//         $query = "SELECT * FROM users WHERE status='approved' AND role='user' AND emp_id LIKE '%$search_query%'";
//     } else {
//         // If no search query is present, fetch all users
//         $query = "SELECT * FROM users WHERE status='approved' AND role='user'";
//     }
?>
  
    <div class="container mt-3">
        <?php //include('../view/message.php'); ?>
        <div class="row">
           <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="text-uppercase fw-bold fw-bolder text-muted"><b>Add User</b>
                            <a href="manage_user.php" class="btn btn-danger float-end"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="manage_user-code.php" method="POST">

                            <div class="mb-3">
                                <label for="username">Username/Emp ID</label>
                                <input type="text" name="username" id="username" class="form-control" required> 
                            </div>
                            
                            <div class="mb-3">
                               <label for="password">Password</label>
                            <input type="text" name="password" id="password"  class="form-control" required>
                            </div>

                                                       

                            <div class="mb-3">
                                <button type="submit" name="save_adminuser" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
        </div>
    </div>

<?php 
//}
include('includes/footer.php');?>