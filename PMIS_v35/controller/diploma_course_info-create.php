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
                        <h4 class="text-uppercase"> Add Diploma Course
                            <a href="diploma_course_info_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="diploma_course_info-code.php" method="POST">

<!--                             <div class="mb-3">
                                <label>Children Name</label>
                                <input type="text" name="name" class="form-control">
                            </div> -->
                            <div class="form-group mb-3"><label for="type">Diploma Course Type:</label>
                                <select class="form-control" id="type" name="type" >
                                    
                                <option value="" disabled selected>--Select--</option>
                                     
                                <option value="Local Training">Local </option>
                                <option value="Foreign Training" >Foreign </option>
                                
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label>Title:</label>
                                <input type="title" name="title" placeholder="Enter Title" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Institute:</label>
                                <input type="text" name="institute" placeholder="Enter Institute" class="form-control">
                            </div>
                             <div class="form-group mb-3">
                                <label>Grade:</label>
                                <input type="text" name="grade" placeholder="Enter Grade" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Country</label>
                                <input type="text" name="country" placeholder="Enter Country" class="form-control">
                            </div>
                             <div class="form-group mb-3">
                                <label>Period</label>
                                <input type="title" name="period"  placeholder="Enter Period" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Year</label>
                                <input type="text" name="year"  placeholder="Enter Year" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label>Month-Year</label>
                                <input type="text" name="month_year"  placeholder="Enter Month/Year" class="form-control">
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