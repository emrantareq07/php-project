<?php
// Start the session
session_start();
// require 'dbcon.php';
require '../db/db.php';
    // session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
// Check if message is set
if(isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Unset the session variable
}
include('../includes/header.php');

?>

  
    <div class="container-fluid mt-4">

        <?php include('../view/message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow border-primary">
                    <div class="card-header">
                        <h4 class="text-center text-uppercase text-muted"><b>Leave Management Information </b><span>
                            <a href="leave_mgtm_info-create.php" class="btn btn-primary float-end"><span class="glyphicon glyphicon-plus"></span> Add </a>
                            <a href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-primary float-start"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous Page</a>
                        </span>
                            
                        </h4>
                    </div>
                    <div class="card-body">

                         <?php if(isset($message)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <!-- <th>ID</th> -->
                                 <!--  -->
                                 <th>Emp Id</th>
                                 <th>Leave Type</th>
                                 <th>Ref. No.</th>
                                 <th>From Date</th>
                                 <th>To Date</th>
                                 <th>Total Days</th>
                                 
                                 <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                 if(isset($_SESSION['emp_id'])){
                                      //$edit_id=$_GET['edit'];
                                      $emp_id=$_SESSION['emp_id'];
                                      $query="SELECT * FROM leave_mgtm where emp_id='$emp_id'";
                                    // }
                                    //$query = "SELECT * FROM training_info";
                                    $query_run = mysqli_query($conn, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        foreach($query_run as $student)
                                        {
                                            ?>
                                            <tr>
                                                <!-- <td><?= $student['id']; ?></td> -->
                                                <!--  -->
                                                <td><?= $student['emp_id']; ?></td>
                                                <td><?= $student['leave_type']; ?></td>
                                                <td><?= $student['ref_no']; ?></td>
                                                <td><?= $student['from_date']; ?></td>
                                                <td><?= $student['to_date']; ?></td>
                                                <td><?= $student['total_days']; ?></td>
                                                
                                                <td class="text-center">
                                                    <a href="membershi_info-view.php?emp_id=<?= $student['emp_id']; ?>" class="btn btn-info btn-sm disabled"><span class="glyphicon glyphicon-eye-open"></span> View</a>
                                                    <a href="leave_mgtm_info_edit.php?id=<?= $student['id']; ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                                                    <form action="leave_mgtm_info-code.php" method="POST" class="d-inline">
                                                        <button type="submit" name="delete_student" value="<?=$student['id'];?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<h5 class='text-danger'><b> No Record Found!!! </b></h5>";
                                    }
                                }
                                ?>
                                
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>


<?php include('../includes/footer.php');?>
<!-- <?php //include('../includes/footer.php');?> -->