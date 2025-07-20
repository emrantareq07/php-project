<!-- diploma_course_info-edit.php -->
<?php
session_start();
require '../db/dbcon.php';
$_SESSION['emp_id']=$_SESSION['emp_id'];
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
<body class="bg-light">
  
    <div class="container mt-2">

        <?php include('../view/message.php'); ?>

        <div class="row">
          <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>

            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow ">
                    <div class="card-header bg-dark">
                        <h4 class="text-uppercase text-white"><b>Nominees Information Edit </b>
                            <a href="nominees_info_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id']))
                        {
                            $id = mysqli_real_escape_string($con, $_GET['id']);
                            $query = "SELECT * FROM nominees WHERE id='$id' ";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                //while($student=mysqli_fetch_array($query_run)){
                                $student = mysqli_fetch_array($query_run);
                                    //$type=$student['type'];
                                ?>
                                <form action="nominees_info-code.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $student['id']; ?>">

                                 <!--    <div class="mb-3">
                                        <label>Student Name</label>
                                        <input type="text" name="user_id" value="<?=$student['user_id'];?>" class="form-control">
                                    </div> -->
                            <div class="mb-3">
                                <label>Emp ID.:</label>
                                <input type="emp_id" name="emp_id" value="<?=$student['emp_id'];?>" class="form-control" readonly>
                            </div>
                                    
                                    <div class="form-group mb-3">
                                <label>Name:</label>
                                <input type="text" name="name" placeholder="Enter Name" value="<?=$student['name'];?>" class="form-control">
                            </div>

                              <div class="form-group mb-3">
                                <label>Address:</label>
                                <textarea class="form-control" placeholder="Ex: C/O, Vill, P.O, P.S...." rows="2" id="address" name="address" value=""><?=$student['address'];?></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Relation:</label>
                                <input type="text" name="relation" value="<?=$student['relation'];?>" placeholder="Enter Relation" class="form-control">
                            </div>
                             <div class="form-group mb-3">
                                <label>Date of Birth:</label>
                                <input type="date" name="dob" value="<?=$student['dob'];?>" placeholder="Enter Grade" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Amount of Share (%):</label>
                                <input type="text" name="amount_of_share" value="<?=$student['amount_of_share'];?>" placeholder="Enter Amount" class="form-control">
                            </div>
                             <div class="form-group mb-3">
                                <label>Bank Name:</label>
                                <input type="title" name="bank_name" value="<?=$student['bank_name'];?>" placeholder="Enter Bank Name" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Account Number:</label>
                                <input type="text" name="account_no" value="<?=$student['account_no'];?>" placeholder="Enter Account Number" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Branch Name:</label>
                                <input type="text" name="branch_name" value="<?=$student['branch_name'];?>" placeholder="Enter Branch Name" class="form-control">
                            </div>


                                    <div class="mb-3">
                                        <button type="submit" name="update_student" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                            Update
                                        </button>
                                    </div>

                                </form>
                                <?php
                            }
                        //}
                            else
                            {
                                echo "<h4>No Such Id Found</h4>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>