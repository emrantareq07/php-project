

<?php
session_start();
include('../db/db.php');
//include('../includes/header.php');

?>
<!DOCTYPE html>
<!-- <html lang="en"> -->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="bn">
  <head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BCIC PMIS</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" >
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 
  
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"  />

<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>

<link rel="icon" type="image/gif/png" href="image/bcic_logo.png">
</head>

<body>

<div class="container mt-2 p-1 my-1 border border-primary shadow-lg bg-white rounded" onload='document.form1.edu_info.focus()'>
      <h2 class="page-header text-center text-muted text-uppercase text-shadow mt-2 p-1 my-1"><b>Bio-Data Search by Emp ID for Specific Info.</b></h2>
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
        
      <form method="POST" action="bio_data_personal_info_print1.php" enctype="multipart/form-data">

        <!-- <div class="form-group">
          <input class="form-control" placeholder="Enter Emp ID" type="text" name="emp_id" id="emp_id" required="">
        </div> -->

        <div class="form-group">
        <!-- <label for="emp_id">Designation:</label> -->
        
         <select class="form-control" id="emp_id" data-placeholder="Choose EMP ID"  name="emp_id[]" multiple class="chosen-select" multiple="multiple" required>
          <!-- <option value="" disabled selected >Select EMP ID</option> -->
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

          <button type="submit" id="submit" name="submit" class="btn btn-md btn-primary btn-block"><i class="fa fa-search" style="color:red"></i> </span> Print Personal Information</button>  

        

            <button type="submit" id="submit1" name="submit1" class="btn btn-md btn-primary btn-block"><i class="fa fa-search" style="color:red"></i> </span> Print Service Information</button>

            <button type="submit" id="submit2" name="submit2" class="btn btn-md btn-primary btn-block"><i class="fa fa-search" style="color:red"></i> </span>Print Service History</button>

            <button type="submit" id="submit3" name="submit3" class="btn btn-md btn-primary btn-block"><i class="fa fa-search" style="color:red"></i> </span> Print Training Information</button> 
               
              
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