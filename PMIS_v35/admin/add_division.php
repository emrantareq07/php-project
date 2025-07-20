
<?php
include_once('../db/db.php');
include_once('../includes/header-utilities.php');  
 if(isset($_POST['submit'])){
      
   $division=$_POST['division'];
 
  
   $sql="INSERT INTO division(division) VALUES ('{$division}')";
  //$result=mysqli_query($conn,$sql); 
  if(mysqli_query($conn,$sql))
  {
      //header("Location: view_profile_details.php");
      header("Location: add_division.php?submitted=successfully");
  }
  else
    print mysqli_error();
 }
?>

<div class="container mt-3">
  
  <div class="row">
    <div class="col-sm-4">
      <a href="add_section.php" class="btn btn-primary float-end mt-5"><i class="fa fa-plus"></i> Add Section</a>
      
    </div>
    <div class="col-sm-4 border rounded shadow-lg border-primary"><h2 class="text-uppercase text-center">Add Divsion</h2>
     <?php
    if(@$_GET['updated'])
    {?>
    <div class="alert alert-success" role="alert">
      Data Updated Successfully!!!
    </div>
    <?php }?>
    <?php
    if(@$_GET['submitted'])
    {?>
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>Hi!</strong> Data Inserted Successfully!!!
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
  <?php }?>
  <?php
    if(@$_GET['deleted'])
    {?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>Hi!</strong> Data Deleted!!
  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
  <?php }?>
   <!-- <form method="POST" action="add_designation.php"> -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-floating mb-3 mt-3">
      <input type="text" class="form-control" id="division" placeholder="Enter Division" name="division" required="">
      <label for="division">Enter Division</label>
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
      <a href="utilities.php" class="btn btn-outline-info float-start mt-5">Previous Page</a>
    </div>

  </div>
  <br>
  <div class="row">

<div class="col-sm-4">
     <div class="table-wrapper">
      <h4 class="text-uppercase text-center"> Divsion List</h4>
    <table class="table table-bordered table-striped table-hover shadow-lg border-warning">
      <thead>
        <th>ID NO</th>
        <th>Division</th>
        <th>Action</th>
      </thead>
      <tbody>
        <?php
          
          $query=mysqli_query($conn,"select * from `division`");
          while($row=mysqli_fetch_array($query)){
            ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['division']; ?></td>
              <td>
                <a href="edit_division.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a>
                <a href="delete_division.php?id=<?php echo $row['id']; ?>"><i class="fa fa-trash" style="font-size:24px;color:red"></i></a>
              </td>
            </tr>
            <?php
          }
        ?>
      </tbody>
    </table>
    </div>
</div>
<div class="col-sm-8">
     <div class="table-wrapper">
      <h4 class="text-uppercase text-center"> Section List</h4>
    <table class="table table-bordered table-striped table-hover shadow-lg border-warning">
      <thead>
        <th>ID NO</th>
        <th>Section</th>
        <th>Division ID</th>
        <th>Action</th>
      </thead>
      <tbody>
        <?php
          
          $query=mysqli_query($conn,"select * from `section`");
          while($row=mysqli_fetch_array($query)){
            ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['name']; ?></td>
              <td><?php echo $row['division_id']; ?></td>
              <td>
                <a href="edit_section.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a>
                <a href="delete_section.php?id=<?php echo $row['id']; ?>"><i class="fa fa-trash" style="font-size:24px;color:red"></i></a>
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

