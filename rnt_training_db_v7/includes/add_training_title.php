<?php
session_name('rnt_training_db');
session_start();
$username=$_SESSION['username']; //chairman
$user_type=$_SESSION['user_type'];//admin
// $office=$_SESSION['office'];
$code = $_SESSION['code']; 

// Check if the user is already logged in, redirect to the dashboard
if (!isset($_SESSION['username'])) {
  header("Location: index.php");
  exit();
}
include('../db/db.php');
include('header.php');

 if(isset($_POST['submit'])){
      
   $t_name=$_POST['t_name'];  
   $sql="INSERT INTO training_list(t_name) VALUES ('{$t_name}')";
  //$result=mysqli_query($conn,$sql); 
  if(mysqli_query($conn,$sql)) {
      //header("Location: view_profile_details.php");
      header("Location: add_training_title.php?submitted=successfully");
  }
  else
    print mysqli_error();
 }
?>

<div class="container mt-3 ">  
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 border rounded shadow-lg border-primary"><h2 class="text-uppercase text-center">Add Training Title</h2>
     <?php
    if(@$_GET['submitted'])
    {?>
    <div class="alert alert-success" role="alert">
      Data Inserted Successfully
    </div>
    <?php }?>
   <!-- <form method="POST" action="add_designation.php"> -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-floating mb-3 mt-3">
      <input type="text" class="form-control" id="t_name" placeholder="Enter Training Title" name="t_name" required="">
      <label for="t_name ">Training Title</label>
    </div>
<!--     <div class="form-floating mt-3 mb-3">
      <input type="text" class="form-control" id="pwd" placeholder="Enter password" name="pswd">
      <label for="pwd">Password</label>
    </div> -->
    <button type="submit" name ="submit" class="btn btn-primary">Submit</button>
  </form>
    <br>
    </div>

    <div class="col-sm-4">
      <a href="../dashboard.php" class="btn btn-outline-info float-end mt-5"><i class="fa fa-arrow-left"></i> Previous Page</a>
    </div>

  </div>
  <br>
  <div class="row">
    <div class="table-wrapper">
    <table class="table table-bordered table-striped table-hover shadow-lg border-warning">
      <thead>
        <th>ID NO</th>
        <th>Training Title</th>
        <th>Action</th>
      </thead>
      <tbody>
        <?php
          
          $query = mysqli_query($conn, "SELECT * FROM `training_list` ORDER BY `t_name` ASC");
          while($row=mysqli_fetch_array($query)){
            ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['t_name']; ?></td>
              <td>
                <a href="edit_training_title.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a>
                <a href="delete_training_title.php?id=<?php echo $row['id']; ?>"><i class="fa fa-trash" style="font-size:20px;color:red"></i></a>
              </td>
            </tr>
            <?php
          }
        ?>
      </tbody>
    </table>
    </div>
  </div>
  </div>


</div>
<?php 
 include_once('../includes/footer.php');
?>


