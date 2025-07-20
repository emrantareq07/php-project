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
                        <h4 class="text-uppercase "><b>Update Prof./ Membership Information </b>
                            <a href="membership_info_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id']))
                        {
                            $id = mysqli_real_escape_string($con, $_GET['id']);
                            $query = "SELECT * FROM prof_membership WHERE id='$id' ";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                //while($student=mysqli_fetch_array($query_run)){
                                $student = mysqli_fetch_array($query_run);
                                    $type=$student['type'];
                                ?>
                                <form action="membership_info-code.php" method="POST">
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
                                        <label>Membership No.</label>
                                        <input type="text" name="mem_no" value="<?=$student['mem_no'];?>" class="form-control">
                                    </div>

                                    <div class="form-group"><label for="type"> Type:</label>
                                    <select class="form-control" id="type" name="type" >
                                        
                                    <option value="" disabled selected>--Select--</option>
                                         
                                    <option value="Associate Member" <?=$type == 'Associate Member' ? ' selected="selected"' : '';?> >Associate Member</option>
                                    <option value="Member" <?=$type == 'Member' ? ' selected="selected"' : '';?> >Member</option>
                                    <option value="Fellow" <?=$type == 'Fellow' ? ' selected="selected"' : '';?> >Fellow</option>
                                    </select>
                                    </div>
                                   <!--  <div class="mb-3">
                                        <label>Title</label>
                                        <input type="text" name="title" value="<?=$student['title'];?>" class="form-control">
                                    </div> -->

                                    <div class="mb-3">
                                        <label>Institute</label>
                                        <input type="text" name="institute" value="<?=$student['institute'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Country</label>
                                        <input type="text" name="country" value="<?=$student['country'];?>" class="form-control">
                                    </div>
                                    <div class="mb-3">
                                        <label>Validity</label>
                                        <input type="text" name="validity" value="<?=$student['validity'];?>" class="form-control">
                                    </div>
                                   <!--   <div class="mb-3">
                                        <label>Month/ Year</label>
                                        <input type="text" name="month_year" value="<?=$student['month_year'];?>" class="form-control">
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