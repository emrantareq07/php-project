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
$val=0;     
if(isset($_GET['val'])){
 $val= $_GET['val'];
 
  }
?>  
    <div class="container-fluid mt-4">

        <?php include('../view/message.php'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow border-primary">
                    <div class="card-header">
                        <h4 class="text-center text-uppercase text-muted"><b>Professional/ Membership Information Details</b><span>
                            <a href="membership_info-create.php?emp_id=<?php echo $_SESSION['emp_id']; ?>&val=<?php echo $val; ?>" class="btn btn-primary float-end"><span class="glyphicon glyphicon-plus"></span> Add </a>
                            <?php

                          if($val==1){
                            ?>
                            <a class="btn btn-primary float-start" href="../login/user_dashboard.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous page </a>
                            <?php
                          }
                          else{
                            ?>
                            <a class="btn btn-primary float-start" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>Previous page </a>

                            <?php
                          }

                          ?>
                            
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
                                <th>ID</th>
                                 <!-- <th>User Id</th> -->
                                 <th>Emp Id</th>
                                 <th>Membership No.</th>
                                 <th>Membership Type</th>
                                 <th>Institute</th>
                                 <th>Country</th>
                                 <th>Validity</th>
                                 <th>Month/ Year</th>
                                 <?php

                    if($val==1){
                      ?>
                      <?php
                    }
                    else{
                      ?>
                      
                      <th class="text-center">Action</th>

                      <?php }
                    ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                 if(isset($_SESSION['emp_id'])){
                                      //$edit_id=$_GET['edit'];
                                      $emp_id=$_SESSION['emp_id'];
                                      $query="SELECT * FROM prof_membership where emp_id='$emp_id'";
                                    // }
                                    //$query = "SELECT * FROM training_info";
                                    $query_run = mysqli_query($conn, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        foreach($query_run as $student)
                                        {
                                            ?>
                                            <tr>
                                                <td><?= $student['id']; ?></td>
                                                <!-- <td><?= $student['user_id']; ?></td> -->
                                                <td><?= $student['emp_id']; ?></td>
                                                <td><?= $student['mem_no']; ?></td>
                                                <td><?= $student['type']; ?></td>
                                                <td><?= $student['institute']; ?></td>
                                                <td><?= $student['country']; ?></td>
                                                <td><?= $student['validity']; ?></td>
                                                <td><?= $student['month_year']; ?></td>
                                                <?php

                  if($val==1){
                    ?>
                    <?php
                  }
                  else{
                    ?>
                    <td class="text-center">
                      <!-- <a href="training_info-view.php?emp_id=<?= $student['emp_id']; ?>" class="btn btn-info btn-sm disabled"><span class="glyphicon glyphicon-eye-open"></span> View</a> -->

                    <a href="membership_info-edit.php?id=<?= $student['id'];?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                    <!-- <?php echo $student['id']; ?>&val=<?php echo $val; ?> -->
                    <form action="membership_info-code.php" method="POST" class="d-inline">
                        <button type="submit" name="delete_student" value="<?=$student['id'];?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                    </form>
                    <!-- <a href="training_info-edit.php?id=<?= $student['id']; ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                    <form action="training_info-code.php" method="POST" class="d-inline">
                        <button type="submit" name="delete_student" value="<?=$student['id'];?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                    </form> -->
                  </td>
                    <?php
                  }
                  ?>
                                               <!--  <td class="text-center">
                                                    <a href="membershi_info-view.php?emp_id=<?= $student['emp_id']; ?>" class="btn btn-info btn-sm disabled"><span class="glyphicon glyphicon-eye-open"></span> View</a>
                                                    <a href="membership_info-edit.php?id=<?= $student['id']; ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                                                    <form action="membership_info-code.php" method="POST" class="d-inline">
                                                        <button type="submit" name="delete_student" value="<?=$student['id'];?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                                                    </form>
                                                </td> -->
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo "<h5 class='text-danger text-center'><b> No Record Found!!! </b></h5>";
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