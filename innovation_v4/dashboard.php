<?php
session_start();  
require_once("config/config.php");
require_once("db/db.php");
if(isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
  include_once(ROOT_PATH.'/libs/function.php');
  $usercredentials=new DB_con();

  //fetching username from either session or cookies condition
  $uname = $uun = $uup = "";
  if (isset($_SESSION["uname"])) {
    $uname  = $_SESSION['uname'];
  }
  if (isset($_COOKIE['user_login'])) {
    $uname  = $_COOKIE['user_login'];
  } 

  $query="SELECT*FROM tblusers  WHERE Username='$uname'";
      $result= $usercredentials->runBaseQuery($query);
      foreach ($result as $k => $v){
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
      }
//session  condition  end  but it follows until bottom of the page
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
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/static/css/login_form_style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
	 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <link href="assests/style.css" rel="stylesheet"> -->
    <title>Signin Admin</title>
  </head>
  <body>
    <!-- nnn -->
<div class="container-fluid bg-light ">
<div class="container pt-4 mt-3 bg-white shadow border rounded border-success">
  <div class="row" style="min-height: 90vh;">
    <!-- <div class="col-sm-12 p-4  bg-white shadow border">-->
		<div class="col-sm-3"> </div>
		<div class="col-sm-6"> 
		<h1>Hello, <strong><?php echo $uun;?>!</strong></h1>
      <h2>Welcome to admnin dashboard</h2>

      <p><a href="add_new_innovation.php" class="btn btn-success float-end">
       <span class="glyphicon glyphicon-plus"></span>  Innovation Idea Details
      </a></p>
      <p><a href="add_new_innovation.php" class="btn btn-success float-end">
       <span class="glyphicon glyphicon-plus"></span>  fiscal year List
      </a></p>
      <p><a href="add_designation.php" class="btn btn-success float-end">
       <span class="glyphicon glyphicon-plus"></span>  Designation List
      </a></p>
		</div>
		<div class="col-sm-3">  <a href="logout.php" class="btn btn-danger float-right"><i class="fa fa-sign-out"></i>
        Logout
      </a></div>  
   <!-- </div>-->
  </div>
</div>
</div>   
   
<!-- should be in bottom -->
<script type="text/javascript" src="<?php echo base_url();?>/static/js/login_form_custom.js"></script>

<script type="text/javascript">
</script>   

</body>
</html>

<?php } else{
   //header('location:login/logout.php');
   header('location:login.php');
  } 
?>

