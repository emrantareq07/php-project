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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
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
 /*     display: block;
  position: relative;
  height:100%;
  width:100%;*/
   }

/*     body {  
  display: block;
  position: relative;
  height:100%;
  width:100%;
}*/

/*body::after {
  content: "";
 /*background:url(https://www.google.co.in/images/srpr/logo11w.png) no-repeat;*/
/* background:url(images/bcic_logo.png) no-repeat;
  opacity: 0.2;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  position: absolute;
  z-index: 1;  
  height:50%;
  width:50%;
}*/*/
 .watermark {
  position: relative;
}

.watermark:after {
  content: "";
  display: block;
  width: 100%;
  height: 100%;
  position: absolute;
  top: 100px;
  left: 0px;
  background-image: url("../images/bcic_logo.png");
  /*background:url("images/bcic_logo.png") no-repeat;*/
  background-size: 100px 100px;
  background-position: 30px 30px;
  background-repeat: no-repeat;
  opacity: 0.2;
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

<div class="container mt-3">
  <p></p>

<div class="card">
  <div class="card-header bg-muted "><h3 class="page-header text-dark text-uppercase text-center">Seniority List </h3></div>
  <div class="card-body">
  
 <div class="row">
	
	
		<!--1st col-->
	<div class="col-sm-0 ">	</div>
	<!--2nd col-->
	<div class="col-sm-12">
  <div class="watermarked">
    <img class="mx-auto d-block my-0 pt-0 watermark" src="../image/bcic.jpg" width="70" height="70"/>
  </div>
<!--navs and pills-->
 <ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-bs-toggle="tab" href="#home">Home</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#menu1">Sr. GM</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#menu2">GM</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#menu3">DGM</a>
    </li>
        <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#menu4">Manager</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#menu5">DM</a>
    </li>
        <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#menu6">AE</a>
    </li>
        <li class="nav-item">
      <a class="nav-link" data-bs-toggle="tab" href="#menu7">SAE</a>
    </li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div id="home" class="container tab-pane active te"><br>
      <h3> Employee with All Cadre/Division</h3>
         
            <div class="input-group gap-2 gx-2 align-items-center text-center" style="display: flex; justify-content: space-around; ">
              
            <form method="POST" action="create_pdf_senior_gmmded.php" target="_blank">  
               <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
            <button type="submit" name="create_pdf" class="btn btn-success"><i class="fa fa-file-pdf-o" style="font-size:20px;color:red"></i> Senior GM (All + MD/ED) </button>
               
            </form>
        <form method="POST" action="create_pdf_gm.php" target="_blank">  
           <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
        <button type="submit" name="create_pdf" class="btn btn-success"><i class="fa fa-file-pdf-o" style="font-size:20px;color:white"></i> GM (All)</button>
           
        </form>

         <form method="POST" action="create_pdf_dgm.php" target="_blank">  
           <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
        <button type="submit" name="create_pdf" class="btn btn-success"><i class="fa fa-file-pdf-o" style="font-size:20px;color:purple"></i> DGM/Addl. (All)</button>
           
        </form>

        <form method="POST" action="create_pdf_manager.php" target="_blank">  
           <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
        <button type="submit" name="create_pdf" class="btn btn-success"><i class="fa fa-file-pdf-o" style="font-size:20px;color:purple"></i> Manager/DCE/DCC (All)</button>
           
        </form>

        <form method="POST" action="create_pdf_dm.php" target="_blank">  
           <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
        <button type="submit" name="create_pdf" class="btn btn-success"><i class="fa fa-file-pdf-o" style="font-size:20px;color:purple"></i> DM/Executive Engr./Chemist (All)</button>
           
        </form>
        <form method="POST" action="create_pdf_ae.php" target="_blank">  
           <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
        <button type="submit" name="create_pdf" class="btn btn-success"><i class="fa fa-file-pdf-o" style="font-size:20px;color:purple"></i> AE/AM (All)</button>
           
        </form>
        <form method="POST" action="create_pdf_sae.php" target="_blank">  
           <!--<input type="submit" name="create_pdf" class="btn btn-danger" value="Print" /> -->
        <button type="submit" name="create_pdf" class="btn btn-success"><i class="fa fa-file-pdf-o" style="font-size:20px;color:purple"></i> SAE/Junior Officer (All)</button>
           
        </form>
        </div>
    
      
      
    </div>

     <div id="menu1" class="container tab-pane fade"><br>
      <h3>Senior General Manager (Cadre/Division wise)</h3>
      <h4 class=" float-center text-center">
        <a href="view_users.php" class="btn btn-success">Senior GM Office</a>
        <!-- <a href="#" class="btn btn-success">GM (All)</a>
          <a href="#" class="btn btn-success">DGM/Addl. (All)</a>
        <a href="#" class="btn btn-success text-white">Manager/DCE/DCC (All)</a>
        <hr class="">
        <a href="#" class="btn btn-secondary text-white">DM/Executive Engr./Chemist (All)</a>
      <a href="#" class="btn btn-secondary text-white"> AE/AM (All)</a>
      <a href="#" class="btn btn-secondary text-white">SAE/Junior Officer (All)</a> -->

      </h4>
    </div>
    <div id="menu2" class="container tab-pane fade"><br>
      <h3>General Manager (Cadre/Division wise)</h3>
      <h4 class=" float-center text-center">
          <a href="#" class="btn btn-warning text-dark">Sr. GM (ICT)</a>
          <a href="#" class="btn btn-warning text-dark">Sr. GM (Admin)</a>
          <a href="#" class="btn btn-warning text-dark">Sr. GM (Com.)</a>
          <a href="#" class="btn btn-warning text-dark">Sr. GM (MTS)</a>
          <a href="#" class="btn btn-warning text-dark">Sr. GM (Accounts)</a>
          <a href="#" class="btn btn-warning text-dark">Sr. GM (MD/ED)</a>
      </h4>
    </div>
    <div id="menu3" class="container tab-pane fade"><br>
      <h3>Deputy General Manager (Cadre/Division wise)</h3>
      <h4 class=" float-center text-center">
        <a href="#" class="btn btn-primary text-white"> GM (ICT)</a>
        <a href="#" class="btn btn-primary text-white"> GM (Admin)</a>
        <a href="#" class="btn btn-primary text-white"> GM (Com.)</a>
        <a href="#" class="btn btn-primary text-white"> GM (MTS)</a>
        <a href="#" class="btn btn-primary text-white"> GM (Accounts)</a>
    </h4>
    </div>
        <div id="menu4" class="container tab-pane fade"><br>
      <h3>Manager (Cadre/Division wise)</h3>
      <h4 class=" float-center text-center">
        <hr class="">
      <a href="#" class="btn btn-info text-dark"> Manager (ICT)</a>
        <a href="#" class="btn btn-info text-dark"> Manager (Admin)</a>
        <a href="#" class="btn btn-info text-dark"> Manager (Com.)</a>
        <a href="#" class="btn btn-info text-dark"> Manager (MTS)</a>
        <a href="#" class="btn btn-info text-dark"> Manager (Accounts)</a>
        <a href="#" class="btn btn-warning text-dark">Print Seniority List</a>
    </h4>
    </div>
        <div id="menu5" class="container tab-pane fade"><br>
      <h3>Deputy Manager (Cadre/Division wise)</h3>
      <h4 class=" float-center text-center">
        <a href="#" class="btn btn-primary text-white"> GM (ICT)</a>
        <a href="#" class="btn btn-primary text-white"> GM (Admin)</a>
        <a href="#" class="btn btn-primary text-white"> GM (Com.)</a>
        <a href="#" class="btn btn-primary text-white"> GM (MTS)</a>
        <a href="#" class="btn btn-primary text-white"> GM (Accounts)</a>
    </h4>
    </div>
  </div>

    
 <!-- <img class="mx-auto d-block watermark" src="image/bcic.jpg" width="70" height="70"/> -->
  <h3 class="mt-2 text-center"></h3>
  <h4 class=" text-center"></h4>
    <h4 class=" float-center text-center">
<!-- 	<a href="view_users.php" class="btn btn-success">Senior GM (All)</a>
	<a href="#" class="btn btn-success">GM (All)</a>
    <a href="#" class="btn btn-success">DGM/Addl. (All)</a>
	<a href="#" class="btn btn-success text-white">Manager/DCE/DCC (All)</a>
	<hr class=""> -->
	
  <!-- <a href="#" class="btn btn-secondary text-white">DM/Executive Engr./Chemist (All)</a>
	<a href="#" class="btn btn-secondary text-white"> AE/AM (All)</a>
  <a href="#" class="btn btn-secondary text-white">SAE/Junior Officer (All)</a>
	<hr class=""> -->
 <!--  <a href="#" class="btn btn-warning text-dark">Sr. GM (ICT)</a>
	<a href="#" class="btn btn-warning text-dark">Sr. GM (Admin)</a>
  <a href="#" class="btn btn-warning text-dark">Sr. GM (Com.)</a>
  <a href="#" class="btn btn-warning text-dark">Sr. GM (MTS)</a>
  <a href="#" class="btn btn-warning text-dark">Sr. GM (Accounts)</a>
  <a href="#" class="btn btn-warning text-dark">Sr. GM (MD/ED)</a> -->
	
<!-- <hr class=""> -->
<!-- <a href="#" class="btn btn-primary text-white"> GM (ICT)</a>
  <a href="#" class="btn btn-primary text-white"> GM (Admin)</a>
  <a href="#" class="btn btn-primary text-white"> GM (Com.)</a>
  <a href="#" class="btn btn-primary text-white"> GM (MTS)</a>
  <a href="#" class="btn btn-primary text-white"> GM (Accounts)</a> -->
  
<!--   <hr class="">
<a href="#" class="btn btn-info text-dark"> Manager (ICT)</a>
  <a href="#" class="btn btn-info text-dark"> Manager (Admin)</a>
  <a href="#" class="btn btn-info text-dark"> Manager (Com.)</a>
  <a href="#" class="btn btn-info text-dark"> Manager (MTS)</a>
  <a href="#" class="btn btn-info text-dark"> Manager (Accounts)</a>
  <a href="#" class="btn btn-warning text-dark">Print Seniority List</a> -->
  <hr class="">
<a href="../dashboard.php" class="btn btn-primary text-white">Previous Page</a>
   
	</h4>
  
  
  </div>
  <!--3rd col-->
  <div class="col-sm-0 "> </div>
	
	</div>
 
  </div>
  <div class="card-footer"><h6 class="text-right float-end">Design & Developed by Md. Tareq Emran, Programmer, BCIC.</h6></div>
</div>
 
</div>
</body>
</html>
