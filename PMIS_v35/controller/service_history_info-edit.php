<!-- service_history_info-edit.php -->
<?php
session_start();
require '../db/dbcon.php';
include('../db/db.php');
$_SESSION['emp_id']=$_SESSION['emp_id'];
$emp_id=$_SESSION['emp_id'];

// $sql="SELECT * FROM job_desc where emp_id='$emp_id'";
 
// $result=mysqli_query($conn,$sql);

//   $row=mysqli_fetch_array($result);
//   $grade=$row['grade'];
//   $scale=$row['scale'];
//   $basic_after_incr=$row['basic_after_incr'];
//   $nature_of_promo=$row['nature_of_promo'];
  

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

        <div class="row">
          <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>

            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow rounded-2 border border-primary">
                    <div class="card-header bg-dark text-white">
                        <h4 class="text-uppercase">Service History Edit 
                            <a href="service_history_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id'])) {
                            $id = mysqli_real_escape_string($con, $_GET['id']);
                            $query = "SELECT * FROM service_history WHERE id='$id' ";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0) {
                                //while($student=mysqli_fetch_array($query_run)){
                                $student = mysqli_fetch_array($query_run);
                                    $service_type=$student['service_type'];
                                    $place_of_posting=$student['place_of_posting'];
                                     $to_date=$student['to_date'];
                                    //$till_now=$student['till_now'];
                                    // $till_now=explode(",",$student['till_now']);
                                    $scale=$student['scale'];
                                    $designation=$student['designation'];
                                    $nature_of_promo=$student['nature_of_promo'];
                                ?>
                                <form action="service_history_info-code.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $student['id']; ?>">

                                    <div class="mb-3">
                                        <label>Emp ID.:</label>
                                        <input type="emp_id" name="emp_id" value="<?=$student['emp_id'];?>" class="form-control" readonly>
                                    </div>

                                    <div class="form-group"><label for="type">Service Type:</label>
                                    <select class="form-control" id="service_type" name="service_type" >
                                        
                                    <option value="" disabled selected>--Select--</option>
                                         
                                    <option value="Before Joining" <?=$service_type == 'Before Joining' ? ' selected="selected"' : '';?> >Before Joining</option>
                                    <option value="After Joining" <?=$service_type == 'After Joining' ? ' selected="selected"' : '';?> >After Joining</option>
                                    
                                    </select>
                                    </div>

                                    <div class="form-group mb-3">
									<label for="from_date">From Date:</label>
									 
									  <input class="form-control" placeholder="" type="date" name="from_date" id="from_date" value="<?=$student['from_date'];?>">
									
									</div>
                                    <div class="form-group mb-3">
                                    <label for="to_date">To Date:</label>
                                     <input class="form-control" placeholder="" type="date" name="to_date" id="to_date" value="<?=$student['to_date'];?>">
                                    </div>
									<!-- <div class="form-group mb-3">
									<label for="to_date">To Date:</label>
									 <input class="form-control" placeholder="" type="date" name="to_date" id="to_date" onchange="checkDate(this);" value="<?=$student['to_date'];?>">
									</div> -->

                                  <!--   <label for="till_now">Currently Working:</label>
                                    <div class="form-check mb-3"> -->
                                     
                                    <!-- <input type="checkbox" name="till_now" onclick="enableCreateUser()" id="till_now" size="17" <?= $student['till_now'] ? 'checked' : '' ?>> Till Now -->
                                <!-- <input type="checkbox" name="till_now" onclick="enableCreateUser()" id="till_now" size="17" <?= $student['till_now'] ? 'checked' : '' ?> value="yes"> Till Now -->

                                    
                                  <!--   <script type="text/javascript">
                                        function enableCreateUser() {
                                          if (document.getElementById("till_now").checked) {
                                            document.getElementById("to_date").disabled = true;
                                            //document.getElementById("pass").disabled = true;
                                            document.getElementById('to_date').valueAsDate = new Date();
                                          } else {
                                            document.getElementById("to_date").disabled = false;
                                            //document.getElementById("pass").disabled = false;
                                            //document.getElementById('to_date').value = '';
                                          }
                                        }

                                    window.addEventListener("load", function() {
                                    enableCreateUser(); // Call the function when the page loads to set the initial state of the to_date input
                                    });
                                    </script> -->
                                    <!-- </div> -->

									<div class="form-group mb-3">
									<label for="org_name">Organization:</label>
									 <input class="form-control" placeholder="Enter Organization" type="text" name="org_name" id="org_name" value="<?=$student['org_name'];?>">
									</div>
									
									<div class="form-group mb-3"><label for="place_of_posting">Place of Posting:</label>
										<select class="form-control" id="place_of_posting" name="place_of_posting" >
                                        <option value="" disabled selected>--Select--</option>
										 <?php 
                                            $query ="SELECT place_of_posting FROM place_of_posting";
                                            $result = $con->query($query);
                                            if($result->num_rows> 0){
                                                while($optionData=$result->fetch_assoc()){
                                                $option =$optionData['place_of_posting'];
                                            ?>
                                            <?php
                                            if(!empty($place_of_posting) && $place_of_posting== $option){
                                            ?>
                                            <option value="<?php echo $option; ?>" selected><?php echo $option; ?> </option>
                                            <?php 
                                        continue;
                                           }?>
                                            <option value="<?php echo $option; ?>" ><?php echo $option; ?> </option>
                                           <?php
                                            }}
                                            ?>
						                        			
										</select>
									</div>

									<div class="form-group mb-3">
                                    <label for="designation">Designation:</label>
                                    <input class="form-control" placeholder="Enter Organization" type="text" name="designation" id="designation" value="<?=$student['designation'];?>">
                                     
                                     <!-- <select class="form-control" id="designation" name="designation" >
                                            <option value="" >--Select--</option> -->
                                         <?php 
                                            // $query ="SELECT designation FROM designation";
                                            // $result = $con->query($query);
                                            // if($result->num_rows> 0){
                                            //     while($optionData=$result->fetch_assoc()){
                                            //     $option =$optionData['designation'];
                                            ?>
                                            <?php
                                            // if(!empty($designation) && $designation== $option){
                                            ?>
                                            <!-- <option value="<?php echo $option; ?>" selected><?php echo $option; ?> </option> -->
                                            <?php 
                                        // continue;
                                           // }
                                           ?>
                                            <!-- <option value="<?php echo $option; ?>" ><?php echo $option; ?> </option> -->
                                           <?php
                                            // }}
                                            ?>
                                                            
                                        <!-- </select> -->
                                    </div>
                                        <div class="form-group mb-3">
                                    <label for="scale">Grade:</label>
                                     
                                     <select class="form-control" id="grade" name="grade" >
                                        <option value="" disabled selected>--Select--</option>
                                        <?php               
                                            // $sql = "SELECT grade FROM grade";
                                            // $result = mysqli_query($conn, $sql);                        
                                            // while($basicrow = //mysqli_fetch_array($result)){                     
                                            ?>  
                                             <!-- <option value="<?php //echo $basicrow['grade'];?>"                         -->
                                               
                                             <?php //if($row['grade']==$basicrow['grade']){
                                                   // echo "selected";
                                             //}
                                             ?>
                                             ><?php //echo $basicrow['grade'];?></option>                     
                                            <?php
                                           // }
                                            ?> 

                                             <?php 
                                            $query ="SELECT grade FROM grade";
                                            $result = $con->query($query);
                                            if($result->num_rows> 0){
                                                while($optionData=$result->fetch_assoc()){
                                                $option =$optionData['grade'];
                                            ?>
                                            <?php
                                            if(!empty($grade) && $grade== $option){
                                            ?>
                                            <option value="<?php echo $option; ?>" selected><?php echo $option; ?> </option>
                                            <?php 
                                        continue;
                                           }?>
                                            <option value="<?php echo $option; ?>" ><?php echo $option; ?> </option>
                                           <?php
                                            }}
                                            ?>
                                                            
                                        </select>
                                    </div>

                                        <div class="form-group mb-3">
                                    <label for="scale">Scale:</label>
                  
                                     <input class="form-control" placeholder="Enter scale Like 16000-38640" type="text" name="scale" id="scale" value="<?=$student['scale'];?>">
                                    </div>

                                        <div class="form-group mb-3">
                                    <label for="scale">Basic:</label>
                  
                                     <input class="form-control" placeholder="Enter basic like 16000" type="text" name="basic" id="basic" value="<?=$student['basic'];?>">
                                    </div>

<!-- 									<div class="form-group mb-3">
									<label for="scale">Scale:</label>
                  
                                     <select class="form-control" id="scale" name="scale" >
                                        <option value="" disabled selected>--Select--</option>
                                        <?php
                                       // $sql = "SELECT * FROM pay_scale_2015";
                                        //$result = mysqli_query($conn, $sql);

                                        //while ($scalerow = mysqli_fetch_array($result)) {
                                          //  $selected = ($row['scale'] == $scalerow['scale']) ? "selected" : "";
                                            ?>
                                            <option value="<?php //echo $scalerow['id']; ?>" <?php echo $selected; ?>>
                                                <?php //echo $scalerow['scale']; ?>
                                            </option>
                                        <?php
                                       // }
                                        ?>
                                                            
                                        </select>
									</div> -->

<!--                                  <div class="form-group mb-3">
                                    <label for="scale">Basic:</label>
                                     
                                     <select class="form-control" id="basic" name="basic" >
                                        <option value="" disabled selected>--Select--</option>
                                          <?php
                                            //$sql = "SELECT * FROM basic";
                                            //$result = mysqli_query($conn, $sql);

                                            //while ($basicrow = mysqli_fetch_array($result)) {
                                                //$selected = ($row['basic_after_incr'] == //$basicrow['basic']) ? "selected" : "";
                                                ?>
                                                <option value="<?php //echo $basicrow['id']; ?>" <?php //echo $selected; ?>>
                                                    <?php //echo $basicrow['basic']; ?>
                                                </option>
                                            <?php
                                            //}
                                            ?>  
                                                            
                                        </select>
                                    </div> -->

                                    <div class="form-group mb-3"><label for="nature_of_promo">Nature of Promotion:</label>
                                    <select class="form-control" id="nature_of_promo" name="nature_of_promo" >
                                        
                                    <option value="" >--Select--</option>
                                         
                                    <option value="Selection Grade" <?=$nature_of_promo == 'Selection Grade' ? ' selected="selected"' : '';?> >Selection Grade</option>
                                    <option value="Senior Scale"  <?=$nature_of_promo == 'Senior Scale' ? ' selected="selected"' : '';?> >Senior Scale</option>
                                    <option value="Time Scale"  <?=$nature_of_promo == 'Time Scale' ? ' selected="selected"' : '';?> >Time Scale</option>
                                    <option value="Regular"  <?=$nature_of_promo == 'Regular' ? ' selected="selected"' : '';?> >Regular</option>
                                    </select>
                                </div>


                                    <div class="form-group mb-3">
                                        <button type="submit" name="update_student" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                            Update
                                        </button>
                                    </div>

                                </form>
                                <?php
                            }
                        //}
                            else  {
                                echo "<h4>No Such Id Found</h4>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <p></p>
            </div>
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<script type="text/javascript">
//for Scale and Basic
$( "select[name='scale']" ).change(function () {

    var scaleID = $(this).val();

    if(scaleID) {

        $.ajax({

            url: "../ajax/ajaxpro_for_payscale.php",

            dataType: 'Json',

            data: {'id':scaleID},

            success: function(data) {

                $('select[name="basic"]').empty();

                $.each(data, function(key, value) {

                    $('select[name="basic"]').append('<option value="'+ key +'">'+ value +'</option>');

                });
            }

        });

    }else{

        $('select[name="basic"]').empty();

    }

});
</script>