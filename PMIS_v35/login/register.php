<?php 
include('includes/header.php');
include('db.php');
include('../controller/function.php');

// Fetch all the country data 
 
if (isset($_POST['register'])) {
	$employee_type = $_POST['employee_type'];
	$emp_id = $_POST['emp_id'];
    $name1 = mysqli_real_escape_string($conn, $_POST['name']);
    $name=strtoupper($name1);
    $designation = $_POST['designation'];
    $division = $_POST['division'];
    $sub_cadre_header = $_POST['sub_cadre_header'];
    $mobile_no = $_POST['mobile_no'];
	$email = $_POST['email'];
    $place_of_posting = $_POST['place_of_posting'];
    $password = mysqli_real_escape_string($conn, $_POST['psw']);
    $password2 = md5($password);

    //$dp = "image/" . $_FILES['image']['name'];

    $sql = "SELECT * FROM users WHERE emp_id='$emp_id'";
    $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) > 0) {
    echo '<script type="text/javascript">';
    echo 'alert("EMP ID Already taken!");';
    echo 'window.location.href="register.php"';
    echo '</script>';
  } else {
    $role = 'user';
    // Check if the registering user is an admin
    if ($emp_id === '5620-0') {
      $role = 'sadmin';
      $status = 'approved'; // Set the status to 'approved' for admin
      echo '<script type="text/javascript">';
      echo 'alert("Super Admin created successfully!");';
      echo 'window.location.href="index.php"';
      echo '</script>';
    } else {
      $status = 'pending'; // Set the status to 'pending' for regular users
      echo '<script type="text/javascript">';
      echo 'alert("Your Account is now pending for Approval!");';
      echo 'window.location.href="index.php"';
      echo '</script>';
    }
// $dp = "C:/xampp/htdocs/PMIS_v30/images/".$_FILES['image']["name"];
 // $dp = "../images/".$_FILES['image']["name"];
 $image = '';
  if($_FILES["user_image"]["name"] != '')  {
  $image = upload_image();
  } 
$job_confirm_status='approved';
 //if(move_uploaded_file($_FILES['image']["tmp_name"],$dp)) {
	$sql = "INSERT INTO users(employee_type,emp_id, name, designation,sub_cadre_header, division, place_of_posting,mobile_no,email,password,status, role, job_confirm_status,image) VALUES('{$employee_type}','{$emp_id}', '{$name}', '{$designation}','{$sub_cadre_header}','{$division}', '{$place_of_posting}', '{$mobile_no}','{$email}','{$password2}', '{$status}','{$role}','{$job_confirm_status}','{$image}')";
    $result = mysqli_query($conn, $sql);
	//}
  }
}

?>
    <div class="container">
    	<h2 class="page-header text-center mt-2 text-uppercase text-muted"><b class=" bg-muted">Welcome BCIC Personnel Management Information System(PMIS) </b></h2>
    	<div class="row">
		<div class="col-sm-3 "></div>
    		<div class="col-sm-6 ">
    			<div class="login-panel panel panel-primary my-0 mt-0 pt-0 mb-0 shadow-lg">
    		        <div class="panel-heading">
    		            <h3 class="panel-title"><span class="glyphicon glyphicon-user"></span>  Registration System
    		            </h3>
    		        </div>
    		    	<div class="panel-body">
    		        	<form method="POST" action="register.php" id="regForm" enctype="multipart/form-data">
    		            	<!--<div id="error_msg"></div>-->
							<fieldset>
							<!-- 	<div class="form-group">
    		                    	<input class="form-control"  pattern="^\d{4}$" placeholder="Enter Employee ID: 1234 Format!!!" type="text" id="emp_id_create" name="emp_id_create" 
									data-toggle="tooltip" data-placement="right" required title="Plz Enter Employee ID: 1234 Format!!!" >
    		                	
								</div> -->

								<div class="form-group" >
		                        <!-- <label for="place_of_posting">Place of Posting:</label> -->
		                        <select class="form-control" id="employee_type" name="employee_type" data-toggle="tooltip" data-placement="right" title="Select Employee Type" required>
		                            <option value="" disabled selected>Employee Type</option>
		                           <option value="Officer">Officer</option>
									<option value="Staff">Staff</option>
									 </select>
		                           </div>
							
								<div class="form-group" >
    		                    	<input class="form-control" onkeyup="checkname();" pattern="^\d{4}-\d{1}$" placeholder="Enter Employee ID: 1234-56 Format!!!" type="text" id="emp_id" name="emp_id" 
									data-toggle="tooltip" data-placement="right" required title="Plz Enter Employee ID: 1234-56 Format!!!" >
    		                	<span id="empID_status" class="text-danger"></span>
								<span></span>
								</div>
								<script type="text/javascript">
								
								//emp id check if already exists
									
									$(":input").attr('autocomplete', 'off');
									function checkname(){
										
									 var emp_id=document.getElementById( "emp_id" ).value;
										
									 if(emp_id) {
									  $.ajax({
									  type: 'post',
									  url: 'checkEmp_id.php',
									  data: {
									   emp_id:emp_id,
									  },
									  success: function (response) {
									   $( '#empID_status' ).html(response);
									  
									   if(response=="OK") {
										   $('#emp_id').focus();
										return true;	
										   }
									   else	{
										   $('#emp_id').focus();
										return false;
										  }
									  }
									  });
									 }
									 else {
									  $( '#empID_status' ).html("");
									  $('#emp_id').focus();
									  return false;
									  
									 }
									}
									function checkall(){
										 var namehtml=document.getElementById("empID_status").innerHTML;
										 //var emailhtml=document.getElementById("email_status").innerHTML;

										 if(namehtml=="OK"){
										  return true;
										//location.href="welcome.php";
										  //return location.href = 'welcome.php';
										 }
										 else{
										  return false;
										 }
										}


									</script>
					<div class="form-group">
				    <input class="form-control" placeholder="Enter Full Name" type="text" name="name" data-toggle="tooltip" data-placement="right" title="Enter Your Full Name" required oninput="this.value = this.value.toUpperCase()">
				</div>

							
    		                
					<div class="form-group" style="display: inline-block; width: 49%">
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
    		           <div class="form-group" style="display: inline-block; width: 50%">
    		         <select class="form-control" id="sub_cadre_header"  name="sub_cadre_header" >
						<option value="" disabled selected >Designation Ext. (Optional)</option>
						<?php 
						$sql = "SELECT header FROM sub_cadre_header order by header ASC";
						 
						$result=mysqli_query($conn,$sql);
						 //while($row=mysql_fetch_object($result)){
						while($row=mysqli_fetch_array($result)){
							$header=$row['header'];
							echo "<option value='$header'>$header</option>";
						}
						?>
						
					  </select>
								  
    		           </div>						
								
						<div class="form-group" style="display: inline-block; width: 49%">
	                    	<select class="form-control" id="division" name="division" required>
							<option value="" disabled selected>Select Division</option>
							<?php 
							$sql="SELECT division FROM division ";
							 
							$result=mysqli_query($conn,$sql);
							 //while($row=mysql_fetch_object($result)){
							while($row=mysqli_fetch_array($result)){
								$division=$row['division'];
								echo "<option value='$division'>$division</option>";
							}
							?>
							
						  </select>
						 
	                	</div>									

    		                	<div class="form-group" style="display: inline-block; width: 50%">
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
                            <div class="form-group" style="display: inline-block; width: 49%">
    		                 <input class="form-control" placeholder="Enter Mobile No" type="text" name="mobile_no" data-toggle="tooltip" data-placement="right" title="Enter Your Mobile No" >
    		                </div>	
    		                <div class="form-group" style="display: inline-block; width: 50%">
    		                 <input class="form-control" placeholder="Enter Email ID" type="email" name="email" data-toggle="tooltip" data-placement="right" title="Enter Your Email ID" >
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
    		                    	<!-- <input class="form-control" placeholder="Upload Image:" type="file" name="image" > -->
    		                    	<input type="file" name="user_image" id="user_image" accept=".jpg, .jpeg,.png,.gif"/>
    		                	</div>
    		                	<button type="submit" name="register" class="btn btn-lg btn-primary btn-block"><span class="glyphicon glyphicon-log-in"></span> Submit</button>
    		            	<div id="result"> </div>
							</fieldset><center> <h5>Already Registration?<a href="index.php"> Login Here </a></h5></center>
    		        	</form>
						
    		    	</div>
					
    		    </div>
    			
    		</div>
			<div class="col-sm-3 ">
			<!--for Password Validation-->
			<div id="message">
		  <h4 class="text-center text-info">Password must contain the following:</h4>
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

$(function () {
    $('[data-toggle=\"tooltip\"]').tooltip()
})	

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
