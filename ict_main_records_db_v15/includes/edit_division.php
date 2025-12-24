<?php
    include_once('../db/db.php');
    include_once('header.php');  
    $id=$_GET['id'];
    $query=mysqli_query($conn,"select * from `division` where id='$id'");
    $row=mysqli_fetch_array($query);
?>
<div class="container mt-3 ">  
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 border rounded shadow-lg border-primary"><h3 class="text-uppercase">Edit Division/Department</h3>
    <form method="POST" action="update_division.php?id=<?php echo $id; ?>">        
    <div class="form-group">
        <label>Division:</label><input type="text" class="form-control" value="<?php echo $row['division']; ?>" name="division">
    </div>
    <br>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="submit">
        <a href="add_division.php" class="btn btn-outline-warning">Back</a>
    </div>
    </form>
    <br>
    </div>
 </div>
 <div class="col-sm-4"></div>
</div>
<?php
include_once('footer.php');  
?>
