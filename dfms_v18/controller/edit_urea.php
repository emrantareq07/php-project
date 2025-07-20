<?php
session_name('dfms');
//Start the session
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: dashboard.php");
  exit();
}
$table=$_SESSION['username'];
$user_type = $_SESSION['user_type'];
// if(!isset($_SESSION['username']) || ($_SESSION['role']!=="user")){
// if(($_SESSION['username']!=="sfcl") || ($_SESSION['role']!=="user")){
//    header("location: dashboard.php");
// }

include('../db/db.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">  
    <title> Edit</title>
</head>
<body>  
    <div class="container mt-5">
        <?php //include('message.php'); ?>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <?php
                if(@$_GET['updated'])
                {?>
                <div class="alert alert-success" role="alert">
                  Data Updated Successfully!!!
                </div>
                <?php }?>
                <div class="card shadow">
                    <div class="card-header">
                        <h4 class="text-uppercase"> Edit 
                            <a href="view_urea_report.php?username=<?php echo $_SESSION['username'] ?>" class="btn btn-danger float-end"><i class="fa fa-arrow-left"></i> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if(isset($_GET['id'])){
                            $id = mysqli_real_escape_string($conn, $_GET['id']);
                            $query = "SELECT * FROM $table WHERE id='$id' ";
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0){
                                //while($student=mysqli_fetch_array($query_run)){
                                $row = mysqli_fetch_array($query_run);
                                $product_produce=$row['product_produce'];
                                ?>
                        <form action="edit_urea_code.php" method="POST">
                             <div class="mb-3">                                         
                            <input type="text" class="form-control" name="id" readonly value="<?php echo !empty($row['id'])?$row['id']:''; ?>" hidden>
                            </div>

                            <?php
                            if ($_SESSION['user_type'] == 'sadmin') { 
                            ?>
                              <div class="mb-3">
                                 <label>Date</label>
                                 <input type="date" class="form-control" name="date" value="<?php echo !empty($row['date'])?$row['date']:''; ?>">
                            </div> 
                            <?php
                                }
                                else{
                                ?>
                            <div class="mb-3">
                             <label>Date</label>
                             <input type="text" class="form-control" name="date" readonly value="<?php echo !empty($row['date'])?$row['date']:''; ?>">
                            </div>
                                <?php
                                }
                            ?>  
                            <div class="form-mb-3">
                            <label for="product_produce" class="form-check-label">Product</label>
                            <input type="text" class="form-control" name="product_produce" value="<?php echo !empty($row['product_produce'])?$row['product_produce']:''; ?>">
                            </div>                                   
                                <div class="mb-3">
                                    <label>Daily</label>
                                    <input type="text" class="form-control" name="daily" value="<?php echo !empty($row['daily'])?$row['daily']:''; ?>">
                                </div>
                                    
                                <div class="mb-3">
                                    <label>Plant Load (%)</label>
                                    <input type="text" class="form-control" name="plant_load" value="<?php echo !empty($row['plant_load']) ? $row['plant_load'] : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label>Remarks</label>

                                <textarea class="form-control" name="remarks"><?php echo !empty($row['remarks']) ? $row['remarks'] : ''; ?></textarea>

                                    
                                    <!-- <input type="text" class="form-control" name="remarks" value="<?php echo !empty($row['remarks']) ? $row['remarks'] : ''; ?>"> -->
                                </div> 

                                <div class="mb-3">
                                    <button type="submit" name="update" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Update
                                    </button>
                                </div>

                                </form>
                                <?php
                            }
                        //}
                            else {
                                echo "<h4>No Such Id Found</h4>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3"></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>