<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCIC PMIS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
  
  
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
<!--
 
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet"> 
  <link href="//db.onlinewebfonts.com/c/aec382221b330b8581963c1bcc7c61d9?family=Nikosh" rel="stylesheet" type="text/css"/> 
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">-->

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali&family=Open+Sans&family=Tiro+Bangla&display=swap" rel="stylesheet">
  <style>
     body
   {
    margin:0;
    padding:0;
    background-color:#f1f1f1;
   }
 
* {
	font-family: 'Open Sans', sans-serif;

    font-family: 'Tiro Bangla', serif;
	<!--font-family: 'Noto Sans Bengali', sans-serif;

    font-family: 'Nikosh', sans-serif;

    font-family: 'Nikosh', serif;-->
}
.bs-example{
            margin: 10px;
        }

  </style>
  <link rel="icon" type="image/gif/png" href="../images/bcic_logo.png">
</head>
<body>

<div class="container mt-2">
  <p></p>
  <?php
if(@$_GET['submitted'])
{?>
<div class="alert alert-success" role="alert">
  Data Inserted Successfully
</div>
<?php }?>
<div class="card">
  <div class="card-header bg-primary "><h3 class="page-header text-white text-uppercase text-center">PMIS Admin (--Dashboard--)</h3></div>
  <div class="card-body bg-white">
  
 <div class="row">
	
	
		<!--1st col-->
	<div class="col-sm-1 ">	</div>
	<!--2nd col-->
	<div class="col-sm-10">
    
 <img class="mx-auto d-block" src="../images/bcic.jpg" width="150" height="150"/>
  <h3 class="mt-2 text-center text-uppercase"> Increment Letter Print All</h3>
  <h6 class="mt-2 text-center"> Excluding:
<p>(1). Which have Penalty
(2). Job Confrmation not Completed
(3). Basic Limit Execeds</p>
  </h6>
  <hr class="border border-info">
  <h4 class="text-center">
   

  <form method="POST" action="increment_letter_print_pdf.php" target="_blank">  

  <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
  <button type="submit" name="create_pdf" class="btn btn-danger text-center"><i class="fa fa-print"></i> Print Increment Letter </button>
     
  </form></h4>
  <center>
     <a class="btn btn-primary" href="../dashboard.php"> <i class="fa fa-arrow-left"></i> Previous Page </a>
  </center>
 

<hr class="">
 
  
  </div>
  <!--3rd col-->
  <div class="col-sm-1 "> </div>
	
	</div>
 
  </div>
  <div class="card-footer"><h6 class="text-right float-end">Design & Developed by, BCIC.</h6></div>
</div>
 
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
