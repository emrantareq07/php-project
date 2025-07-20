
<?php
include_once('../db/db.php');
include_once('../includes/header-utilities.php'); 
 if(isset($_POST['submit'])){
      
   $designation=$_POST['designation'];  
   $sql="INSERT INTO designation(designation) VALUES ('{$designation}')";
  //$result=mysqli_query($conn,$sql); 
  if(mysqli_query($conn,$sql)) {
      //header("Location: view_profile_details.php");
      header("Location: add_designation.php?submitted=successfully");
  }
  else
    print mysqli_error();
 }
?>

<div class="container mt-3 ">  
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 border rounded shadow-lg border-primary"><h2 class="text-uppercase text-center">Add Designation</h2>
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
      <input type="text" class="form-control" id="designation" placeholder="Enter Designation" name="designation" required="">
      <label for="designation ">Designation</label>
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
      <a href="utilities.php" class="btn btn-outline-info float-end mt-5">Previous Page</a>
    </div>

  </div>
  <br>
  <div class="row">
    <div class="table-wrapper">
    <table class="table table-bordered table-striped table-hover shadow-lg border-warning">
      <thead>
        <th>ID NO</th>
        <th>Designation</th>
        <th>Action</th>
      </thead>
      <tbody>
        <?php
          
          $query = mysqli_query($conn, "SELECT * FROM `designation` ORDER BY `designation` ASC");
          while($row=mysqli_fetch_array($query)){
            ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['designation']; ?></td>
              <td>
                <a href="edit_designation.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a>
                <a href="delete_designation.php?id=<?php echo $row['id']; ?>"><i class="fa fa-trash" style="font-size:20px;color:red"></i></a>
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


