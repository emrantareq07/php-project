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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>BCIC Land Database</title>
</head>
<link rel="icon" type="image/gif/png" href="images/BCIC_logo.jpg">
<body>

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
                        <b>Organization Details</b>
                        <span class="float-end">
                           
                            <div style="margin-top: 10px;">
                              <a href="admin-dashboard.php" class="btn btn-primary "> <i class="fa fa-arrow-left" style="font-size:16px"></i> BACK</a>
                                <a href="add_org.php" class="btn btn-primary"> <i class="fa fa-plus" style="font-size:16px"></i> Add</a>
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
                            
                            <th>Org. Name</th>
                            <!-- <th>Designation</th> -->
                            <th>Address</th>
                            <!-- <th>Status</th> -->
                            <th>Establishment Year</th>
                            <th class='text-center'>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT * FROM org_tbl";
                            $query_run = mysqli_query($conn, $query);

                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $student) {
                                    ?>                                    
                                  <tr>
                                    <td><?= $student['id']; ?></td>                                    
                                    <td><?= $student['org_name']; ?></td>                                   
                                    <td><?= $student['address']; ?></td>                                    
                                    <td><?= $student['establish_year']; ?></td>
                                    <td class='text-center'>
                                        <a href="org_edit.php?id=<?= $student['id']; ?>" class="btn btn-success btn-sm"><i class="fa fa-edit" style="font-size:16px"></i> Edit</a>
                                        
                                        <form action="manage_user-code.php" method="POST" class="d-inline">
                                            <button type="submit" name="delete_org" value="<?= $student['id']; ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash" style="font-size:16px"></i> Delete</button>
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
