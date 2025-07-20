
<?php
include_once('../db/db.php');
 include_once('../includes/header-utilities.php'); 
 if(isset($_POST['submit'])){
      
   $place_of_posting=$_POST['place_of_posting'];
 
  
   $sql="INSERT INTO place_of_posting(place_of_posting) VALUES ('{$place_of_posting}')";
  //$result=mysqli_query($conn,$sql); 
  if(mysqli_query($conn,$sql))
  {
      //header("Location: view_profile_details.php");
      header("Location: add_office_or_factory.php?submitted=successfully");
  }
  else
    print mysqli_error();
 }
?>


<div class="container mt-3 ">
  
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 border rounded shadow-lg border-primary"><h3 class="text-uppercase text-center">Add Office or Factory</h3>
           <?php
    if(@$_GET['deleted'])
    {?>
    <div class="alert alert-danger" role="alert">
      Data Deleted Successfully!!!
    </div>
    <?php }?>

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
    <div class="alert alert-success" role="alert">
      Data Inserted Successfully
    </div>
    <?php }?>
   <!-- <form method="POST" action="add_designation.php"> -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-floating mb-3 mt-3">
      <input type="text" class="form-control" id="place_of_posting" placeholder="Enter Office or Factory" name="place_of_posting" required="">
      <label for="place_of_posting "> Office or Factory</label>
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
      <a href="utilities.php" class="btn btn-outline-primary float-end mt-5 shadow-lg"><i class="fa fa-arrow-left"></i> Previous Page</a>
    </div>

  </div>
  <br>
  <div class="row">
    <div class="table-wrapper">
    <table class="table table-bordered table-striped table-hover shadow-lg border-warning">
      <thead>
        <th>ID NO</th>
        <th>Office/Factory Name</th>
        <th>Action</th>
      </thead>
      <tbody>
        <?php
          
          $query=mysqli_query($conn,"select * from `place_of_posting`");
          while($row=mysqli_fetch_array($query)){
            ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['place_of_posting']; ?></td>
              <td>
                <a href="edit_office_or_factory.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                <a href="delete_office_or_factory.php?id=<?php echo $row['id']; ?>"><i class="fa fa-trash" style="font-size:20px;color:red"></i></a>
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


