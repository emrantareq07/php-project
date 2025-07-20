<?php
session_start();
include('includes/header.php');
?>

<div class="container">
    <h1 class="page-header text-center text-uppercase text-muted">
        <b class=" bg-muted">Welcome BCIC PMIS Dashboard</b>
    </h1>
    <div class="row">
        <div class="col-sm-4 ">
            
        <?php
          if(@$_GET['submitted'])
          {?>
          <div class="alert alert-danger" role="alert">
            Incorrect Password.
          </div>
          <?php }?>
        </div>
        <div class="col-sm-4 ">

            <?php
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']); // Clear the error message from the session
            }
            ?>

            <div class="login-panel panel panel-primary shadow-lg">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="glyphicon glyphicon-lock"></span> Login</h3>
                </div>
                <div class="panel-body">
                    <form method="POST" action="login.php" >
                      <fieldset>
                          <div class="form-group">
                              <input class="form-control" placeholder="Employee ID" type="text" id="emp_id" name="emp_id" onkeyup="checkemp_id();" required lang="en">
                          <!-- <input class="form-control" placeholder="Employee ID" type="text" id="emp_id" name="emp_id" onkeyup="checkemp_id();" pattern="[a-zA-Z]*" title="Please enter only English letters" required lang="en"> -->

                          <span id="emp_id_status" class="text-danger"></span>
                          </div>
                          <div class="form-group">
                              <input class="form-control" placeholder="Password" type="password" id="password" name="password" onkeyup="checkpwd();" required>
                          <span id="pwd_status" class="text-danger"></span>
                          </div>
                          <button type="submit" id="login"  name="login" class="btn btn-lg btn-primary btn-block"><span class="glyphicon glyphicon-log-in"></span> Login</button>
                      
                      <div id="result"> </div>
                      </fieldset>
							<center> <!--<h5>Don't have an account?<a href="register.php"> Register Here </a></h5>-->
							<h5>Already Employee?<a href="register.php"> Plz Register Here </a></h5>
							New Employee & Don't have Employee ID?<a href="new_employee_for_emp_id_register.php"> Plz Register Here </a>
							</center>
                        </form>
                        
                    </div>
                    
                </div>
                
            </div>
        </div>
    </div>
    
    <script type="text/javascript">
    //focus text fieldse
    $("input:text").focus();
   var emp_id; // Declare emp_id globally
 function checkemp_id(){
      emp_id = document.getElementById("emp_id").value;
        if(emp_id){
            $.ajax({
                type: 'post',
                url: 'checkdata.php',
                data: {
                    emp_id: emp_id,
                },
                success: function (response) {
                    $('#emp_id_status').html(response);
                }
            });
        } else {
            $('#emp_id_status').html("");
        }
    }

    function checkpwd(){
        var password = document.getElementById("password").value;
        
        if(password && emp_id){
            $.ajax({
                type: 'post',
                url: 'checkdata.php',
                data: {
                    password: password,
                    emp_id: emp_id
                },
                success: function (response) {
                    $('#pwd_status').html(response);
                }
            });
        } else {
            $('#pwd_status').html("");
        }
    }

    // function checkall(){
    //     var emp_id_status = $('#emp_id_status').text().trim();
    //     var pwd_status = $('#pwd_status').text().trim();

    //     if(emp_id_status === "OK" && pwd_status === "OK"){
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
    function checkall(){
 var emp_idhtml=document.getElementById("emp_id_status").innerHTML;
 var pwdhtml=document.getElementById("pwd_status").innerHTML;

 if((emp_idhtml && pwdhtml)=="OK")
 {
  return true;
//location.href="welcome.php";
  // return location.href = 'welcome.php';
 }
 else
 {
  return false;
 }
}
</script>
<?php include('includes/footer.php');?>