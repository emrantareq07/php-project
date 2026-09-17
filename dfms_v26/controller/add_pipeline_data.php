<?php 
ob_start(); 
 session_start();
// $table=$_SESSION['username'];
// $user_type=$_SESSION['user_type'];
// echo $user_type;

// Check if the user is already logged in, redirect to the dashboard
// if (!isset($_SESSION['username'])) {
//   header("Location: dashboard.php");
//   exit();
// }

// Ensure the correct timezone is set
date_default_timezone_set('Asia/Dhaka'); // Correct timezone identifier for Dhaka, Bangladesh
 
$username=$_SESSION['username'];
require_once('../db/db.php');
include('../include/header.php');
if(isset($_POST['submit_pipeline'])){

    $date=$_POST['date'];
    $from_office=$_POST['from_office'];
    $to_office=$_POST['to_office'];
    $amount=$_POST['amount'];
    $sql="INSERT INTO pipeline (date,from_office,to_office,amount) values('{$date}','{$from_office}','{$to_office}','{$amount}')";
    $result = mysqli_query($conn, $sql);
    header("Location: pipeline_details.php?office_type=" . urlencode($_SESSION['office_type']));
  exit();
    }
ob_end_flush();
?>
  
<div class="container mt-3">  
  <div class="row"><div class="col-sm-12 border rounded border-2 border-success">
    <h4 class="text-dark text-center text-uppercase"> <b class="text-danger "> <?= $_SESSION['username'];?></b> </h4>
  </div></div>
<div class="col-sm-12 border pt-2 my-2 mt-2 shadow rounded border-2 border-success">

<div class="row">
  <h5 for="date" class="text-uppercase"> Pipeline Records <a href="pipeline_details.php" class="btn btn-primary float-end"><i class="fa fa-arrow-left" ></i> Previous Page</a></h5>
  
</div>
<div class="row">
<form action="add_pipeline_data.php" method="POST">
    <div class="row">
      <div class="col">
        <div class="form-group">
        <label for="date" class="form-check-label"> Choose a Date</label>

        <input type="date" class="form-control" placeholder="Enter Date" name="date" id="date" onChange="checkAvailability()" required value="<?php echo date("Y-m-d");?>">          
         <span id="user-availability-status"></span>          
        <p><img src="LoaderIcon.gif" id="loaderIcon" style="display:none" /></p>             
      </div>
      </div>

      <div class="col">
      <div class="form-group">
          <label for="from_office" class="form-check-label">From Factory/Port</label>
        <?php 
          if($_SESSION['username'] == 'bcic_mkt') {
            echo '<select class="form-select form-select mb-3" name="from_office" aria-label="Default select example">
              <option selected>Select</option>
              <option value="mongla_port">Mongla Port</option>
              <option value="sfcl">SFCL</option>
              <option value="chittagong_port">Chittagong Port</option>
            </select>';
          } else {
            // Close the PHP tag to insert the HTML directly
            ?>
            <input type="text" class="form-control" placeholder="" name="from_office" id="from_office" value="<?php echo $_SESSION['username']; ?>" readonly>
            <?php
            // Reopen the PHP tag to continue PHP scripting if necessary
          }
          ?>
     
                      
       </div>
    </div>


      <div class="col">
      <div class="form-group">
          <label for="to_office" class="form-check-label">To Buffer/Factory</label>

          <select class="form-select form-select mb-3" name="to_office" aria-label="Default select example">
              <option selected > Select </option>
              <option value="kaliganj_buffer">Kaliganj Buffer</option>
              <option value="shiromoni_buffer">Shiromoni Buffer</option>
              <option value="jessore_buffer">Jessore Buffer</option>
            </select>
       </div>
    </div>

    <div class="col">
       <div class="form-group">
          <label for="amount" class="form-check-label">Pipeline Amount (MT)</label>
          <input type="text" class="form-control" placeholder=" Pipeline Amount (MT)" name="amount"  value="" id="amount" >
       </div>
       </div>

    <div class="col">
       <div class="form-group" align="center"><label for="pipeline" class="form-check-label"></label>        
        <button type="submit" name="submit_pipeline" class="btn  btn-primary "><i class="fa fa-plus" ></i> Add</button>
        
        </div>
      </div>   
    
    </div>
  </form>
</div>
</div>
</div>

<?php 

include('../include/footer.php');?>