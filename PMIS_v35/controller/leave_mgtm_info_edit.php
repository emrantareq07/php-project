<?php
session_start();
require '../db/dbcon.php';
$_SESSION['emp_id']=$_SESSION['emp_id'];
include('../includes/header.php');
?>


  
    <div class="container mt-5">

        <?php include('../view/message.php'); ?>

        <div class="row">
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>

            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow border-primary">
                    <div class="card-header">
                        <h4 class="text-uppercase "><b>Update Leave Management Information </b>
                            <a href="leave_mgtm_info_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id'])){
                            $id = mysqli_real_escape_string($con, $_GET['id']);
                            $query = "SELECT * FROM leave_mgtm WHERE id='$id' ";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0) {
                                //while($student=mysqli_fetch_array($query_run)){
                                $student = mysqli_fetch_array($query_run);
                                    $leave_type=$student['leave_type'];
                                ?>
                                <form action="leave_mgtm_info-code.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $student['id']; ?>">

                                    
                                    <div class="form-group"><label for="leave_type"> Leave Type:</label>
                                        <select class="form-control" id="leave_type" name="leave_type" >
                                            
                                        <option value="" disabled selected>--Select--</option>
                                                                                     
                                        <option value="Earn Leave" <?=$leave_type == 'Earn Leave' ? ' selected="selected"' : '';?> >Earn Leave</option>
                                        <option value="Maternity Leave" <?=$leave_type == 'Maternity Leave' ? ' selected="selected"' : '';?> >Maternity Leave</option>
                                        <option value="Medical Leave" <?=$leave_type == 'Medical Leave' ? ' selected="selected"' : '';?> >Medical Leave</option>
                                        <option value="Study Leave" <?=$leave_type == 'Study Leave' ? ' selected="selected"' : '';?> >Study Leave</option>
                                        <option value="Lien Leave" <?=$leave_type == 'Lien Leave' ? ' selected="selected"' : '';?> >Lien Leave</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Ref. No.</label>
                                        <input type="text" name="ref_no" value="<?=$student['ref_no'];?>" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" value="<?=$student['from_date'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" value="<?=$student['to_date'];?>" class="form-control">
                                    </div>
                                    <!-- <div class="mb-3">
                                        <label>Total Days</label>
                                        <input type="text" name="total_days" class="form-control" value="<?=$student['total_days'];?>" readonly>
                                    </div> -->
                                   
                                    <div class="mb-3">
                                        <button type="submit" name="update_student" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
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
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
        </div>
    </div>
<?php include('../includes/footer.php');?>