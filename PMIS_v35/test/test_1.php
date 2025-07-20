<?php
session_start();
include('db/db.php');
//if(isset($_SESSION['emp_id'])){
	
	 $emp_id='5620-4';

	  $sql="SELECT * from edu_info where emp_id='$emp_id'";

 //}
$result=mysqli_query($conn,$sql);

	$row=mysqli_fetch_array($result);
	 
	$ssc_exam=$row['ssc_exam']; 
	$ssc_group_name=$row['ssc_group_name'];

     $sql5 = "SELECT * FROM exam where name like '%$ssc_exam%'";
    $result5 = mysqli_query($conn, $sql5);
    $row5 = mysqli_fetch_array($result5);
    $ab_id=$row5['id'] ;

    $sql6 = "SELECT * FROM subject_ssc where name like '%$ssc_group_name%'";
    $result6 = mysqli_query($conn, $sql6);
    $row6 = mysqli_fetch_array($result6);
    $abc_id=$row6['id'] ;



	?>

<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-2">
                    <div class="card mt-5">
                        <div class="card-header">
                            
                        </div>
                        <div class="card-body">
                            <form>
			<div class="form-group">
					 <select class="form-control" id="ssc_exam" name="ssc_exam" tabindex="6">
						<option value="<?php echo $ab_id?>" disabled selected><?php echo $ssc_exam?></option>
						<!-- <option value="" disabled selected>--Select--</option> -->
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
                            <select class="form-control" id="ssc_group_name" name="ssc_group_name" tabindex="7">
                             <option value="<?php echo $abc_id?>" disabled selected><?php echo $ssc_group_name?></option>
                              
                            </select>
							
	
							
                        </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"  crossorigin="anonymous"></script>
        <script>
            $(document).ready(function() {
                $('#ssc_exam').on('change', function() {
                    var category_id = this.value;
                    $.ajax({
                        url: "ajaxpro_for_ssc_exam_1.php",
                        type: "POST",
                        data: {
                            category_id: category_id
                        },
                        cache: false,
                        success: function(result) {
                            $("#ssc_group_name").html(result);
                        }
                    });
                });
            });
        </script>
    </body>
</html>
















































			