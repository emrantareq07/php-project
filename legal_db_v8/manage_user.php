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
<div class="container-fluid mt-2">
    <?php //include('message.php'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow border border-success border-2">
                <div class="card-header">
                    <h4 class="text-center text-uppercase ">
                        <?php
                        // if ($role === 'admin') {
                        //     echo '<a href="admin_dashboard.php?role=' . $_SESSION["role"] . '" class="btn btn-primary float-start">Previous Page</a>';
                        // } else {
                        //     echo '<a href="super-admin_dashboard.php?role=' . $_SESSION["role"] . '" class="btn btn-primary float-start">Previous Page</a>';
                        // }
                        ?>
                        <b>User Management Details</b>
                        <span class="float-end">
                           
                            <div style="margin-top: 10px;">
                              <a href="super_admin-dashboard.php" class="btn btn-primary "> <i class="fa fa-arrow-left" style="font-size:16px"></i> BACK</a>
                                <a href="manage_user-create.php" class="btn btn-primary"> <i class="fa fa-plus" style="font-size:16px"></i> Add</a>
                                <a href="logout.php" class="btn btn-danger"><i class="fa fa-sign-out" style="font-size:16px"></i> Logout</a>
                            </div>
                        </span>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                              <tr>
                            <th>ID</th>
                            
                            <th>User Name</th>
                            <!-- <th>Designation</th> -->
                            <th>Password</th>
                            <!-- <th>Status</th> -->
                            <th>Role</th>
                            <th class='text-center'>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM tblusers";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $student) {
                                    ?>
                                    <!-- Display user data -->
                                  <tr>
                                    <td><?= $student['id']; ?></td>
                                    
                                    <td><?= $student['Username']; ?></td>
                                    <!-- <td><?= $student['designation']; ?></td> -->
                                    <td><?= $student['Password']; ?></td>
                                    <!-- <td><?= $student['status']; ?></td> -->
                                    <td><?= $student['role']; ?></td>
                                    <td class='text-center'>
                                        <a href="manage_user-edit.php?id=<?= $student['id']; ?>" class="btn btn-success btn-sm"><i class="fa fa-edit" style="font-size:16px"></i> Edit</a>
                                        
                                        <form action="manage_user-code.php" method="POST" class="d-inline">
                                            <button type="submit" name="delete_student" value="<?= $student['id']; ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash" style="font-size:16px"></i> Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'><h5 class='text-danger'> No Record Found!!! </h5></td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

</body>
</html>

<?php
}
else{    
}
?>
