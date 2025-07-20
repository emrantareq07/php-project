
<?php
include('db/db.php');
if(isset($_SESSION['emp_id'])){
	 // $edit_id=$_GET['edit'];
	 $emp_id=$_SESSION['emp_id'];
	 //$user_id=$_SESSION['id'];
	 // $sql="SELECT * from edu_info where user_id='$edit_id'";
	  $sql="SELECT * from edu_info where emp_id='$emp_id'";
	// $sql="SELECT * FROM users where id='$edit_id'";
 }
$result=mysqli_query($conn,$sql);
//if(!$result){
    //die("ERROR".mysqli_error($conn));
 //while($row=mysql_fetch_object($result)){
if (mysqli_num_rows($result) > 0) {
	while($row=mysqli_fetch_array($result)){
	 
	$ssc_exam=$row['ssc_exam']; 
	$ssc_group_name=$row['ssc_group_name'];
	$ssc_inst_name=$row['ssc_inst_name'];
	$ssc_board_or_univ=$row['ssc_board_or_univ'];
	$ssc_div_or_cgpa=$row['ssc_div_or_cgpa'];
	$ssc_passing_year=$row['ssc_passing_year'];
	?>

			<div class="form-group">
				 <select class="form-control" id="ssc_exam" name="ssc_exam" tabindex="6">
						<option value="" disabled selected>--Select--</option>
						<?php
                        //require_once('db/db.php');

						$sql = "SELECT * FROM exam where id between 1 and 6";
						$result = mysqli_query($conn, $sql);
						while($row = mysqli_fetch_array($result))
						{
						 echo "<option value='".$row['id']."'>".$row['name']."</option>";
						}?>	
					 </select>
					</div>
				<div class="form-group">
				<label for="ssc_group_name">Subject/Group/Degree:</label>
				 <!-- <input class="form-control" placeholder="Enter Group/Subject" type="text" name="ssc_group_name" id="ssc_group_name" > -->
				 <select class="form-control" id="ssc_group_name" name="ssc_group_name" tabindex="7">
						<option value="" disabled selected>--Select--</option>
						
					 </select>
					 <script>

					 	$( "select[name='ssc_exam']" ).change(function () {
				            var examID = $(this).val();

				            if (examID) {
				                $.ajax({
				                    url: "ajaxpro_for_ssc_exam.php",
				                    dataType: 'Json',
				                    data: {'id': examID},
				                    success: function(data) {
				                        $('select[name="ssc_group_name"]').empty();
				                        $.each(data, function(key, value) {
				                            $('select[name="ssc_group_name"]').append('<option value="'+ key +'">'+ value +'</option>');
				                        });
				                    }
				                });
				            } else {
				                $('select[name="ssc_group_name"]').empty();
				            }
				        });



					</script>
				</div>	


	<?php 
		}
	}

	?>
				 <div class="form-group"> -->
					 <select class="form-control" id="ssc_exam" name="ssc_exam" > 
				
				  <option value="">--Select--</option>
					    <?php 
					    $query ="SELECT name FROM exam where id between 1 and 6";
					    $result = $conn->query($query);
					    if($result->num_rows> 0){
					        while($optionData=$result->fetch_assoc()){
					        $option =$optionData['name'];
					        $id=$optionData['id'];
					    ?>
					    <?php
						    if(!empty($ssc_exam) && $ssc_exam== $option){
						    ?>
						    <option value="<?php echo $id; ?>" selected><?php echo $option; ?> </option>
						    <?php 
								continue;
						   }?>
						    <option value="<?php echo $id; ?>" ><?php echo $option; ?> </option>
						   <?php
						    }}
						    ?>
					  </select> 


					 </div>
				 <div class="form-group">
				<label for="ssc_group_name">Subject/Group/Degree:</label>
							 
				  <select class="form-control" id="ssc_group_name" name="ssc_group_name" tabindex="7" value="">
						<option value="" disabled selected>--Select--</option>

						<?php
                        
                        $query = "SELECT * FROM edu_info where emp_id='$emp_id'";
				        $result = mysqli_query($conn, $query);
				        $row2 = mysqli_fetch_array($result);
				        $ssc_exam = $row2['ssc_exam'];
				        $ssc_group_name = $row2['ssc_group_name'];

				         $sql5 = "SELECT * FROM subject_ssc where name like '%$ssc_group_name%'";
				        $result5 = mysqli_query($conn, $sql5);
				        $row5 = mysqli_fetch_array($result5);
                        $subject_id=$row5['subject_id'] ;

				        $sql2 = "SELECT * FROM subject_ssc where subject_id='$subject_id'";
				        $result2 = mysqli_query($conn, $sql2);

				        while ($row1 = mysqli_fetch_array($result2)) {
				            $selected = ($row2['ssc_group_name'] == $row1["name"]) ? 'selected="selected"' : '';
				        ?>
				            <option value="<?=$row1['id'];?>" <?=$selected;?>>
				                <?=$row1['name'];?>
				            </option>
				        <?php
				        }
				       // mysqli_close($conn); // Close the database connection
				        ?>
						
					 </select>
					 <script>

					 	$( "select[name='ssc_exam']" ).change(function () {
				            var examID = $(this).val();

				            if (examID) {
				                $.ajax({
				                    url: "ajaxpro_for_ssc_exam.php",
				                    dataType: 'Json',
				                    data: {'id': examID},
				                    success: function(data) {
				                        $('select[name="ssc_group_name"]').empty();
				                        $.each(data, function(key, value) {
				                            $('select[name="ssc_group_name"]').append('<option value="'+ key +'">'+ value +'</option>');
				                        });
				                    }
				                });
				            } else {
				                $('select[name="ssc_group_name"]').empty();
				            }
				        });
					

					</script>
				</div>	 