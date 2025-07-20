<?php
session_start();
require '../db/dbcon.php';

// session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
// include('db/db.php');
// Check if message is set
if(isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']); // Unset the session variable
}

$val=0;     
if(isset($_GET['val'])){
 $val= $_GET['val'];
 
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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <title>BCIC PMIS</title>
</head>
<body>
  
    <div class="container-fluid p-4 mt-2">

        <?php include('../view/message.php'); ?>
       
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-lg rounded border border-primary">
                    <div class="card-header">
                        <h4 class="text-center text-uppercase text-muted"><b>Award / Punishment Details</b>
                            <a href="award_penalty_info-create.php?emp_id=<?php echo $_SESSION['emp_id']; ?>&val=<?php echo $val; ?>" class="btn btn-primary float-end"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add </a>

                            <?php

                          if($val==1){
                            ?>
                            <a class="btn btn-primary float-start" href="../login/user_dashboard.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous page </a>
                            <?php
                          }
                          else{
                            ?>
                            <a class="btn btn-primary float-start" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> Previous page </a>

                            <?php
                          }

                          ?>
                            <!-- <a href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-primary float-start"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>  Previous Page</a> -->
                        </h4>


                    </div>

                    <div class="card-body">
                    	<?php if(isset($message)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                <!-- <th>ID</th> -->
                                 <!-- <th>User Id</th> -->
                                 <!-- <th>Emp Id</th> -->
                                 <!-- <th>Ref. No.</th> -->
                                 <th>Given Date</th>
                                 <th>Status</th>
                                 
                                 <th>Nature Of Award/ Penalty</th>
                                 <th>No of Sepecial Increment</th>
                                 <th>From Date</th>
                                 <th>To Date</th>
                                 <th>Ground / Subject</th>
                    <?php

                    if($val==1){
                      ?>
                      <?php
                    }
                    else{
                      ?>
                      
                      <th class="text-center">Action</th>

                      <?php
                    }
                    ?>
                        
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                include('../db/db.php');

                                 if(isset($_SESSION['emp_id'])){
                                      //$edit_id=$_GET['edit'];
                                      $emp_id=$_SESSION['emp_id'];
                                      $query="SELECT * FROM award_and_penalty where emp_id='$emp_id'";
                                     }                                    
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0) {
                                        foreach($query_run as $student){
                                            ?>
                                            <tr>
                                                <!-- <td><?= $student['id']; ?></td> -->
                                                <!-- <td><?= $student['user_id']; ?></td> -->
                                                <!-- <td><?= $student['emp_id']; ?></td> -->
                                                <!-- <td><?= $student['ref_no']; ?></td> -->
                                                <td><?= $student['given_date']; ?></td>
                                                <td><?= $student['status']; ?></td>
                                                <td><?= $student['nature']; ?></td>
                                                <td><?= $student['special_increment']; ?></td>
                                                <td><?= $student['from_date']; ?></td>
                                                <td><?= $student['to_date']; ?></td>
                                                <td><?= $student['ground_or_subject']; ?></td>
                                                <?php

                  if($val==1){
                    ?>
                    <?php
                  }
                  else{
                    ?>
                    <td class="text-center">
                      <!-- <a href="training_info-view.php?emp_id=<?= $student['emp_id']; ?>" class="btn btn-info btn-sm disabled"><span class="glyphicon glyphicon-eye-open"></span> View</a> -->
                    <a href="award_penalty_info-edit.php?id=<?= $student['id'];?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                    <!-- <?php echo $student['id']; ?>&val=<?php echo $val; ?> -->
                    <form action="award_penalty_info-code.php" method="POST" class="d-inline">
                        <button type="submit" name="delete_student" value="<?=$student['id'];?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                    </form>
                  </td>
                    <?php
                  }
                  ?>
                                                
                                                <!-- <td class="text-center">
                                                   
                                                    <a href="award_penalty_info-edit.php?id=<?= $student['id']; ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit"></span> </a>
                                                    <form action="award_penalty_info-code.php" method="POST" class="d-inline">
                                                        <button type="submit" name="delete_student" value="<?=$student['id'];?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> </button>
                                                    </form>
                                                </td> -->
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else{
                                        echo "<h4 class='text-danger text-center'><b> No Record Found!!! </b></h4>";
                                    }
                                ?>
                                
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>