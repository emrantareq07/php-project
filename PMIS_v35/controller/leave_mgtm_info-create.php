<?php
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
include('../includes/header.php');
?>

  
    <div class="container mt-3">

        <?php include('../view/message.php'); ?>

        <div class="row">
           <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>

            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="text-uppercase fw-bold fw-bolder text-muted"><b>Leave Management Information</b>
                            <a href="leave_mgtm_info_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="leave_mgtm_info-code.php" method="POST">

                            
                            <div class="mb-3">
                             <div class="form-group"><label for="leave_type"> Leave Type:</label>
                            <select class="form-control" id="leave_type" name="leave_type" required>
                                
                            <option value="" disabled selected>--Select--</option>
                                 
                            <option value="Earn Leave">Earn Leave</option>
                            <option value="Maternity Leave">Maternity Leave</option>
                            <option value="Medical Leave">Medical Leave</option>
                            <option value="Study Leave">Study Leave</option>
                            <option value="Lien Leave">Lien Leave</option>
                            </select>
                            </div>
                            </div>
                           
                            <div class="mb-3">
                                <label>Ref. No.</label>
                                <input type="text" name="ref_no" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>From Date</label>
                                <input type="date" name="from_date" class="form-control">
                            </div>
                             <div class="mb-3">
                                <label>To Date</label>
                                <input type="date" name="to_date" class="form-control">
                            </div>
                            <!-- <div class="mb-3">
                                <label>Total Days</label>
                                <input type="text" name="total_days" class="form-control" >
                            </div> -->

                            <div class="mb-3">
                                <button type="submit" name="save_student" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
        </div>
    </div>

<?php include('../includes/footer.php');?>