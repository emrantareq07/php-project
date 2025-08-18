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
include_once"includes/header_user_manage.php";
?>  
    <div class="container mt-4">
        <?php //include('message.php'); ?>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card shadow border border-success border-2">
                    <div class="card-header">
                        <h4 class="text-uppercase"> Edit 
                            <a href="manage_user.php" class="btn btn-danger float-end"><i class="fa fa-arrow-left" style="font-size:16px"></i> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if(isset($_GET['id'])){
                            $id = mysqli_real_escape_string($conn, $_GET['id']);
                            $query = "SELECT * FROM tblusers WHERE id='$id'";
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0){
                                //while($student=mysqli_fetch_array($query_run)){
                                $student = mysqli_fetch_array($query_run);
                                    $role=$student['role'];

                                ?>
                        <form action="manage_user-code.php" method="POST">
                        <input type="hidden" name="id" value="<?= $student['id']; ?>">

                        <div class="mb-3">
                            <label for="username">User Name</label>
                            <input type="text" name="username" id="username" value="<?=$student['Username'];?>" class="form-control">                            
                        </div>    
                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input type="text" name="password" id="password" value="<?=$student['Password'];?>" class="form-control">
                        </div>

                        <script type="text/javascript">
                            var passwordInput = document.getElementById("password");

                            function genPassword(event) {
                                event.preventDefault(); // Prevent the default form submission behavior

                                var chars = "0123456789abcdefghijklmnopqrstuvwxyz!@#$%^&*()ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                                var passwordLength = 6;
                                var newPassword = "";
                                for (var i = 0; i < passwordLength; i++) {
                                    var randomNumber = Math.floor(Math.random() * chars.length);
                                    newPassword += chars.substring(randomNumber, randomNumber + 1);
                                }
                                passwordInput.value = newPassword;
                            }

                            function copyPassword(event) {
                                event.preventDefault(); // Prevent the default form submission behavior

                                passwordInput.select();
                                passwordInput.setSelectionRange(0, 999);
                                document.execCommand("copy");                           

                            }
                        </script>               
				
				 <div class="form-group mb-3">
                    <label>Role</label>
                    <select class="form-control" id="role" name="role" >
                      <?php 
                      if($role=='sadmin'){
                        ?>
                        <option value="sadmin" readonly <?=$student['role'] == 'sadmin' ? ' selected="selected"' : '';?>>SAdmin</option>

                        <?php  
                      }else{
                        ?>
                        <option value="user" <?=$student['role'] == 'user' ? ' selected="selected"' : '';?>>User</option>  
                    <option value="admin" <?=$student['role'] == 'admin' ? ' selected="selected"' : '';?>>Admin</option> 
                        <?php
                      }
                      ?>             
                    </select>                 
                </div>				
                <div class="form-group mb-3">
                    <button type="submit" name="update_student" class="btn btn-primary">
                        <i class="fa fa-save" style="font-size:16px"></i> Update
                    </button>
                </div>

            </form>
            <?php }    //}
        else {
            echo "<h4>No Such Id Found</h4>";
        }
    }
    ?>
                    </div>
                </div>
            </div> 
            <div class="col-md-3"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
}else{

   
}

?>