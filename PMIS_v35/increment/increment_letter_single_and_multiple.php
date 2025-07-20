
<?php
// session_start();
include('../db/db.php');
include('../includes/header-bio-increment.php');

?>
<div class="container mt-2 p-1 my-1 border shadow-lg bg-white rounded" onload='document.form1.edu_info.focus()'>      
  <h2 class="page-header text-center text-muted text-uppercase text-shadow mt-2 p-1 my-1"><b>Basic Increment Letter (Single +) With Emp ID</b></h2>
      <div class="row">    
        <div class="col-sm-12">      
    <div class="col-sm-4"> </div>          
    <div class="col-sm-4"> 
      <div class="card border border-warning">
        <div class="card-header">        
        </div>
      <div class="card-body">        
     <form method="POST" target="_blank" action="increment_letter_single_and_multiple_pdf.php" enctype="multipart/form-data">      
        <!-- <div class="form-group">
          <input class="form-control" placeholder="Enter Emp ID" type="text" name="emp_id" id="emp_id" required="">
        </div> -->
        <div class="form-group">       
         <select class="form-control" id="emp_id" data-placeholder="Select EMP ID"  name="emp_id[]" multiple class="chosen-select" multiple="multiple" required>
          <!-- <option value="" disabled selected >Select EMP ID</option> -->
          <?php 
          $sql="SELECT emp_id FROM job_desc";           
          $result=mysqli_query($conn,$sql);
           $arr1=array();
           //while($row=mysql_fetch_object($result)){
            while($row=mysqli_fetch_array($result)){
            $emp_id=$row['emp_id'];
            echo "<option value='$emp_id'>$emp_id</option>";
          }
          ?>          
          </select>          
        </div>
          <button type="submit" id="submit" name="submit" class="btn btn-md btn-primary btn-block"><i class="fa fa-credit-card"></i> Increment letter</button>              
      </form>
      <!-- <button class="btn" name="btn" id="btn">Reset</button>  -->

      </div>
  </div>
</div>
<div class="col-sm-4"> <center><a href="increment_details.php" class="btn btn-primary float-end"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>  Previous Page </a></center></div>

</div>
</div>
</div>
</div>
 <script>
  jQuery('#emp_id').chosen();
  </script>
<?php include('../includes/footer.php');?>