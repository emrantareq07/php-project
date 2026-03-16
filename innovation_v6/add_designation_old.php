<?php
session_start();  
require_once("config/config.php");
require_once("db/db.php");
include('includes/header.php');

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
      foreach ($result as $k => $v) {
        $uun = $result[$k]['Username'];
        $uup = $result[$k]['Password'];
      }
    }
?>

<div class="container mt-3 ">  
  <div class="row">
    <div class="col-sm-4"></div>
    <div class="col-sm-4 border rounded shadow-lg border-primary"><h2 class="text-uppercase text-center">Add Designation</h2>
     <?php
    if(@$_GET['submitted'])
    {?>
    <div class="alert alert-success" role="alert">
      Data Inserted Successfully
    </div>
    <?php }?>
   <!-- <form method="POST" action="add_designation.php"> -->
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <div class="form-floating mb-3 mt-3">
      <input type="text" class="form-control" id="designation_bn" placeholder="Enter Designation" name="designation_bn" required="">
      <label for="designation ">Designation BN</label>
    </div>
   <div class="form-floating mt-3 mb-3">
      <input type="text" class="form-control" id="designation_en" placeholder="Enter password" name="designation_en">
      <label for="designation_en">Designation EN</label>
    </div> 
    <button type="submit" name ="submit" class="btn btn-primary">Submit</button>
  </form>
    <br>
    </div>

    <div class="col-sm-4">
      <a href="dashboard.php" class="btn btn-outline-info float-end mt-5"><i class="fa fa-arrow-left"></i> Previous Page</a>
    </div>

  </div>
  <br>
  <div class="row">
    <div class="table-wrapper">
    <table class="table table-bordered table-striped table-hover shadow-lg border-warning" width="100%">
      <thead>
        <th>ID NO</th>
        <th>Designation_BN</th>
        <th>Designation_EN</th>
        <th>Action</th>
      </thead>
      <tbody>
        <?php          
          $query = mysqli_query($conn, "SELECT * FROM `designation` ORDER BY `id` ASC");
          while($row=mysqli_fetch_array($query)){
            ?>
            <tr>
              <td><?php echo $row['id']; ?></td>
              <td><?php echo $row['designation_bn']; ?></td>
              <td><?php echo $row['designation_en']; ?></td>
              <td>
                <a href="edit_designation.php?id=<?php echo $row['id']; ?>"><i class="fa fa-edit"></i></a>
                <a href="delete_designation.php?id=<?php echo $row['id']; ?>"><i class="fa fa-trash" style="font-size:20px;color:red"></i></a>
              </td>
            </tr>
            <?php
          }
        ?>
      </tbody>
    </table>
    </div>
  </div>
  </div>


</div>
<?php 
 include_once('includes/footer.php');
?>


