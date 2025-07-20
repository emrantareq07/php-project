<?php
session_start();
require 'dbcon.php';

// session_start();
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

    <title>Training View</title>
</head>
<body>

    <div class="container mt-5">

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Training View Details 
                            <a href="edit_training_info_4.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end">BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['emp_id']))
                        {
                            $emp_id = mysqli_real_escape_string($con, $_GET['emp_id']);
                            $query = "SELECT * FROM training_info WHERE emp_id='$emp_id' ";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                $student = mysqli_fetch_array($query_run);


                                ?>
                                <table class='table table-bordered table-striped text-center rounded'>
                                 <thead class='thead-dark text-center'>
                                 <tr >
                                 <th class=' text-center'>EmP ID</th>
                                 <th class=' text-center'> Training Type</th>
                                  <th class=' text-center'>Title</th>
                                  <th class=' text-center'> Institute </th>
                                  <th class=' text-center'> Country </th>
                                 <th class=' text-center'>Period</th>
                                 <th class=' text-center'> Month/ Year </th>
                                   
                                 
                                 </tr>
                                  </thead>
                                   <tbody>
                                       <tr>
                                     
                                      <td> <?=$student['emp_id'];?> </td>
                                      <td> <?=$student['type'];?></td>
                                      <td><?=$student['title'];?>  </td>
                                      <td><?=$student['institute'];?> </td>
                                      <td><?=$student['country'];?> </td>
                                      <td> <?=$student['period'];?> </td>
                                      <td> <?=$student['month_year'];?> </td>
                                                                          
                                      </tr>
                                     
                                      </tbody>
                                      </table>
                                
                                   <!--  <div class="mb-3">
                                        <label>Student Name</label>
                                        <p class="form-control">
                                            <?=$student['id'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Student Email</label>
                                        <p class="form-control">
                                            <?=$student['user_id'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Student Phone</label>
                                        <p class="form-control">
                                            <?=$student['emp_id'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Training Type</label>
                                        <p class="form-control">
                                            <?=$student['type'];?>
                                        </p>
                                    </div>

                                    <div class="mb-3">
                                        <label>Title</label>
                                        <p class="form-control">
                                            <?=$student['title'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Institute</label>
                                        <p class="form-control">
                                            <?=$student['institute'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Country</label>
                                        <p class="form-control">
                                            <?=$student['country'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Period</label>
                                        <p class="form-control">
                                            <?=$student['period'];?>
                                        </p>
                                    </div>
                                    <div class="mb-3">
                                        <label>Month/ Year</label>
                                        <p class="form-control">
                                            <?=$student['month_year'];?>
                                        </p>
                                    </div> -->

                                <?php
                            }
                            else
                            {
                                echo "<h4>No Such Id Found</h4>";
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>