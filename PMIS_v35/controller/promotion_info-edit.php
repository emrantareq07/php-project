<!-- promotion_info-edit.php -->
<!-- service_history_info-edit.php -->
<?php
session_start();
require 'dbcon.php';
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

        <?php include('message.php'); ?>

        <div class="row">
          <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>

            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow border-primary">
                    <div class="card-header">
                        <h4 class="text-uppercase text-muted"><b>Promotion Particulars Edit </b>
                            <a href="view_promotion_info.php?emp_id=<?php echo $_SESSION['emp_id'] ?>" class="btn btn-danger float-end"><span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> BACK</a>
                        </h4>

                        <?php if(isset($message)): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                    <div class="card-body">

                        <?php
                        if(isset($_GET['id']))
                        {
                            $id = mysqli_real_escape_string($con, $_GET['id']);
                            $query = "SELECT * FROM promotion_info WHERE id='$id' ";
                            $query_run = mysqli_query($con, $query);

                            if(mysqli_num_rows($query_run) > 0)
                            {
                                //while($student=mysqli_fetch_array($query_run)){
                                $student = mysqli_fetch_array($query_run);
                                    $designation=$student['designation'];
                                    $place_of_posting=$student['place_of_posting']; 
                                    $scale=$student['scale'];
                                    $nature_of_promo=$student['nature_of_promo'];
                                    
                                ?>
                                <form action="promotion_info-code.php" method="POST">
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
									<label for="ref_no">Ref. No.:</label>
									 
									  <input class="form-control" placeholder="" type="text" name="ref_no" id="ref_no" value="<?=$student['ref_no'];?>">
									
									</div>

									<!-- <div class="form-group mb-3">
									<label for="designation">Designation:</label>
									 <input class="form-control" placeholder="Enter Designation" type="text" name="designation" id="designation" value="<?=$student['designation'];?>" >
									</div> -->

									<div class="form-group mb-3"><label for="designation">Designation:</label>
				                    <select name="designation" class="form-control">
									    <option value="">--Select--</option>
									    <?php 
									    $query ="SELECT designation FROM designation";
									    $result = $con->query($query);
									    if($result->num_rows> 0){
									        while($optionData=$result->fetch_assoc()){
									        $option =$optionData['designation'];
									    ?>
									    <?php
									    if(!empty($designation) && $designation== $option){
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

				                	<div class="form-group mb-3"><label for="place_of_posting">Place of Posting:</label>
										<select class="form-control" id="place_of_posting" name="place_of_posting" >
																				
								<option value="" disabled selected>--Select--</option>
								<option value="BCIC H.O" <?=$place_of_posting == 'BCIC H.O' ? ' selected="selected"' : '';?> >BCIC H.O</option>
		                        <option value="SFCL" <?=$place_of_posting == 'SFCL' ? ' selected="selected"' : '';?> >SFCL</option>
		                       	<option value="JFCL" <?=$place_of_posting == 'JFCL' ? ' selected="selected"' : '';?> >JFCL</option>
		                        <option value="CUFL" <?=$place_of_posting == 'CUFL' ? ' selected="selected"' : '';?> >CUFL</option>
		                        <option value="AFCCL" <?=$place_of_posting == 'AFCCL' ? ' selected="selected"' : '';?> >AFCCL</option>
		                        <option value="GPFPLC" <?=$place_of_posting == 'GPFPLC' ? ' selected="selected"' : '';?> >GPFPLC</option>
		                        <option value="CCCL" <?=$place_of_posting == 'CCCL' ? ' selected="selected"' : '';?> >CCCL</option>
		                        <option value="BISFL" <?=$place_of_posting == 'BISFL' ? ' selected="selected"' : '';?> >BISFL</option>
		                        <option value="GPUFP" <?=$place_of_posting == 'GPUFP' ? ' selected="selected"' : '';?> >GPUFP</option>
		                        <option value="Others" <?=$place_of_posting == 'Others' ? ' selected="selected"' : '';?> >Others</option>
                        			
										</select>
									</div>

                                  
                                    <div class="form-group mb-3">
									<label for="date_of_promot">Promotion Date:</label>
									 
									  <input class="form-control" placeholder="" type="date" name="date_of_promot" id="date_of_promot" value="<?=$student['date_of_promot'];?>">
									
									</div>
									<div class="form-group mb-3">
									<label for="granted_promo_date">Granted Date:</label>
									 <input class="form-control" placeholder="" type="date" name="granted_promo_date" id="granted_promo_date"  value="<?=$student['granted_promo_date'];?>">
									</div>

                                     <div class="form-group mb-3">
                                        <label for="nature_of_promo">Nature of Promotion:</label>
                                        <select class="form-control" id="nature_of_promo" name="nature_of_promo">
                                            <option value="" disabled selected>--Select--</option>
                                            <option value="Selection Grade" <?=$nature_of_promo == 'Selection Grade' ? ' selected="selected"' : '';?>>Selection Grade</option>
                                            <option value="Senior Scale" <?=$nature_of_promo == 'Senior Scale' ? ' selected="selected"' : '';?>>Senior Scale</option>
                                            <option value="Regular" <?=$nature_of_promo == 'Regular' ? ' selected="selected"' : '';?>>Regular</option>
                                        </select>
                                    </div>

								
									<!-- <div class="form-group mb-3">
									<label for="nature_of_promo">Nature Of Promotion:</label>
									 <input class="form-control" placeholder="Enter Organization" type="text" name="nature_of_promo" id="nature_of_promo" value="<?=$student['nature_of_promo'];?>">
									</div> -->
                                    <div class="form-group"><label for="scale">Pay Scale:</label>
                                    <select name="scale" class="form-control">
                                        <option value="">--Select--</option>
                                        <?php 
                                        include('db/db.php');
                                        $query ="SELECT scale FROM pay_scale_2015";
                                        $result = $conn->query($query);
                                        if($result->num_rows> 0){
                                            while($optionData=$result->fetch_assoc()){
                                            $option =$optionData['scale'];
                                        ?>
                                        <?php
                                        if(!empty($scale) && $scale== $option){
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
									
									<!-- <div class="form-group mb-3">
									<label for="scale">Scale:</label>
									 <input class="form-control" placeholder="Enter Scale" type="text" name="scale" id="scale" value="<?=$student['scale'];?>" >
									</div> -->

                                    <div class="form-group mb-3">
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