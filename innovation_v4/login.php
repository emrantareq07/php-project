<?php
session_start();
require_once("config/config.php");
require_once("db/db.php");
require_once(ROOT_PATH.'/libs/function.php');
$usercredentials=new DB_con();

//fetching username from either session or cookies condition
if(isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
  $uname = $uun = $uup = "";
  if (isset($_SESSION["uname"])) {
    $uname  = $_SESSION['uname'];
  }
  if (isset($_COOKIE['user_login'])) {
    $uname  = $_COOKIE['user_login'];
  }

  $query="SELECT*FROM tblusers  WHERE Username='$uname'";
      $result= $usercredentials->runBaseQuery($query);

      foreach ($result as $k => $v) {
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
      }
}
// login function start
if(isset($_POST['signin'])){
// Posted Values
$uname=$_POST['username'];
$pasword=md5($_POST['password']);
//Function Calling
$ret=$usercredentials->signin($uname,$pasword);
$num=mysqli_fetch_array($ret);
if($num>0){
  $_SESSION['uid']=$num['id'];
  $_SESSION['uname']=$uname;

      if(!empty($_POST["remember"])) {
        setcookie ("user_login",$_POST["username"],time()+ (10 * 365 * 24 * 60 * 60), "/");
        setcookie ("userpassword",$_POST["password"],time()+ (10 * 365 * 24 * 60 * 60), "/");

      } else {
        if(isset($_COOKIE["user_login"])) {
          setcookie ("user_login","");
        }

        if(isset($_COOKIE["userpassword"])) {
          setcookie ("userpassword","");
          setcookie ("userpassword","");
        }

      }
// For success
echo "<script>window.location.href='dashboard.php'</script>";
}
else{
// Message for unsuccessfull login
echo "<script>alert('Invalid details. Please try again');</script>";
echo "<script>window.location.href='login.php'</script>";
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
     <!-- font awesome  -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/> 
    <title>BCIC Innovation Database</title>
  <link rel="icon" type="image/gif/png" href="images/bcic_logo.png"> 
  </head>
  <body>
    <!-- nnn -->
<div class="container ">  
  <div class="row d-flex justify-content-center align-items-center rounded" style="height: 90vh;">
    <div class="col-sm-4 p-4  border border-3 shadow-lg rounded border-primary bg-white">
      <form action="" method="post" id="login" autocomplete="off">
        <div class="">
          <center><img src="images/bcic_logo.png" alt="BCIC" class="avatar shadow-lg " width="70"></center>          
          <h4 class="title my-3 text-center text-muted text-uppercase">BCIC Innovation Database</h4>
          <h4 class="title my-3 text-center text-primary fw-bold text-uppercase"><b>Login</b></h4>
          <div class="col-12">
            <div class="input-group mb-3">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
              </div>
              <input name="username" type="text" value="<?php if(isset($_COOKIE["user_login"])) { echo $_COOKIE["user_login"]; } ?>" required class="input form-control" id="username" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
            </div>
          </div>

          <div class="col-12">
            <div class="input-group mb-3 w-100">
              <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1"><i class="fas fa-lock"></i></span>
              </div>
              <input name="password" type="password" value="" class="input form-control" id="password" placeholder="password" required="true" aria-label="password" aria-describedby="basic-addon1">
            </div>
          </div>

          <div class="col-6">
            <div class="form-group form-check text-left">
                <input type="checkbox" name="remember" <?php if(isset($_COOKIE["user_login"])) { ?> checked <?php } ?>  class="form-check-input" 
                id="remember_me">
                <label class="form-check-label" for="remember_me">Remember me</label>
            </div>

          </div>
          <div class="col-sm-12 pt-3 text-right">
               <p>Already registered <a href="register.php">Register</a></p>
          </div>

          <div class="col-12">
            <button class="btn btn-primary btn-block mx-auto" type="submit"  name="signin"> <i class="fa fa-sign-in"></i> Login</button>
          </div>

            <!-- <div class="col-12 pt-4">
              <em>
                <p><b>use bellwo user name and password for login:</b></p>
                <p>user: admin</p>
                <p>user: 123</p>
              </em>
            </div> -->

        </div> 
      </form>
        
    </div>
  </div>
</div> 
</body>
</html>

