<?php
session_name('bcic_e-library');
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin']) == 0) {
    header('location:../index.php');
    exit;
}
// Reset session login value if set
if (isset($_SESSION['login']) && $_SESSION['login'] != '') {
    $_SESSION['login'] = '';
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom Style -->
    </head>
<body>

<div class="container mt-4">
    <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
    </div>
	<?php endif; ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow border border-success border-2">
                <div class="card-header">
                    <h4 class="text-center text-uppercase ">
                        <?php
                      
                        ?>
                        <b>User Details </b>
                        <span class="float-end">
                           
                            <div style="margin-top: 10px;">
                              <a href="sadmin_dashboard.php" class="btn btn-primary "> <i class="fa fa-arrow-left" style="font-size:16px"></i> BACK</a>
                                <a href="manage_user-create.php" class="btn btn-primary"> <i class="fa fa-plus" style="font-size:16px"></i> Add</a>
                                <a href="logout.php" class="btn btn-danger"><i class="fa fa-sign-out" style="font-size:16px"></i> Logout</a>
                            </div>
                        </span>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                              <tr>
                            <th>ID</th>
                            
                            <th>User Name</th>
                            <!-- <th>Designation</th> -->
                            <th>Password</th>
                            <!-- <th>Status</th> -->
                            <th>Role</th>
                            <th class='text-center'>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM admin";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $student) {
                                    ?>
                                    <!-- Display user data -->
                                  <tr>
                                    <td><?= $student['id']; ?></td>                                    
                                    <td><?= $student['username']; ?></td>
                                    <!-- <td><?= $student['designation']; ?></td> -->
                                    <td><?= $student['password']; ?></td>
                                    <!-- <td><?= $student['status']; ?></td> -->
                                    <td><?= $student['role']; ?></td>
                                    <td class='text-center'>
                                        <a href="manage_user-edit.php?id=<?= $student['id']; ?>" class="btn btn-success btn-sm"><i class="fa fa-edit" style="font-size:16px"></i> Edit</a>                                        
                                       <!--  <form action="manage_user-code.php" method="POST" class="d-inline">
                                            <button type="submit" name="delete_student" value="<?= $student['id']; ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash" style="font-size:16px"></i> Delete</button>
                                        </form> -->
                                        <form action="manage_user-code.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
									    <button type="submit" name="delete_user" value="<?= $student['id']; ?>" class="btn btn-danger btn-sm">
									        <i class="fa fa-trash" style="font-size:16px"></i> Delete
									    </button>
									</form>

                                    </td>
                                </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'><h5 class='text-danger'> No Record Found!!! </h5></td></tr>";
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</body>
</html>