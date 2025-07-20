

<?php
    include_once('../db/db.php');
    $id=$_GET['id'];
    $query=mysqli_query($conn,"select * from `sub_cadre_header` where id='$id'");
    $row=mysqli_fetch_array($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>BCIC PMIS</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-3 ">
  
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 border rounded shadow-lg border-primary"><h3 class="text-uppercase">Edit Sub-Cadre Header</h3>

        <form method="POST" action="update_sub-cadre_header.php?id=<?php echo $id; ?>">
        <!-- <label>Firstname:</label><input type="text" value="<?php echo $row['firstname']; ?>" name="firstname"> -->
        <div class="form-group">
        <label>Sub-Cadre Header:</label><input type="text" class="form-control" value="<?php echo $row['header']; ?>" name="header">
    </div>
    <br>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" name="submit">
        <a href="add_sub-cadre_header.php" class="btn btn-outline-warning">Back</a>
    </div>
        
        
    </form>
    <br>
     </div>
 </div>
 <div class="col-sm-4"></div>
</div>

    
</body>
</html>