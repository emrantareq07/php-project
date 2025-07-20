<!-- service_history_info-create.php -->
<?php
session_start();
$_SESSION['emp_id']=$_SESSION['emp_id'];

include_once('../includes/header.php');
require_once('../db/db.php');
$val= $_GET['val'];
// echo $val;
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
  
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css"  />

	<script src="https://code.jquery.com/jquery-3.7.0.min.js" ></script>
	<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" ></script>
    <title>BCIC PMIS</title>
</head>
<body class="">
  
    <div class="container mt-2">

       <?php include('../view/message.php'); ?>

        <div class="row">
            <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>

            <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
                <div class="card shadow-lg border border-primary">
                    <div class="card-header bg-dark">
                        <h4 class="text-uppercase text-white"> Add Service History
                            <a href="service_history_details.php?emp_id=<?php echo $_SESSION['emp_id']; ?>&val=<?php echo $val; ?>" class="btn btn-danger float-end"> <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="service_history_info-code.php?emp_id=<?php echo $_SESSION['emp_id']; ?>&val=<?php echo $val; ?>" method="POST">
                       	<!-- <form action="service_history_info-code.php<?php echo isset($val) ? '?val=' . urlencode($val) : ''; ?>" method="POST"> -->


		                 <div class="form-group mb-3">
						<label for="service_type">Service Type:</label>
						 
						 <select class="form-control" id="service_type" name="service_type" required>
								
							<option value="" disabled selected>--Select--</option>
								 
							<option value="Before Joining">Before Joining</option>
							<option value="After Joining">After Joining</option>
							
							</select>
						 </div>			

				<div class="form-group mb-3">
				<label for="from_date">From Date:</label>
				
				  <input class="form-control" placeholder="" type="date" name="from_date" id="from_date" value="">				
				</div>

				<div class="form-group mb-3">
				<label for="to_date">To Date:</label>
				 <input class="form-control" placeholder="" type="date" name="to_date" id="to_date" value="">
				</div>			
			
				<div class="form-group mb-3">
				<label for="org_name">Organization:</label>
				 <input class="form-control" placeholder="Previously Worked Organization(Before Joining)" type="text" name="org_name" id="org_name" value="">
				</div>
				<!-- <div class="form-group mb-3">
				<label for="location">Office Location:</label>
				 <input class="form-control" placeholder="Enter Location" type="text" name="location" id="location" value="" >
				</div> -->

				<div class="form-group mb-3"><label for="place_of_posting">Place of Posting:</label>
					<select class="form-control" id="place_of_posting" name="place_of_posting" >
						
					<option value="">--Select--</option>
					<?php 
					$sql="SELECT place_of_posting FROM place_of_posting";
					 
					$result=mysqli_query($conn,$sql);
					 
					while($row=mysqli_fetch_array($result)){
						$office=$row['place_of_posting'];
						echo "<option value='$office'>$office</option>";
					}
					?>
					
					</select>
				</div>

				<div class="form-group mb-3">
				<label for="designation">Designation:</label>
				<input class="form-control" placeholder="Enter Designation" type="text" name="designation" id="designation" value="" >
				 <!-- <select class="form-control designation" id="designation"  name="designation" >
					<option value=""  >--Select--</option> -->
					<?php 
					// $sql="SELECT designation FROM designation";
					 
					// $result=mysqli_query($conn,$sql);
					 
					// while($rowd=mysqli_fetch_array($result)){
					// 	$designation=$rowd['designation'];
					// 	echo "<option value='$designation'>$designation</option>";
					// }
					?>					
				  <!-- </select> -->
				  
				</div>
				<div class="form-group">
				<label for="grade">Grade:</label>
				<select class="form-control"  name="grade" tabindex="">
					<option value="" disabled selected>--Select--</option>
					<?php	            
					// $sql = "SELECT * FROM grade";
					// $result = mysqli_query($conn, $sql);
					// while($row = mysqli_fetch_array($result)){
					//  echo "<option value='".$row['id']."'>".$row['grade']."</option>";
					// }

					 $sql="SELECT * FROM grade";
					 
						$result=mysqli_query($conn,$sql);
						 
						while($rowg=mysqli_fetch_array($result)){
							$grade=$rowg['grade'];
							echo "<option value='$grade'>$grade</option>";
						}

					?>	
				 </select>	
				
				</div>		
				
				<div class="form-group">
			    <label for="scale">Scale:</label>

			    <!-- important next use -->
			    <!-- <select class="form-control" id="scale" name="scale" tabindex="" required>
			        <option value="" disabled selected>--Select--</option> -->
			        <?php                                               
			        // $sql="SELECT id, scale FROM pay_scale_2015";
			        // $result = mysqli_query($conn, $sql);
			        // while($row = mysqli_fetch_array($result)){
			        //     echo "<option value='".$row['id']."'>".$row['scale']."</option>";
			        // }
			        ?>			
			    <!-- </select> -->

			    <input class="form-control" placeholder="Enter Scale Like 16000-38640  " type="text" name="scale" id="scale" value="">

				</div>

				<div class="form-group">
				    <label for="basic">Basic:</label>
				    <!-- important next use -->
				    <!-- <select class="form-control" id="basic" name="basic" tabindex="">
				        <option value="" disabled selected>--Select--</option>
				    </select> -->
				     <input class="form-control" placeholder="Enter Basic like 16000" type="text" name="basic" id="basic" value="">			
				</div>

				<div class="form-group"><label for="nature_of_promo">Nature of Promotion:</label>
					<select class="form-control" id="nature_of_promo" name="nature_of_promo" tabindex="">
						
					<option value="">--Select--</option>
						 
					<option value="Selection Grade">Selection Grade</option>
					<option value="Senior Scale">Senior Scale</option>
					<option value="Regular">Regular</option>
					<option value="Time Scale">Time Scale</option>
					</select>
				</div>


				<!-- <div class="form-group mb-3">
				<label for="scale">Scale:</label>
				<select class="form-control" id="scale" name="scale" >
						<option value="" disabled selected>--Select--</option> -->
						<?php
                        
      //                   $sql="SELECT scale FROM pay_scale_2015";
					 
						// $result=mysqli_query($conn,$sql);
						//  //while($row=mysql_fetch_object($result)){
						// while($row=mysqli_fetch_array($result)){
						// 	$scale=$row['scale'];
						// 	echo "<option value='$scale'>$scale</option>";
						// }

						// $sql = "SELECT * FROM pay_scale_2015";
						// $result = mysqli_query($conn, $sql);
						// while($row = mysqli_fetch_array($result))
						// {
						//  echo "<option value='".$row['id']."'>".$row['scale']."</option>";
						// }
						?>		 
					 <!-- </select>
				
				
				</div> -->
				<!-- <div class="col-sm-4 mb-3"> --><div class="form-group mb-3">
                 <button type="submit" name="save_student" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Save</button>
                 </div>
			</form>
		</div>

                    </div>
                    <p></p>

                </div>
                <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
            </div>
            
        </div>
    
    <script>
  	// jQuery('#designation').chosen();
	jQuery('#place_of_posting').chosen();
	// jQuery('#basic').chosen();
  </script>

<?php include('../includes/footer.php');?>

<script>
$(function() {
    $("select[name='scale']").change(function () {
        var scaleID = $(this).val();

        if(scaleID) {
            $.ajax({
                url: "../ajax/ajaxpro_for_payscale.php",
                dataType: 'json',
                data: {'id':scaleID},
                success: function(data) {
                    $('select[name="basic"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="basic"]').append('<option value="'+ key +'">'+ value +'</option>');
                    });
                }
            });
        } else {
            $('select[name="basic"]').empty();
        }
    });
});
</script>