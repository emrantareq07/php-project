<?php
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
include('../includes/header.php');
$val= $_GET['val'];
?>  
    <div class="container mt-3">
        <?php include('../view/message.php'); ?>
        <div class="row">
           <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="text-uppercase fw-bold fw-bolder text-muted"><b>Add Membership Information</b>
                            <a href="membership_info_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="membership_info-code.php?emp_id=<?php echo $_SESSION['emp_id']; ?>&val=<?php echo $val; ?>" method="POST">

                            <div class="mb-3">
                                <label>Membership No.</label>
                                <input type="text" name="mem_no" class="form-control">
                            </div>
                            <div class="mb-3">
                             <div class="form-group"><label for="type"> Type:</label>
                            <select class="form-control" id="type" name="type" >
                                
                            <option value="" disabled selected>--Select--</option>
                                 
                            <option value="Associate Member">Associate Member</option>
                            <option value="Member">Member</option>
                            <option value="Fellow">Fellow</option>
                            </select>
                            </div>
                            </div>

                           <!--  <div class="mb-3">
                                <label>Title</label>
                                <input type="title" name="title" class="form-control">
                            </div> -->
                            <div class="mb-3">
                                <label>Institute</label>
                                <input type="text" name="institute" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Country</label>
                                <input type="text" name="country" class="form-control">
                            </div>
                             <div class="mb-3">
                                <label>Validity</label>
                                <input type="title" name="validity" class="form-control">
                            </div>
                          <!--   <div class="mb-3">
                                <label>Month-Year</label>
                                <input type="text" name="month_year" class="form-control">
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