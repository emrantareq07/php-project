<?php
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];
include_once('../includes/header.php');
$val= $_GET['val'];
?>  
    <div class="container mt-3">
        <?php include('../view/message.php'); ?>
        <div class="row">
          <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow-lg">
                    <div class="card-header">
                        <h4 class="text-uppercase"> Add Training
                           <!--  <a href="training_info_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> BACK</a> -->

                            <a href="training_info_details.php?emp_id=<?php echo $_SESSION['emp_id']; ?>&val=<?php echo $val; ?>" class="btn btn-danger float-end"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- <form action="training_info-code.php" method="POST"> -->
                            <form action="training_info-code.php?emp_id=<?php echo $_SESSION['emp_id']; ?>&val=<?php echo $val; ?>" method="POST">

<!--                             <div class="mb-3">
                                <label>Children Name</label>
                                <input type="text" name="name" class="form-control">
                            </div> -->
                            <div class="form-group"><label for="type">Training Type:</label>
                                <select class="form-control" id="type" name="type" >
                                    
                                <option value="" disabled selected>--Select--</option>
                                     
                                <option value="Local Training">Local Training</option>
                                <option value="Foreign Training" >Foreign Training</option>
                                
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Title</label>
                                <input type="title" name="title" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Institute</label>
                                <input type="text" name="institute" class="form-control">
                            </div>
                            <?php 
                            //index.php
                            $connect = new PDO("mysql:host=localhost;dbname=pmis_db", "root", "");
                            $query = "
                                SELECT country_name FROM countries 
                                ORDER BY country_name ASC
                            ";
                            $result = $connect->query($query);
                            ?>
                            <div class="mb-3">
                                <label>Country</label>
                                <select name="country" class="form-select" id="country">
                                <option value="">Select Country</option>
                                <?php 
                                foreach($result as $row)
                                {
                                    echo '<option value="'.$row["country_name"].'">'.$row["country_name"].'</option>';
                                }
                                ?>  
                            </select>
                            <script>

                                var country = document.querySelector('#country');

                                dselect(country, {
                                    search: true
                                });

                            </script>
                            </div>

                          
                            <!-- <div class="mb-3">
                                <label>Country</label>
                                <input type="text" name="country" class="form-control">
                            </div> -->


                             <div class="mb-3">
                                <label>Period</label>
                                <input type="title" name="period" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Month-Year</label>
                                <input type="text" name="month_year" class="form-control">
                            </div>

                            <div class="mb-3">
                                <button type="submit" name="save_student" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
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