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
                        <h4 class="text-center text-uppercase text-muted"><b>Nominees Details</b>
                            <a href="nominees_info-create.php" class="btn btn-primary float-end"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add </a>
                            <a href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-primary float-start"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>  Previous Page</a>
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
                                 <th>Nominess Name</th>
                                 <th>Address</th>
                                 
                                 <th>Relationship with the Member</th>
                                 <th>Date of Birth</th>
                                 <th>Amount of Share (%)</th>
                                 <th>Bank Name</th>
                                 <th>Account Number</th>
                                 <th>Branch Name</th>
                                 <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                include('../db/db.php');

                                 if(isset($_SESSION['emp_id'])){
                                      //$edit_id=$_GET['edit'];
                                      $emp_id=$_SESSION['emp_id'];
                                      $query="SELECT * FROM nominees where emp_id='$emp_id'";
                                     }
                                    //$query = "SELECT * FROM training_info";
                                    $query_run = mysqli_query($con, $query);

                                    if(mysqli_num_rows($query_run) > 0)
                                    {
                                        foreach($query_run as $student)
                                        {
                                            ?>
                                            <tr>
                                                <!-- <td><?= $student['id']; ?></td> -->
                                                <!-- <td><?= $student['user_id']; ?></td> -->
                                                <!-- <td><?= $student['emp_id']; ?></td> -->
                                                <!-- <td><?= $student['ref_no']; ?></td> -->
                                                <td><?= $student['name']; ?></td>
                                                <td><?= $student['address']; ?></td>
                                                <td><?= $student['relation']; ?></td>
                                                <td><?= $student['dob']; ?></td>
                                                <td><?= $student['amount_of_share']; ?></td>
                                                <td><?= $student['bank_name']; ?></td>
                                                <td><?= $student['account_no']; ?></td>
                                                <td><?= $student['branch_name']; ?></td>
                                                <td class="text-center">
                                                   
                                                    <a href="nominees_info-edit.php?id=<?= $student['id']; ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-edit"></span> </a>
                                                    <form action="award_penalty_info-code.php" method="POST" class="d-inline">
                                                        <button type="submit" name="delete_student" value="<?=$student['id'];?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash"></span> </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    else
                                    {
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