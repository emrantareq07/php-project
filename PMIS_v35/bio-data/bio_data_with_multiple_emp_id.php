
<?php
session_start();
include('../db/db.php');
include('../includes/header-bio-increment.php');


?>


<div class="container mt-2 p-1 my-1 border border-primary shadow-lg bg-white rounded" onload='document.form1.edu_info.focus()'>
      <h2 class="page-header text-center text-muted text-uppercase text-shadow mt-2 p-1 my-1"><b>Bio-Data Search by Emp ID</b></h2>
      <div class="row">
    
        <div class="col-sm-12">
        <?php if(@$_GET['submitted'])
      {?>
      <div class="alert alert-success" role="alert">
       <b> Basic Decrement Successfully!!!</b>
      </div>
      <?php }?>
          
    <div class="panel-body">
      
  
    <div class="col-sm-4"> </div>   
          
    <div class="col-sm-4"> 
      <div class="card border border-warning">
        <div class="card-header">
        
        </div>
      <div class="card-body">

        
      <form method="POST" action="bio_data_with_multiple_emp_id_pdf_1.php" enctype="multipart/form-data">


        <div class="form-group">
      
         <select class="form-control" id="emp_id" data-placeholder="Choose EMP ID"  name="emp_id[]" multiple class="chosen-select" multiple="multiple" required>
       
          <?php 
          $sql="SELECT emp_id FROM basic_info";
           
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

          <button type="submit" id="submit" name="submit" class="btn btn-md btn-primary btn-block"><i class="fa fa-search" style="color:red"></i> </span> Search</button>  
               
              
      </form>
      <!-- <button class="btn" name="btn" id="btn">Reset</button>  -->

</div>
</div>
</div>
<div class="col-sm-4"> <center><a href="bio-data_and_gradation_list.php" class="btn btn-primary float-end"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>  Previous Page </a></center></div>

</div>
</div>
</div>
</div>

 <script>

  jQuery('#emp_id').chosen();

  </script>

<?php include('../includes/footer.php');?>