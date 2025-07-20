

<?php
    include_once('../db/db.php');
     include_once('../includes/header-utilities.php');  
    $id=$_GET['id'];
    $query=mysqli_query($conn,"select * from `designation` where id='$id'");
    $row=mysqli_fetch_array($query);
?>
<div class="container mt-3 ">
  
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 border rounded shadow-lg border-primary"><h3 class="text-uppercase">Edit Designation</h3>

        <form method="POST" action="update_designation.php?id=<?php echo $id; ?>">
        
        <div class="form-group">
        <label>Designation:</label><input type="text" class="form-control" value="<?php echo $row['designation']; ?>" name="designation">
    </div>
    <br>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="submit">
        <a href="add_designation.php" class="btn btn-outline-warning">Back</a>
    </div>
        
        
    </form>
    <br>
     </div>
 </div>
 <div class="col-sm-4"></div>
</div>

<?php

include_once('../includes/footer-print.php');  
?>