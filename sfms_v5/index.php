<?php
// --------------------------
session_start();
include('db/db.php');

if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password = sha1($password);

    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $username1 = $row['username'];
        $password1 = $row['password'];
        $_SESSION['user_type'] = $row['user_type']; // Set user_type here
        $_SESSION['office_type'] = $row['office_type'];
        
         if($username1==$username && $password1==$password){
        
        $_SESSION['username'] = $_POST['username'];
        // $_SESSION=$row['user_type'];
        //$_SESSION['usernametable'] = $username1 ;
         

         // if($_SESSION['user_type']=='admin'){
         //     header("location:admin_dashboard.php");
         //    // header("location:controller/urea_form.php");
         // }
         // elseif($_SESSION['user_type']=='sadmin'){
         //    header("location:controller/sadmin_dashboard.php");
         // }
         // else{
         //    header("location:controller/urea_form.php");
         // }
        if (($_SESSION['user_type'] == 'user') && 
            ($row['office_type'] == 'port_office' || 
             $row['office_type'] == 'bcic_hq' || 
             $row['office_type'] == 'factory_office')) {
            header("Location: controller/user_dashboard.php");
            exit(); // It's a good practice to include exit() after a header redirection.
        }

        elseif(($_SESSION['user_type']=='user')){
            header("location:controller/urea_form.php");
         }
        
         else{
            header("location:controller/dashboard.php");
         }

        }
    } 

    else {
        //$msg = "Username and Password is Incorrect";
    echo '<html><head>';
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '</head><body>';
    echo '<script type="text/javascript">
            Swal.fire({
                title: "Username and Password is Incorrect",                
                icon: "warning",
                confirmButtonColor: "#dc3545"
            }).then(function() {
                window.location.href = "dashboard.php";
            });
          </script>';
    echo '</body></html>';
    }
}
?>

<?php include_once"include/header.php";?>
<div class="container-fluid mt-5">
  <div class="row">
  <div class="col-sm-4"></div>
  <div class="col-sm-4">
  
  <div class="card shadow-lg border border-3 border-primary">
  <div class="card-header text-center text-uppercase text-white" style="background-color: #751aff;"><b>BCIC Fertilizer Management System</b></div>
  <div class="card-body">
  <div class="imgcontainer">      
      <img src="images/bcic_logo.png" alt="BCIC" class="avatar shadow-lg">
    </div>
  <form action="<?=$_SERVER['PHP_SELF']; ?>" method="POST" class="">
   <!--  <div class="form-floating mb-3">
      <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div> -->
    <!-- <div class="mb-3 mt-3">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
    </div> -->
    <div class="form-floating mb-3 mt-3">      
      <input type="text" class="form-control" id="floatingInput" placeholder="Enter Username" name="username" required="">
      <label for="floatingInput">Username</label>
    </div>
    <div class="form-floating mb-3">      
      <input type="password" class="form-control" id="floatingPassword" placeholder="Enter password" name="password" required="">
      <label for="floatingPassword">Password</label>
    </div>
    <div class="form-check mb-3">
      <label class="form-check-label">
        <input class="form-check-input" type="checkbox" name="remember"> Remember me
      </label>
    </div>
    <button type="submit" class="btn btn-primary gap-2 col-12 mx-auto" name="login"><i class="fa fa-sign-in" ></i> Sign In</button>
    <!-- <button type="submit" class="btn btn-primary d-grid gap-2 col-12 mx-auto" name="login" style="font-size:24px">Sign In <i class="fa fa-sign-out"></i></button> -->
  </form>
  </div>
  <div class="card-footer text-center text-muted">Design & Developed by ICT Division, BCIC.</div>
</div>
  
  
  </div>
  <div class="col-sm-4"></div>
  
</div>
</div>

<?php
include('include/footer.php');
?>