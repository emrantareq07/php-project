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

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title> Edit</title>
    <link rel="icon" type="image/gif/png" href="images/BCIC_logo.jpg">
</head>
<body>  
    <div class="container mt-4">
        <?php //include('message.php'); ?>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card shadow border border-success border-2">
                    <div class="card-header">
                        <h4 class="text-uppercase"> Edit 
                            <a href="org_details.php" class="btn btn-danger float-end"><i class="fa fa-arrow-left"></i> BACK</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if(isset($_GET['id'])){
                            $id = mysqli_real_escape_string($conn, $_GET['id']);
                            $query = "SELECT * FROM org_tbl WHERE id='$id'";
                            $query_run = mysqli_query($conn, $query);

                            if(mysqli_num_rows($query_run) > 0){
                                //while($student=mysqli_fetch_array($query_run)){
                                $student = mysqli_fetch_array($query_run);
                                    //$role=$student['role'];

                                ?>
                        <form action="manage_user-code.php" method="POST">
                        <input type="hidden" name="id" value="<?= $student['id']; ?>">

                        <div class="mb-3">
                            <label for="org_name">Org. Name</label>
                            <input type="text" name="org_name" id="org_name" value="<?=$student['org_name'];?>" class="form-control">                            
                        </div>    
                        <div class="mb-3">
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" value="<?=$student['address'];?>" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="establish_year">Establishment Year</label>
                            <input type="text" name="establish_year" id="establish_year" value="<?=$student['establish_year'];?>" class="form-control">
                        </div>                  
				
                <div class="form-group mb-3">
                    <button type="submit" name="update_org" class="btn btn-primary">
                        <i class="fa fa-save"></i> Update
                    </button>
                </div>

            </form>
            <?php
        }
    //}
        else{
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