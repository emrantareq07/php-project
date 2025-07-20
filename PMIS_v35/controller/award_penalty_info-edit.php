<!-- promotion_info-edit.php -->
<!-- service_history_info-edit.php -->
<?php
session_start();
require '../db/dbcon.php';
// include('db/db.php');
$_SESSION['emp_id']=$_SESSION['emp_id'];
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
<body class="bg-light">
  
    <div class="container mt-2">

        <?php include('../view/message.php'); ?>

        <div class="row">
          <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>

            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow border-primary">
                    <div class="card-header">
                        <h4 class="text-uppercase text-muted"><b>Award / Penalty Edit </b>
                            <a href="award_and_penalty_info.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> BACK</a>
                        </h4>

                        <?php if(isset($message)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id'])){
                            $id = mysqli_real_escape_string($con, $_GET['id']);
                            $query = "SELECT * FROM award_and_penalty WHERE id='$id' ";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0){
                                
                                $student = mysqli_fetch_array($query_run);
                                    $status=$student['status'];
                                     $nature=$student['nature'];                                   
                                ?>
                                <form action="award_penalty_info-code.php" method="POST">
                                    <input type="hidden" name="id" value="<?= $student['id']; ?>">

                                    <div class="mb-3">
                                        <label>Emp ID.:</label>
                                        <input type="emp_id" name="emp_id" value="<?=$student['emp_id'];?>" class="form-control" readonly>
                                    </div>

                                    <!-- <div class="form-group mb-3">
									<label for="ref_no">Ref. No.:</label>
									 
									  <input class="form-control" placeholder="" type="text" name="ref_no" id="ref_no" value="<?=$student['ref_no'];?>">
									
									</div> -->

									<div class="form-group mb-3">
									<label for="given_date">Given Date:</label>
									 <input class="form-control"  type="date" name="given_date" id="given_date" value="<?=$student['given_date'];?>" >
									</div>

									<!-- <div class="form-group mb-3"><label for="status">Status:</label>
				                    <select name="status" class="form-control">
									    <option value="">--Select--</option>
									    <?php 
									    //$query ="SELECT award_or_penalty FROM award";
									    //$result = $con->query($query);
									    //if($result->num_rows> 0){
									       // while($optionData=$result->fetch_assoc()){
									        //$option =$optionData['award_or_penalty'];
									    ?>
									    <?php
									    //if(!empty($award_or_penalty) && $award_or_penalty== $option){
									    ?>
									    <option value="<?php echo $option; ?>" selected><?php echo $option; ?> </option>
									    <?php 
									//continue;
									   //}?>
									   // <option value="<?php echo $option; ?>" ><?php echo $option; ?> </option>
									   <?php
									   // }}
									    ?>
									</select>
				                	</div> -->

				                	<div class="form-group mb-3"><label for="status">Status:</label>
										<select class="form-control" id="status" name="status" >
																				
        								<option value="" disabled selected>--Select--</option>
        								<option value="Award" <?=$status == 'Award' ? ' selected="selected"' : '';?> >Award</option>
        		                        <option value="Penalty" <?=$status == 'Penalty' ? ' selected="selected"' : '';?> >Penalty</option>	                       
                        		      </select>
									</div>
                                    <div class="form-group mb-3">
                                        <label for="nature">Nature:</label>
                                        <select class="form-control" id="nature" name="nature">
                                            <option value="" disabled selected>--Select--</option>
                                            <option value="Special Increment" <?=$nature == 'Special Increment' ? ' selected="selected"' : '';?>>Special Increment</option>
                                            <option value="Special Promotion" <?=$nature == 'Special Promotion' ? ' selected="selected"' : '';?>>Special Promotion</option>
                                            <option value="Cash Award" <?=$nature == 'Cash Award' ? ' selected="selected"' : '';?>>Cash Award</option>
                                            <option value="Commendation Certificate" <?=$nature == 'Commendation Certificate' ? ' selected="selected"' : '';?>>Commendation Certificate</option>
                                            <option value="Appreciation Letter" <?=$nature == 'Appreciation Letter' ? ' selected="selected"' : '';?>>Appreciation Letter</option>
                                            <option value="Innovation" <?=$nature == 'Innovation' ? ' selected="selected"' : '';?>>Innovation</option>
                                            <option value="NIS" <?=$nature == 'NIS' ? ' selected="selected"' : '';?>>NIS</option>
                                            <option value="Temporary Suspensions" <?=$nature == 'Temporary Suspensions' ? ' selected="selected"' : '';?>>Temporary Suspensions</option>
                                            <option value="Increment Held-up" <?=$nature == 'Increment Held-up' ? ' selected="selected"' : '';?>>Increment Held-up</option>
                                            <option value="Demotion" <?=$nature == 'Demotion' ? ' selected="selected"' : '';?>>Demotion</option>
                                            <option value="Warning" <?=$nature == 'Warning' ? ' selected="selected"' : '';?>>Warning</option>
                                            <option value="Show cause" <?=$nature == 'Show cause' ? ' selected="selected"' : '';?>>Show cause</option>

                                        </select>
                                    </div> 
                                    <div class="form-group ">
                                        <label for="special_increment">No. of Special Increment:</label>
                                        <input class="form-control" placeholder="" type="text" name="special_increment" id="special_increment" value="<?=$student['special_increment'];?>">
                                    </div>  

                                    
									<div class="form-group mb-3">
									<label for="from_date">From Date:</label>
									 <input class="form-control" placeholder="" type="date" name="from_date" id="from_date"  value="<?=$student['from_date'];?>">
									</div>                                    

								
									<div class="form-group mb-3">
									<label for="to_date">To Date:</label>
									 <input class="form-control" placeholder="Enter Organization" type="date" name="to_date" id="to_date" value="<?=$student['to_date'];?>">
									</div>
                                     <div class="form-group mb-3">
                                        <label for="ground_or_subject">Ground/Subject:</label>
                                        <!-- <textarea class="form-control" placeholder="Enter Ground/Subject...." rows="2" id="ground_or_subject"  name="ground_or_subject" ><?php echo $student['ground_or_subject'];?></textarea> -->

                                        <textarea class="form-control" placeholder="Enter Ground/Subject...." rows="2" id="ground_or_subject" name="ground_or_subject"><?php echo isset($student['ground_or_subject']) ? $student['ground_or_subject'] : ''; ?></textarea>

                                    </div>                               
									
                                    <div class="form-group mb-3">
                                        <button type="submit" name="update_student" class="btn btn-primary">
                                            Update
                                        </button>
                                    </div>

                                </form>
                                <?php
                            }
                        //}
                            else{
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