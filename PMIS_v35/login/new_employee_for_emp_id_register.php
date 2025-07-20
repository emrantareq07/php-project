<?php include('includes/header.php');
 include('db.php');

if (isset($_POST['register'])) {

$employee_type = $_POST['employee_type'];
$name = mysqli_real_escape_string($conn, $_POST['name']);
$designation = $_POST['designation'];
// $division = $_POST['department'];
// $section = $_POST['section'];
$place_of_posting = $_POST['place_of_posting'];
$password = mysqli_real_escape_string($conn, $_POST['psw']);
$password2 = md5($password);
$mobile_no = $_POST['mobile_no'];
$email = $_POST['email'];
$nid = $_POST['nid'];


$role = 'user';
  
$status = 'pending'; // Set the status to 'pending' for regular users
echo '<script type="text/javascript">';
echo 'alert("Your Account is now pending for Approval!");';
echo 'window.location.href="index.php"';
echo '</script>';
// }

$dp = "image/".$_FILES['image']["name"];

$sql = "INSERT INTO users(employee_type,name, designation, place_of_posting,password,status, role, image,mobile_no,email,nid) VALUES('{$employee_type}','{$name}', '{$designation}', '{$place_of_posting}', '{$password2}', '{$status}','{$role}','{$dp}', '{$mobile_no}','{$email}','{$nid}')";
$result = mysqli_query($conn, $sql);

$file = $_FILES['image']['tmp_name'];
$file1 = $_FILES['image']['name'];

list($width,$height)=getimagesize($file);
	$nwidth=$width/4;
	$nheight=$height/4;
	$newimage=imagecreatetruecolor($nwidth,$nheight);
	if($_FILES['image']['type']=='image/jpeg'){
		$source=imagecreatefromjpeg($file);
		imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height);
	
		imagejpeg($newimage,'image/'.$file1);
	}elseif($_FILES['image']['type']=='image/png'){
		$source=imagecreatefrompng($file);
		imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height);

		imagepng($newimage,'image/'.$file1);
	}elseif($_FILES['image']['type']=='image/gif'){
		$source=imagecreatefromgif($file);
		imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height);
	
		imagegif($newimage,'image/'.$file1);
	}

	//else{
	// 			$source=imagecreatefromgif($file);
	// 	imagecopyresized($newimage,$source,0,0,0,0,$nwidth,$nheight,$width,$height);
	
	// 	imagegif($newimage,'image/'.$file1);
	// }
//strpos($_FILES['image']['type'], 'image/') === 0)


  }
// }
?>

    <div class="container ">
    	<!-- <h3 class="page-header text-center mt-2 text-uppercase text-muted"><b class=" bg-muted">BCIC PMIS Registration</b></h3> -->
    	<div class="row">
		<div class="col-sm-4 "></div>
    		<div class="col-sm-4 ">
    			<div class="login-panel panel panel-primary my-1 mt-0 pt-0 shadow-lg">
    		        <div class="panel-heading">
    		            <h3 class="panel-title"><span class="glyphicon glyphicon-user"></span> BCIC PMIS Registration
    		            </h3>
    		        </div>
    		    	<div class="panel-body">
    		        	<form method="POST" action="new_employee_for_emp_id_register.php" id="regForm" enctype="multipart/form-data">
    		            	<!--<div id="error_msg"></div>-->
							<fieldset>

						<div class="form-group">
                        <!-- <label for="place_of_posting">Place of Posting:</label> -->
                        <select class="form-control" id="employee_type" name="employee_type" data-toggle="tooltip" data-placement="right" title="Select Employee Type" required>
                            <option value="" disabled selected>Employee Type</option>
                           <option value="Officer">Officer</option>
							<option value="Staff">Staff</option>
							 </select>
                           </div>
							
						<div class="form-group">
		                 <input class="form-control" placeholder="Enter Full Name" type="text" name="name" data-toggle="tooltip" data-placement="right" title="Enter Your Full Name" required>
		                </div>
							
    		                
						<div class="form-group">
		                    	<select class="form-control" id="designation"  name="designation" required>
								<option value="" disabled selected >Select Designation</option>
								<?php 
								$sql="SELECT designation FROM designation order by designation asc";
								 
								$result=mysqli_query($conn,$sql);
								 //while($row=mysql_fetch_object($result)){
								while($row=mysqli_fetch_array($result)){
									$designation=$row['designation'];
									echo "<option value='$designation'>$designation</option>";
								}
								?>
								
							  </select>
							  
		                	</div>	

							<!-- <div class="form-group">    		                	                    
						        <select class="form-control" id="sub_cadre_header" name="sub_cadre_header" value="">
						          <option value="Select"  selected>Select Designation Ext.</option>
 -->
						        <!-- <option value="" disabled selected>নির্বাচন করুন</option> -->
						         <?php
						            // $sql = "SELECT header FROM sub_cadre_header order by header ASC";
						            // $result = mysqli_query($conn, $sql);
						            // while($row = mysqli_fetch_array($result)) {
						            //  echo "<option value='".$row['header']."'>".$row['header']."</option>";
						            //}

						            ?>                
						        <!-- 
						        </select>	
						        </div>	 -->			
								
								<!-- <div class="form-group">
    		                    	<select class="form-control" id="department" name="department" required>
									<option value="" disabled selected>Select Division</option> -->
									<?php 
									// $sql="SELECT name FROM department";
									 
									// $result=mysqli_query($conn,$sql);
									//  //while($row=mysql_fetch_object($result)){
									// while($row=mysqli_fetch_array($result)){
									// 	$dept_name=$row['name'];
									// 	echo "<option value='$dept_name'>$dept_name</option>";
									// }
									?>
									
								  <!-- </select>
								 
    		                	</div> -->			
    		                	<!-- <div class="form-group">
    		                 <input class="form-control" placeholder="Enter DOB" type="date" name="dob" data-toggle="tooltip" data-placement="right" title="Enter Your DOB" required>
    		                </div>	 -->					
							
								<div class="form-group">
                                <!-- <label for="place_of_posting">Place of Posting:</label> -->
                                <select class="form-control" id="place_of_posting" name="place_of_posting" required>
                                    <option value="" disabled selected>Select Place of Posting</option>
                                   		
									<?php
									$sql = "SELECT * FROM place_of_posting";
									$result = mysqli_query($conn, $sql);
									while($row = mysqli_fetch_array($result))
									{
									 echo "<option value='".$row['place_of_posting']."'>".$row['place_of_posting']."</option>";
									}?>
                                </select>
                            </div>
							<div class="form-group">
    		                 <input class="form-control" placeholder="Enter Mobile No" type="text" name="mobile_no" data-toggle="tooltip" data-placement="right" title="Enter Your Mobile No" required>
    		                </div>	
    		                <div class="form-group">
    		                 <input class="form-control" placeholder="Enter Email ID" type="email" name="email" data-toggle="tooltip" data-placement="right" title="Enter Your Email ID" required>
    		                </div>
    		                 <div class="form-group">
    		                 <input class="form-control" placeholder="Enter NID" maxlength="13" type="number" name="nid" data-toggle="tooltip" data-placement="right" title="Enter Your NID" required>
    		                </div>
								
    		                	<div class="form-group">
								    <input class="form-control" type="password" placeholder="Password" id="psw" name="psw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
								    title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
								</div>
								<div class="form-group">
								    <input class="form-control" placeholder="Re-Type Password" type="password" id="conf_password" name="conf_password" onkeyup="isPasswordMatch();" required>
								    <span id="passwordMatchMessage" class="text-danger"></span>
								    <span id="checkPassword" class="text-success"></span>
								</div>
								<style>
								    .success-message {
								        color: green; /* You can change this color to your preferred color */
								    }
								</style>
								<script>
								    function isPasswordMatch() {
								        var password = $("#psw").val();
								        var confirmPassword = $("#conf_password").val();
								        var passwordMatchMessage = $("#passwordMatchMessage");
								        var checkPassword = $("#checkPassword");

								        if (password !== confirmPassword) {
								            passwordMatchMessage.html("Passwords do not match!");
								            checkPassword.removeClass("success-message"); // Remove the success message class
								            checkPassword.html("");
								        } else {
								            passwordMatchMessage.html(""); // Clear the error message
								            checkPassword.addClass("success-message"); // Add the success message class
								            checkPassword.html("Passwords match.");
								        }
								    }
								</script>

								<div class="form-group">
    		                    	<!-- <input class="form-control" placeholder="Upload Image:" type="file" name="image" required> -->

    		           <!-- <input class="form-control" placeholder="Upload Image:" type="file" name="image" accept="image/*" required> -->
         
    <input class="form-control" placeholder="Upload Image:" type="file" name="image" id="imageInput" accept=".jpg, .jpeg, .png,.gif" required>


           	</div>
    		                	<button type="submit" name="register" class="btn btn-lg btn-primary btn-block"><span class="glyphicon glyphicon-log-in"></span> Submit</button>
    		            	<div id="result"> </div>
							</fieldset>
							<center> <h5>Already Registration?<a href="index.php"> Login Here </a></h5>
							</center>
    		        	</form>
						
    		    	</div>
					
    		    </div>
    			
    		</div>
			<div class="col-sm-4 ">
			<!--for Password Validation-->
			<div id="message">
		  <h3 class="text-center text-info">Password must contain the following:</h3>
		  <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
		  <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
		  <p id="number" class="invalid">A <b>number</b></p>
		  <p id="length" class="invalid">Minimum <b>8 characters</b></p>
		</div>
			</div>
    	</div>
    </div>

	
<!--for tooltip-->
	<script>
	//text field focus
	$("#regForm #emp_id").focus();
	
	// var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
// var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  // return new bootstrap.Tooltip(tooltipTriggerEl)
// })
$(function () {
    $('[data-toggle=\"tooltip\"]').tooltip()
})
	
// $(document).ready(function(){
// $('[data-toggle="tooltip"]').tooltip();   
// });

// $(document).ready(function() {
    // $("body").tooltip({ selector: '[data-toggle=tooltip]' });
// });
// for popover

// $(document).ready(function(){
  // $('[data-toggle="popover"]').popover();   
// });
</script>
	
				
<script>
var myInput = document.getElementById("psw");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

// When the user clicks on the password field, show the message box
myInput.onfocus = function() {
  document.getElementById("message").style.display = "block";
}

// When the user clicks outside of the password field, hide the message box
myInput.onblur = function() {
  document.getElementById("message").style.display = "none";
}

// When the user starts to type something inside the password field
myInput.onkeyup = function() {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) {  
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }
  
  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {  
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }
  
  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
</script>

	

<!--<script src="jQuery v3.6.0.js"></script>
<script src="script.js"></script>-->
<?php include('includes/footer.php');?>
