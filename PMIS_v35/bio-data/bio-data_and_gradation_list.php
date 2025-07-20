<?php include('../includes/header-dashboard.php');?>

<div class="container mt-1">
  
  <?php
if(@$_GET['submitted'])
{?>
<div class="alert alert-success" role="alert">
  Data Inserted Successfully
</div>
<?php }?>
<div class="card">
  <div class="card-header bg-primary "><h3 class="page-header text-white text-uppercase text-center">PMIS Admin (--Bio-Data--)</h3></div>
  <div class="card-body bg-white ">
  
 <div class="row">
	
	
		<!--1st col-->
	<!-- <div class="col-sm-1 ">	</div> -->
	<!--2nd col-->
	<div class="col-sm-12">
    
 <img class="mx-auto d-block" src="../images/bcic.jpg" width="130" height="130"/>
  <h3 class="mt-2 text-center"></h3>
  <h4 class="text-center"></h4>
    <h4 class="float-center text-center">
	
  <hr>
  <a href="../gradation-list/update_seniority_list_form.php" class="btn btn-secondary text-white"><i class="fa fa-edit" style="color:white"></i> Update Seniority List</a>
	<a href="../gradation-list/seniority_list_div_wise.php" class="btn btn-secondary text-white"><i class="fa fa-list" style="color:white"></i>  Seniority List</a>
  <a href="cadre_sub-cadre_grade_office_wise.php" class="btn btn-secondary text-white"><i class="fa fa-list" style="color:white"></i>  Gradation List (Cadre,Subcadre,Grade,Office)</a>
  <a href="#" class="btn btn-danger text-white"><i class="fa fa-print" style="color:white"></i> Print Seniority List</a>
  <hr>
  <a href="../gradation-list/search_all.php" class="btn btn-dark text-white"><i class="fa fa-search" style="color:red"></i> Gradation List (with Date)</a>
	
	<hr>
  <a href="bio_data_with_multiple_emp_id.php" class="btn btn-warning text-dark"><i class="fa fa-book" style="color:black"></i> Bio-Data (Multiple)</a>
  <a href="bio_data_specific_info_by_emp_id_1.php" class="btn btn-warning text-dark"><i class="fa fa-book" style="color:black"></i> Bio-Data (Specific Info.)</a>
  <a href="bio_data_specific_info_by_emp_id.php"  class="btn btn-warning text-dark"><i class="fa fa-book" style="color:black"></i> Bio-Data (Spec. Info. Multi.)</a>
   <a href="cadre_sub-cadre_grade_office_wise.php" class="btn btn-warning text-dark"><i class="fa fa-book" style="color:black"></i> Bio-Data (Cadre,Subcadre,Grade,Office)</a>
   <hr>
  <a href="short_bio_data_with_multiple_emp_id.php" class="btn btn-warning text-dark"><i class="fa fa-book" style="color:black"></i> Short Bio-Data (Multiple)</a>
 

<hr class="">
<a href="../dashboard.php" class="btn btn-primary text-white" ><i class="fa fa-dashboard" style="color:white"></i> Dashboard</a>
  <!-- <form method="POST" action="increment_letter_print_pdf.php" target="_blank">   -->

  <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
  <!-- <button type="submit" name="create_pdf" class="btn btn-danger text-center"><i class="fa fa-print"></i> Print Increment Letter </button>
     
  </form>
 -->


	</h4>
  
  
  </div>
  <!--3rd col-->
  <!-- <div class="col-sm-1 "> </div> -->
	
	</div>
 
  </div>
  <div class="card-footer"><h6 class="text-right float-end">Design & Developed by, BCIC.</h6></div>
</div>
 
</div>

<?php include('../includes/footer-dashboard.php');?>