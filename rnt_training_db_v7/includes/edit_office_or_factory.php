<?php
session_name('rnt_training_db');
session_start();
$username=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
$office=$_SESSION['office'];
$code = $_SESSION['code']; 

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}
include('../db/db.php');
include('header.php');  
$id=$_GET['id'];
$query=mysqli_query($conn,"select * from `place_of_posting` where id='$id'");
$row=mysqli_fetch_array($query);
?>
<div class="container mt-3 ">  
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 border rounded shadow-lg border-primary"><h3 class="text-uppercase">Edit Office or Factory</h3>
        <form method="POST" action="update_office_or_factory.php?id=<?php echo $id; ?>">        
        <div class="form-group">
        <label>Office or Factory:</label><input type="text" class="form-control" value="<?php echo $row['place_of_posting']; ?>" name="place_of_posting">
    </div>
    <br>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="submit">
        <a href="add_office_or_factory.php" class="btn btn-outline-warning">Back</a>
    </div>       
        
    </form>
    <br>
     </div>
 </div>
 <div class="col-sm-4"></div>
</div>
<?php
//include_once('../includes/footer-print.php');  
?>