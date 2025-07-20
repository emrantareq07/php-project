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

    <title>BCIC PMIS</title>
</head>
<body class="bg-light">
  
    <div class="container mt-3">

       <?php include('../view/message.php'); ?>

        <div class="row">
          <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>

            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow-lg">
                    <div class="card-header">
                        <h4 class="text-uppercase"> Add Welfare Grant Information
                            <a href="welfare_info_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="welfare_info-code.php" method="POST">

                            <div class="form-group mb-3">
                                <label>Ref. No:</label>
                                <input type="title" name="ref_no" placeholder="Enter Ref. No" class="form-control">
                            </div>
                             <div class="form-group mb-3">
                                <label>Granted Date:</label>
                                <input type="date" name="granted_date" placeholder="Enter Granted Date" class="form-control">
                            </div>
                            <div class="form-group mb-3"><label for="type">Welfare Grant Type:</label>
                                <select class="form-control" id="type" name="type" >
                                    
                                <option value="" disabled selected>--Select--</option>
                                     
                                <option value="Illness Grant">Illness Grant </option>
                                <option value="Educational Grant" >Educational Grant </option>
                                <option value="Marriage Grant">Marriage Grant </option>
                                <option value="Retirement Grant" >Retirement Grant </option>
                                <option value="Death Grant" >Death Grant </option>
                                </select>
                            </div>
                           
                            <div class="form-group mb-3">
                                <label>Grant Amount (TK.):</label>
                                <input type="text" name="amount" placeholder="Enter Grant Amount" class="form-control">
                            </div>
                             <div class="form-group mb-3">
                                <label>Fiscal Year:</label>
                                <input type="text" name="fiscal_year" placeholder="Enter Fiscal Year" class="form-control">
                            </div>
                            

                            <div class="form-group mb-3">
                                <button type="submit" name="save_student" class="btn btn-primary">Save</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>