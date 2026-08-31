<?php
session_name('pms_db');
session_start();

if(!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
     <!-- font awesome  -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <!-- <link href="assests/style.css" rel="stylesheet"> -->
    <title>Admin Dashboard</title>
  </head>
  <body>
    <!-- nnn -->
  <div class="container-fluid bg-light rounded">
  <div class="container pt-4 mt-3 bg-white shadow border">
    <div class="row" style="min-height: 90vh;">
      <!-- <div class="col-sm-12 p-4  bg-white shadow border">-->
  		<div class="col-sm-2"> </div>
  		<div class="col-sm-8"> 
  		<h1>Hello, <strong><?php echo $_SESSION['username'];?>!</strong></h1>
        <h2>Welcome to Doctor dashboard</h2>        
     
        <a href="medicine_mgtm.php" class="btn btn-success float-end">
          Medicine Manage
        </a>
      <a href="patient_mgtm.php" class="btn btn-success float-end">
          Patient Manage
        </a>
      </p>
      <p>
        <form id="downloadForm" action="download_database.php" method="post" class="px-3 py-2">
            <button class="btn btn-warning  " type="submit" name="submit">
                <i class="fas fa-download"></i> Download DB
            </button>
        </form>
        
  	  </p>
  		</div>
  		<div class="col-sm-2">  <a href="logout.php" class="btn btn-danger float-right">
          <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a></div>
    </div>
  </div>
  </div> 

  </body>
</html>

