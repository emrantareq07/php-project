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
                        <h4 class="text-uppercase text-white"><b>Loan Information Edit </b>
                            <a href="loan_info_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id']))
                        {
                            $id = mysqli_real_escape_string($con, $_GET['id']);
                            $query = "SELECT * FROM loan_info WHERE id='$id' ";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                //while($student=mysqli_fetch_array($query_run)){
                                $student = mysqli_fetch_array($query_run);
                                    $type=$student['type'];
                                ?>
                                <form action="loan_info-code.php" method="POST">
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
                                        <label>Ref. No:</label>
                                        <input type="title" name="ref_no" placeholder="Enter Ref. No" class="form-control" value="<?=$student['ref_no'];?>">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label>Granted Date:</label>
                                        <input type="date" name="granted_date" placeholder="Enter Granted Date" class="form-control" value="<?=$student['granted_date'];?>">
                                    </div>
                                    <div class="form-group mb-3"><label for="type">Loan Type:</label>
                                        <select class="form-control" id="type" name="type" >
                                            
                                        <option value="" disabled selected>--Select--</option>
                                             
                                        <option value="House Building Loan" <?=$type == 'House Building Loan' ? ' selected="selected"' : '';?>>House Building Loan </option>
                                        <option value="Motorcycle Loan" <?=$type == 'Motorcycle Loan' ? ' selected="selected"' : '';?>>Motorcycle Loan </option>
                                        <option value="Welfare Loan" <?=$type == 'Welfare Loan' ? ' selected="selected"' : '';?>>Welfare Loan </option>
                                        <option value="Provident Fund Loan" <?=$type == 'Provident Fund Loan' ? ' selected="selected"' : '';?>>Provident Fund Loan </option>
                                        </select>
                                    </div>
                                   
                                    <div class="form-group mb-3">
                                        <label>Loan Amount (TK.):</label>
                                        <input type="text" name="amount" placeholder="Enter Loan Amount" class="form-control" value="<?=$student['amount'];?>">
                                    </div>
                                     <div class="form-group mb-3">
                                        <label>Fiscal Year:</label>
                                        <input type="text" name="fiscal_year" placeholder="Enter Fiscal Year" class="form-control" value="<?=$student['fiscal_year'];?>">
                                    </div>

                                    <div class="mb-3">
                                        <button type="submit" name="update_student" class="btn btn-primary">
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