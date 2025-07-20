<?php
session_start();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['emp_id'])) {
  header("Location: login.php");
  exit();
}

// Check the user role
$role = $_SESSION['role'];

// Display different content based on the user role
if($role === 'sadmin') {
  include 'db.php';
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title> Edit</title>
</head>
<body>
  
    <div class="container mt-4">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card shadow border border-success border-2">
                    <div class="card-header">
                        <h4 class="text-uppercase"> Edit 
                            <a href="manage_admin.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id']))
                        {
                            $id = mysqli_real_escape_string($conn, $_GET['id']);
                            $query = "SELECT * FROM users WHERE id='$id' ";
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                //while($student=mysqli_fetch_array($query_run)){
                                $student = mysqli_fetch_array($query_run);
                                    $role=$student['role'];
                                ?>
                                <form action="manage_admin-code.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $student['id']; ?>">

                                 <!--    <div class="mb-3">
                                        <label>Student Name</label>
                                        <input type="text" name="user_id" value="<?=$student['user_id'];?>" class="form-control">
                                    </div> -->
                                    <!-- <div class="mb-3">
                                        <label>Emp ID</label>
                                        <input type="emp_id" name="emp_id" value="<?=$student['emp_id'];?>" class="form-control">
                                    </div> -->
                                    <div class="mb-3">
                                        <label>Emp ID</label>
                                        <input type="text" name="emp_id" value="<?=$student['emp_id'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Name</label>
                                        <input type="emp_id" name="name" value="<?=$student['name'];?>" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>Designation</label>
                                        <input type="text" name="designation" value="<?=$student['designation'];?>" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>Password</label>
                                        <input type="text" name="password" value="<?=$student['password'];?>" class="form-control">
                                    </div>

                                   
                                    <div class="form-group mb-3">
                                        <label>Status</label>
                                        <input type="text" name="status" value="<?=$student['status'];?>" class="form-control">
                                    </div>
									
									 <div class="form-group mb-3">
                                        <label>Role</label>
                                        <input type="text" name="role" value="<?=$student['role'];?>" class="form-control" readonly>
                                    </div>
									
									
                                    <!--<div class="form-group"><label for="type"> Role:</label>
                                    <select class="form-control" id="role" name="role">
                                        
                                    <option value="" disabled selected>--Select--</option>
                                         
                                    <option value="admin" <?=$role == 'admin' ? ' selected="selected"' : '';?> >Admin</option>
                                    <option value="user" <?=$role == 'user' ? ' selected="selected"' : '';?> >User</option>
                                    
                                    </select>
                                    </div>-->

                                    <div class="form-group mb-3">
                                        <button type="submit" name="update_student" class="btn btn-primary">
                                            Update
                                        </button>
                                    </div>

                                </form>
                                <?php
                            }
                        //}
                            else
                            {
                                echo "<h4>No Such Id Found</h4>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div> 
            <div class="col-md-3"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php }?>