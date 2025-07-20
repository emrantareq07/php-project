<?php
// controller/other_qualification_details.php
include('../inc/header.php');
// include('includes/header.php');
session_start();

// Check if user is logged in and emp_id is set in session
if (!isset($_SESSION['emp_id'])) {
    // Redirect to login page or handle unauthorized access
    exit("Unauthorized access. Please login.");
}


?>
<script src="../js/jquery.dataTables.min.js"></script>
<script src="../js/dataTables.bootstrap.min.js"></script>      
<link rel="stylesheet" href="../css/dataTables.bootstrap.min.css" />

<script src="../js/ajax_other_qualification.js"></script>   
<!-- <script src="js/ajax_other_qualification.js"></script> -->
<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
<style>
/*.dataTables_filter, .dataTables_info { display: none; }*/
</style>
<?php //include('inc/container.php'); ?>
<div class="container shadow  rounded" style="min-height:500px; ">
    
  <div class="row">
    <!-- <div class="container"> -->
        <div class=" col-sm-12"> 
         <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">    -->
            
            <div class="card shadow" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19); ">
                <div class="card-header" style="background-color: #7734eb;">
                    <h2 class=" text text-center text-uppercase text-white" style="color: white"><b> Other Qualification Information</b></h2>
                </div>
                
                <div class="card-body">

                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-10">
                                <!-- <h3 class="panel-title"></h3> -->
                                <a class="btn btn-primary text text-bg-white" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>">Previous page</a>
                            </div>
                            <div class="col-md-2" align="right">
                                <button type="button" name="add" id="addRecord" class="btn btn-success">Add New</button>
                            </div>
                        </div>
                    </div>

                    <table id="recordListing" class="table table-bordered table-striped display" width="100%">
                        <thead>
                            <tr>
                                <th>#</th>					
                                <!-- <th>User id</th>					 -->
                                <th>Emp Id</th>
                                <th>Degree Name</th>					
                                <th>Subject</th>					
                                <th>University</th>
                                <th>Country</th>                    
                                <th>Course Duration</th>
                                <th></th>
                                <th></th>					
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>


            <div id="recordModal" class="modal fade">
                <div class="modal-dialog">
                    <form method="post" id="recordForm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title"><i class="fa fa-plus"></i> Edit Record</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="degree_name" class="control-label">Degree Name</label>
                                    <input type="text" class="form-control" id="degree_name" name="degree_name" placeholder="Enter Degree Name" required>			
                                </div>
                                  <div class="form-group">
                                    <label for="subject" class="control-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter Subject" required>         
                                </div>
                                <div class="form-group">
                                    <label for="university" class="control-label">University</label>
                                    <input type="text" class="form-control" id="university" name="university" placeholder="Enter University" required>         
                                </div>
                                 <div class="form-group">
                                    <label for="country" class="control-label">Country</label>
                                    <input type="text" class="form-control" id="country" name="country" placeholder="Enter Country" required>         
                                </div>
                                  <div class="form-group">
                                    <label for="course_duration" class="control-label">Course Duration</label>
                                    <input type="text" class="form-control" id="course_duration" name="course_duration" placeholder="Enter Course Duration" required>         
                                </div>
                               
                               
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" name="id" id="id" />
                                <input type="hidden" name="action" id="action" value="update" />
                                <input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <!-- </div> -->
</div>
</div>
<?php include('../inc/footer.php');?>