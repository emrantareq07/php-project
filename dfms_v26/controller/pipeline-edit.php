<?php 
session_start();
$table=$_SESSION['username'];//kaliganj)buffer
// $user_type=$_SESSION['user_type'];
// echo $user_type;

// Check if the user is already logged in, redirect to the dashboard
// if (!isset($_SESSION['username'])) {
//   header("Location: dashboard.php");
//   exit();
// }
require_once('../db/db.php');
//include('../include/header.php');
// if(isset($_SESSION['username']){
// $query="SELECT * FROM pipeline where to_office='$table' AND status='pending'";               
// $query_run = mysqli_query($conn, $query);  
// }



if(isset($_POST['update'])){
    $id=$_POST['id'];
    $date=$_POST['date'];
    $from_office=$_POST['from_office'];
    $to_office=$_POST['to_office'];
    $amount=$_POST['amount'];

    $query = "UPDATE pipeline SET  date='$date',from_office='$from_office', to_office='$to_office', amount='$amount' WHERE id='$id' ";
    $query_run = mysqli_query($conn, $query);
    if($query_run) {
        //$_SESSION['message'] = "<span class='text-success'><b> Information Updated Successfully!!!</b></span>";
        header("Location: pipeline_details.php");
        exit(0);
        }
}
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <title> Edit</title>
</head>
<body>
  
    <div class="container mt-4">

        <?php //include('message.php'); ?>

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card shadow border border-success border-2">
                    <div class="card-header">
                        <h4 class="text-uppercase"> Edit 
                            <a href="pipeline_details.php" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if(isset($_GET['id'])){
                            $id = mysqli_real_escape_string($conn, $_GET['id']);
                            $query = "SELECT * FROM pipeline WHERE id='$id'";
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0){
                                //while($student=mysqli_fetch_array($query_run)){
                                $student = mysqli_fetch_array($query_run);
                                    $from_office=$student['from_office'];

                                ?>
                        <form action="" method="POST">
                        <input type="hidden" name="id" value="<?= $student['id']; ?>">

                        <div class="mb-3">
                            <label for="date">Date</label>
                            <input type="date" name="date" id="date" value="<?=$student['date'];?>" class="form-control">                            
                        </div>  
                        <div class="form-group mb-3">
                            <label>From Factory/Port</label>
                           <!--  <select class="form-control" id="from_office" name="from_office" >

                            <option value="mongla_port" <?=$student['from_office'] == 'mongla_port' ? ' selected="selected"' : '';?>>Mongla Port</option>  
                            <option value="sfcl" <?=$student['from_office'] == 'sfcl' ? ' selected="selected"' : '';?>>SFCL</option>
                            <option value="chittagong_port" <?=$student['from_office'] == 'chittagong_port' ? ' selected="selected"' : '';?>>Chittagong Port</option>    
                                                
                            </select>  -->    
                            <input type="text" class="form-control" placeholder="" name="from_office" id="from_office"  value="<?php echo $_SESSION['username'];?>" readonly>                       
                        </div>  
                        <div class="form-group mb-3">
                            <label>To Factory/Port</label>
                            <select class="form-control" id="to_office" name="to_office" >
                            <option value="kaliganj_buffer" <?=$student['to_office'] == 'kaliganj_buffer' ? ' selected="selected"' : '';?>>Kaliganj Buffer</option>  
                            <option value="shiromoni_buffer" <?=$student['to_office'] == 'shiromoni_buffer' ? ' selected="selected"' : '';?>>Shiromoni Buffer</option>
                            <option value="jessore_buffer" <?=$student['to_office'] == 'jessore_buffer' ? ' selected="selected"' : '';?>>Jessore Buffer</option>     
                                                
                            </select>
                            
                        </div>
                         <div class="mb-3">
                            <label for="amount">Email</label>
                            <input type="text" name="amount" id="amount" value="<?=$student['amount'];?>" class="form-control">                            
                        </div>                       
				
                <div class="form-group mb-3">
                    <button type="submit" name="update" class="btn btn-primary">
                        Update
                    </button>
                </div>

            </form>
            <?php
        }
    //}
        else {
            echo "<h4>No Such Id Found</h4>";
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
<?php 
}else{

   
}

?>