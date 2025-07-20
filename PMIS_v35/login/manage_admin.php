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
if ($role === 'sadmin') {
  //echo "<h1 class='text-center my-2 text-uppercase text-secondary'>Welcome, Admin!</h1>";
  // Display admin-specific content
  //header("Location: admin_approval.php");
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

    <title>BCIC PMIS</title>
</head>
<body>
  
    <div class="container mt-4">

        <?php include('message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow border border-primary">
                    <div class="card-header">
                        <h4 class="text-center text-uppercase "><b>Admin Management</b>
                            <span class=" float-end">
							<a href="membership_info-create.php" class="btn btn-primary"> Add </a>
							<a href="logout.php" class="btn btn-danger"> Logout </a></span>
                            <a href="super-admin_dashboard.php?role=<?php echo $_SESSION['role'] ?>" class="btn btn-primary float-start">Previous Page</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <table class="table table-bordered table-striped">
                            <thead>
                                 <tr>
                                <th>ID</th>
                                <th>Emp ID</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Password</th>
                                <th>Status</th>
                                <th>Role</th>
                                <th class='text-center'>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php 

                                 if(isset($_SESSION['emp_id'])){
                                      //$edit_id=$_GET['edit'];
                                      $emp_id=$_SESSION['emp_id'];
                                      //$query="SELECT * FROM users_tbl where email='$email'";
                                      $query="SELECT * FROM users where status='approved' and role='admin'";
                                     }
                                    //$query = "SELECT * FROM training_info";
                                    $query_run = mysqli_query($conn, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        foreach($query_run as $student)
                                        {
                                            ?>
                                            <tr>
                                            <td><?= $student['id']; ?></td>
                                            <td><?= $student['emp_id']; ?></td>
                                            <td><?= $student['name']; ?></td>
                                            <td><?= $student['designation']; ?></td>
                                            <td><?= $student['password']; ?></td>
                                            <td><?= $student['status']; ?></td>
                                            <td><?= $student['role']; ?></td>
                                                                                                
                                                <td class='text-center'>
                                                    <!-- <a href="membershi_info-view.php?emp_id=<?= $student['emp_id']; ?>" class="btn btn-info btn-sm disabled">View</a> -->
                                                    <a href="manage_admin-edit.php?id=<?= $student['id']; ?>" class="btn btn-success btn-sm">Edit</a>
                                                    <form action="membershi_info-code.php" method="POST" class="d-inline">
                                                        <button type="submit" name="delete_student" value="<?=$student['id'];?>" class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<h5> No Record Found </h5>";
                                    }
                                ?>
                                
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
<?php }//include('includes/footer.php');?>
<!-- <?php //include('../includes/footer.php');?> -->