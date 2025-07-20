<?php

if (isset($_POST['submit'])) {

    $emp_id = $_POST['emp_id'];
   //  $_SESSION['emp_id'] = $emp_id;
   // $emp_id=$_SESSION['emp_id'];
    



 include('../includes/header-dashboard.php');?>


<div class="container mt-1 ">
 
<div class="card shadow-lg">
  <div class="card-header bg-primary "><h3 class="page-header text-white text-uppercase text-center">Bio-Data Search by Emp ID for Specific Info. Details</h3></div>
  <div class="card-body bg-white">
  
 <div class="row">
    
    
        <!--1st col-->
    <div class="col-sm-1 "> </div>
    <!--2nd col-->
    <div class="col-sm-10">
    

 
  <h3 class="mt-2 text-center"></h3>
  <h4 class="text-center"></h4>
    <h4 class="float-center text-center">


<a href="bio_data_personal_info_print1.php?emp_id=<?php echo $emp_id; ?>" class="btn btn-danger text-white"><i class="fa fa-print" style="color:white"></i> Print Personal Information</a>




 

  <a href="#" id="printButtonSerivicInfo" class="btn btn-danger text-white"><i class="fa fa-print" style="color:white"></i> Print Service Information</a>

  <a href="#" id="printButtonSerivicHistory" class="btn btn-danger text-white"><i class="fa fa-print" style="color:white"></i> Print Service History</a>

    <hr class="">  
  <a href="#" id="printButtonTrainingInfo" class="btn btn-danger text-white"><i class="fa fa-print" style="color:white"></i> Print Training Information</a>

  <a href="#" class="btn btn-danger text-white"><i class="fa fa-print" style="color:white"></i> Print Bank Information</a>
  
  <a href="#" class="btn btn-danger text-white"><i class="fa fa-print" style="color:white"></i> Print Nominees Info.</a>
  <hr>
  
<a href="bio_data_specific_info_by_emp_id_1.php" class="btn btn-primary text-white"><i class="fa fa-arrow-left" style="color:white"></i> Previous Page</a>

    </h4>
    
  </div>
 
  <div class="col-sm-1 "> </div>
    
    </div>
 
  </div>
  <div class="card-footer"><h6 class="text-right float-end">Design & Developed by, BCIC.</h6></div>
</div>
 
</div>


<?php include('../includes/footer-dashboard.php');}?>


