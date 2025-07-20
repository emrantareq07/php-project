<?php 
include('includes/header.php');
include('db.php');
include('../controller/function.php');

session_start();
 
if (isset($_POST['register'])) {
 
    $emp_id = $_SESSION['emp_id']; 

    $password = mysqli_real_escape_string($conn, $_POST['psw']);
    $password = md5($password);  

  $statement = $connection->prepare(
        "UPDATE users 
        SET  password = :password 
        WHERE emp_id = :emp_id"
    );
    $result = $statement->execute(
        array(
      
      ':password' => $password,
            ':emp_id' => $emp_id
        )
    );

    echo '<script type="text/javascript">';
    echo 'alert("Password Updated Successfully!!!");';
 
    echo 'window.location.href="user_dashboard.php"';
    echo '</script>';
    }
?>

    <div class="container">
    	<h2 class="page-header text-center mt-2 text-uppercase text-muted"><b class=" bg-muted">Changed Password </b></h2>
    	<div class="row">
		<div class="col-sm-3 "></div>
    		<div class="col-sm-6 ">
    			<div class="login-panel panel panel-primary my-0 mt-0 pt-0 mb-0 shadow-lg">
           	<div class="panel-body">
    		        	<form method="POST" action="#"  enctype="multipart/form-data"> 					
               							
		              <div class="form-group">
								    <input class="form-control" type="password" placeholder="Password" id="psw" name="psw" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
								    title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required>
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
    		                	<button type="submit" name="register" class="btn btn-lg btn-primary btn-block"><span class="glyphicon glyphicon-log-in"></span> Submit</button> 		                	

    		         
    		        	</form>
						
    		    	</div>
					
    		    </div>
    			
    		</div>
			<div class="col-sm-3 "><a class="btn btn-primary" href="user_dashboard.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"><span class="glyphicon glyphicon-arrow-left"> Previous page </a>
			<!--for Password Validation-->
			<div id="message">
		  <h4 class="text-center text-info"> <b>Password must contain the following:</b></h4>
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
