<?php

error_reporting(0);
include('../includes/header-print.php');
?>

<div class="container mt-5">
<div class="row">	
  
  <div class="col-sm-4">  	</div>

  <div class="col-sm-4 border border rounded shadow border-primary ">
  	<h3 class="text-uppercase text-center"><b>Search Promotion Due List</b></h3>
  	<h5 class="form-label text-center text-muted text-uppercase">(Service Period Completed 5 Years / +)</h5>

  	<form method="POST" action="promotion_due_search_emp_list.php" enctype="multipart/form-data">
    
    <!-- <div class="input-group date" id="datepicker" data-target-input="nearest">
      <input type="text" class="form-control datetimepicker-input" data-target="#datepicker"/>
      <div class="input-group-append" data-target="#datepicker" data-toggle="datetimepicker">
        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
      </div>
    </div> -->
  <!--   <div class="form-group mb-3"><label for="start_date" class="form-label"> Start Date  </label>
	<input type="date" class="form-control"  id="start_date" name="start_date" data-target="" required  />
	</div> -->

	<div class="form-group mb-3"><label for="target_date" class="form-label"> Targeted Date </label>
	<input type="date" class="form-control"  id="target_date" name="target_date" data-target="" required/>
	</div>

	  <div class="form-group">
        
        <select class="form-control" id="designation" name="designation" tabindex="" required>
            <option value="" disabled selected>--Select Designation--</option>
           <?php
           include('../db/db.php');
            $sql = "SELECT designation FROM designation order by designation ASC";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result))
            {
             echo "<option value='".$row['designation']."'>".$row['designation']."</option>";
            }?>
           </select>
               
        </div>
<hr>
        <div class="form-group">
        
        <select class="form-control" id="sub_cadre_header" name="sub_cadre_header" tabindex="" >
            <option value="" disabled selected>--Select Designation Ext.--</option>
           <?php
           include('../db/db.php');
            $sql = "SELECT header FROM sub_cadre_header order by header ASC";
            $result = mysqli_query($conn, $sql);
            while($row = mysqli_fetch_array($result))
            {
             echo "<option value='".$row['header']."'>".$row['header']."</option>";
            }?>
           </select>
               
        </div>
	
	<!-- <div class="input-group mb-3" >
  	
  	<select class="form-control" id="dateColumnSelector" name="dateColumnSelector" required>
  		<option  disabled selected>Select Catagory</option> 
      <option value="dob">DOB</option> 
        <option value="doj">DOJ</option> 
        <option value="next_promo_date">Next Promotion Due</option>
        <option value="prl">PRL</option>
    </select>
	</div> -->
	
     <button type="submit" id="submit" name="submit" class="btn btn-md btn-primary btn-block form-control mt-2"><i class="fa fa-search" style="color:white"></i> </span> Search</button>  
     <hr>
       <a href="../dashboard.php" class="btn btn-md btn-warning btn-block text-white form-control mt-2">
              <i class="fa fa-arrow-circle-left" style="color:white"></i> Previous Page
          </a>
  
</form>
<br>
</div>
  <div class="col-sm-4"></div>

</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tempusdominus-bootstrap-4@5.1.2/dist/js/tempusdominus-bootstrap-4.min.js"></script>

<script>
  // Initialize the datepicker
  // $('#datepicker').datetimepicker({
  //   format: 'L' // You can customize the date format
  // });
</script>

<?php include('../includes/footer.php');?>