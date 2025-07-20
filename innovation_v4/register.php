<?php
require_once("config/config.php");
require_once("db/db.php");
require_once(ROOT_PATH.'/libs/function.php');
// Object creation
$userdata=new DB_con();
if(isset($_POST['submit']))
{
// Posted Values
$fname=$_POST['fullname'];
$uname=$_POST['username'];
$uemail=$_POST['email'];
$pasword=md5($_POST['password']);
//Function Calling
$sql=$userdata->registration($fname,$uname,$uemail,$pasword);
  if($sql)
  {
  // Message for successfull insertion
  echo "<script>alert('Registration successfull.');</script>";
  echo "<script>window.location.href='login.php'</script>";
  }
  else
  {
    // Message for unsuccessfull insertion
    echo "<script>alert('Something went wrong. Please try again');</script>";
    echo "<script>window.location.href='register.php'</script>";
  }
}
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <!-- <link href="assests/style.css" rel="stylesheet"> -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
   <title>Innovation Database</title>
  
  <style>

  </style>

  <script>


function check_uname(){
 var uname=document.getElementById( "username" ).value;  
 if(uname) {
    $.ajax({
    type: 'post',
    url: 'libs/checkdata.php',
    data: {
     username:uname,
    },
    success: function (response) {
     $( '#name_status' ).html(response);
     if(response=="Available")      {
      $(".form-group span").addClass("text-success");
      return true;  
     }
     else{
      return false; 
     }
    }
    });
 }

 else {
  $('#name_status').html("");
  return false;
 }
}

function checkemail(){
 var uemail=document.getElementById( "email" ).value;
  
 if(email) {
  $.ajax({
  type: 'post',
  url: 'libs/checkdata.php',
  data: {
   email:uemail,
  },
  success: function (response) {
   $( '#email_status' ).html(response);
   if(response=="Available")    {
    $(".form-group span").addClass("text-success");
    return true;  
   }
   else   {
    return false; 
   }
  }
  });
 }
 else {
  $( '#email_status' ).html("");
  return false;
 }
}

function checkall(){
 var namehtml=document.getElementById("name_status").innerHTML;
 var emailhtml=document.getElementById("email_status").innerHTML;

 if((namehtml && emailhtml)=="Available") {
  $(".form-group span").addClass("text-success");
  return true;
 }
 else {
  return false;
 }
}

</script>
  </head>
  <body>

    <div class="container p-4 ">
      <div class="row d-flex justify-content-center">
        <div class="col-sm-6 border p-4 bg-light shadow">
		
          <form action="" method="post" id="login" onsubmit="return checkall();">
          <h4 class="text-muted text-center text-uppercase"> Registration Innovation Database</h4>
			<hr class="bg-warning">
            <div class="form-group">
              <label for="fullname">Full Name</label>
              <input name="fullname" type="text"  class="form-control" 
              id="fullname" placeholder="" required="true">
            </div>

            <div class="form-group">
               <label for="username">User Name</label>
               <input type="text" id="username" name="username" onkeyup="check_uname()" class="form-control"  required="true">
               <span id="name_status"></span>
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" id="email" name="email" onkeyup="checkemail()" placeholder="" class="form-control" required="true">
              <span id="email_status"></span>
            </div>       

            <div class="form-group">
              <label for="password">Password</label>
               <input type="password"  id="password" name="password" placeholder="" class="form-control"  required="true">  
            </div>
           
            <div class="col-sm-12 text-right">
               <button class="btn btn-primary" type="submit" id="submit" name="submit">Register</button>
            </div>

            <div class="col-sm-12 text-right">
               <p>Already registered <a href="login.php">Login</a></p>
            </div>

            <!-- <div class="col-12 pt-4">
              <em>
                <p><b>bellwo user name and email is already available:</b></p>
                <p>user: admin</p>
                <p>user: bootstrapfriendly@gmail.com</p>
              </em>
            </div> -->

          </form>
        </div>
      </div>
    </div>
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    
   
  </body>
</html>
