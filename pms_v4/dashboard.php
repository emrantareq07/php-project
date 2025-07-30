<?php
session_name('pms_db');
session_start();

if(!isset($_SESSION['username'])) {
    header("Location: index.php");
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
        <h2>Welcome to admnin dashboard</h2>
        <p><a href="add_new_notice.php" class="btn btn-success float-end">
          <i class="fas fa-plus me-2"></i> Add Notice  
        </a></p>
        <p><a href="add_new_notice_datatable.php" class="btn btn-success float-end">
          Notice Board with datatables
        </a></p>
  	  <p><a href="add_new_innovation.php" class="btn btn-success float-end">
          Message
        </a></p>
  	  <p><a href="includes/latest_news.php" class="btn btn-success float-end">
          Latest News
        </a></p>
  	  <p><a href="includes/teachers_info.php" class="btn btn-success float-end">
          Teacher Info.
        </a>&nbsp;||&nbsp;<a href="add_photo.php" class="btn btn-success float-end">
          Photo Gallery
        	
        </a>&nbsp;||&nbsp;<a href="includes/manage_categories.php" class="btn btn-success float-end">
          Manage Categories & Sub-Category
         
        </a>
      </p>
      <p>
        <a href="includes/student_registration.php" class="btn btn-success float-end">
         Student Registration/Admission</a>
        <a href="includes/student_exam_fees.php" class="btn btn-success float-end">
          Report
        </a>
        <a href="includes/medicine_mgtm.php" class="btn btn-success float-end">
          Medicine Manage
        </a>
      <a href="includes/patient_mgtm.php" class="btn btn-success float-end">
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
  		<div class="col-sm-2">  <a href="includes/logout.php" class="btn btn-danger float-right">
          <i class="fas fa-sign-out-alt me-2"></i> Logout
        </a></div>
    </div>
  </div>
  </div> 

  </body>
</html>

