<!-- diploma_course_info-create.php -->
<?php
session_start();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>BCIC PMIS</title>
</head>
<body class="bg-light">
  
    <div class="container mt-2">

        <?php include('../view/message.php'); ?>

        <div class="row">
          <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>

            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-dark text-white">
                        <h4 class="text-uppercase "> Add Nominees Inforamtion
                            <a href="nominees_info_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="nominees_info-code.php" method="POST">

<!--                             <div class="mb-3">
                                <label>Children Name</label>
                                <input type="text" name="name" class="form-control">
                            </div> -->
                          <!--   <div class="form-group mb-3"><label for="type">Diploma Course Type:</label>
                                <select class="form-control" id="type" name="type" >
                                    
                                <option value="" disabled selected>--Select--</option>
                                     
                                <option value="Local Training">Local </option>
                                <option value="Foreign Training" >Foreign </option>
                                
                                </select>
                            </div> -->
                            <div class="form-group mb-3">
                                <label>Name:</label>
                                <input type="text" name="name" placeholder="Enter Name" class="form-control">
                            </div>

                              <div class="form-group mb-3">
                                <label>Address:</label>
                                <textarea class="form-control" placeholder="Ex: C/O, Vill, P.O, P.S...." rows="2" id="address" name="address" ></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label>Relation:</label>
                                <input type="text" name="relation" placeholder="Enter Relation" class="form-control">
                            </div>
                             <div class="form-group mb-3">
                                <label>Date of Birth:</label>
                                <input type="date" name="dob" placeholder="Enter Grade" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Amount of Share (%):</label>
                                <input type="text" name="amount_of_share" placeholder="Enter Amount" class="form-control">
                            </div>
                             <div class="form-group mb-3">
                                <label>Bank Name:</label>
                                <input type="title" name="bank_name"  placeholder="Enter Bank Name" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Account Number:</label>
                                <input type="text" name="account_no"  placeholder="Enter Account Number" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Branch Name:</label>
                                <input type="text" name="branch_name"  placeholder="Enter Branch Name" class="form-control">
                            </div>

                            <div class="form-group mb-3">
                                <button type="submit" name="save_student" class="btn btn-block btn-primary"><i class="fa fa-save"></i> Save</button>
                            </div>

                        </form>

                    </div>

                </div>
                <br>
            </div>
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>