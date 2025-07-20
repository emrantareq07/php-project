<?php
session_start();
// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['emp_id'])) {
  header("Location: login.php");
  exit();
}
// Check the user role
$role = $_SESSION['role'];
// Display different content based on the user role
if ($role === 'sadmin') {
  //echo "<h1 class='text-center my-2 text-uppercase text-secondary'>Welcome, Admin!</h1>";
  // Display admin-specific content
  //header("Location: admin_approval.php");
include 'db.php';
// Get the combined pending request counts
$sql = "SELECT
           SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS pending_count,
           SUM(CASE WHEN job_confirm_status = 'pending' THEN 1 ELSE 0 END) AS pending_count_job_confirm
        FROM users";

$result = mysqli_query($conn, $sql);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $pendingCount = $row['pending_count'];
    $pendingCountJobConfirm = $row['pending_count_job_confirm'];
} else {
    // Handle the query error
    echo "Error: " . mysqli_error($conn);
    }

 include_once('navbar.php');
 include_once('includes/header_sadmin.php');
 ?>

  <div class="container-fluid border shadow rounded my-2">
    <!-- Content here --> 
    <div class="row">
      <h1 class='text-center my-1 text-uppercase text-secondary'>Welcome, Super Admin!</h1>
      <div class="col-sm-2">
        <hr>
        <p>
          <center><a href="../dashboard.php?role=<?php echo $_SESSION['role'] ?>" class="btn btn-primary "><i class="fa fa-gear"></i> Dashboard</a></p><p>
            <a class="btn btn-primary " href="manage_user.php"><i class="fa fa-users"></i> Manage User</a></center>        
        </p>       
        <p>
        <center><a class="btn btn-primary position-relative" href="job_confirm_status.php" ><i class="fa fa-exchange"></i> Job Confirm Status
           <!-- <span class="badge bg-danger"><?php echo $pendingCount; ?></span> -->
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          <?php echo $pendingCountJobConfirm; ?>
        <!-- <span class="visually-hidden">unread messages</span> --></span>
        </a></center></p>
        <p>
        <center>
        <a class="btn btn-warning" href="overview.php"><i class="fa fa-eye"></i> Overview</a></center></p>
        <center><a class="btn btn-danger" href="logout.php"> <i class="fa fa-sign-out"></i>Logout</a></center></p>
        <br>
      </div>
      <div class="col-sm-10">
    <h1 class="text-white text-center text-uppercase bg-dark bg-outline-info rounded "> User Request List
    <a class="btn btn-custom position-relative float-end mt-1 me-1 text-white " >Pending Request      
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
    <?php echo $pendingCount; ?>
    </span>
    </a>
      </h1>
      <div class="table-responsive">
      <table class="table table-bordered table-striped text-center" id="users">
      <thead>
        <tr>
          <!-- <th>#</th> -->
          <th>Emp Type</th>
          <th>Name</th>
          <th>Designation</th>
          <th>Office</th>
          <th>Mobile NO.</th>
          <th>Email</th>
          <th>NID</th>          
          <th>Picture</th>
          <th>Action</th>
        </tr>
      </thead>
      <?php
    // $sql="select * from users_tbl where status='pending' ORDER BY ASC";
    $sql="SELECT * From users where status ='pending' order by id ASC";
  $result=mysqli_query($conn,$sql);
  while($row = mysqli_fetch_array($result)) {
    ?>
  <tbody>
    <tr>
      <!-- <td><?php echo $row['id'];?></td> -->
      <td><?php echo $row['employee_type'];?></td>
      <td><?php echo $row['name'];?></td>
      <td><?php echo $row['designation'];?></td>
      <td><?php echo $row['place_of_posting'];?></td>
      <td><?php echo $row['mobile_no'];?></td>
      <td><?php echo $row['email'];?></td>
      <td><?php echo $row['nid'];?></td>
      <!-- <td><?php echo $row['image'];?></td> -->
      <td><?php 
        if ($row['image'] != '' && file_exists('' . $row['image'])) {
      echo "<img class='rounded rectangle border border-muted' src='" . $row['image'] . "' width='50' height='50'";
  } else {
      echo "<img class='rounded-circle border border-muted' src='image/avatar.png' width='30' height='30'>";
 }?></td>
      <!-- <td><?php echo $row['image'];?></td> -->
      <!-- <td><img src="image/<?php echo $row['image']; ?>" alt="Image"></td> -->
      <!-- <td><img src="<?php echo 'image/' . $row['image']; ?>" alt="Image"></td> -->
      <!-- <td><?php echo "<img class='rounded-circle border border-muted' src='image/" . $row['image'] . "' width='30' height='30'>"; ?></td> -->
      
      <td>
        <form action="admin_dashboard_1.php" method="POST">
          <input type="hidden" name="id" value="<?php echo $row['id'];?>"/>
          <button type="submit" name="approve" class="btn btn-primary btn-xs">
          <i class="fa fa-check"></i> Approve
          </button>
          <button type="submit" name="deny" class="btn btn-danger btn-xs">
          <i class="fa fa-trash"></i> Deny
          </button>
          <!-- <input type="submit" name='deny' class="btn btn-danger btn-xs" value="Deny"/> -->

        </form>
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
if(isset($_POST['approve'])){
  $id=$_POST['id'];

  $sql="UPDATE  users set status='approved' where id='$id'";
	$result=mysqli_query($conn,$sql);
	echo '<script type="text/javascript">';
  echo 'alert("User Approved");';
  echo 'window.location.href="super-admin_dashboard.php"';
  echo '</script>';
  }
elseif(isset($_POST['admin'])){
  //if(isset($_POST['admin'])){
  $id=$_POST['id'];
  $sql="UPDATE  users set role='admin' where id='$id'";
  $result=mysqli_query($conn,$sql);
  echo '<script type="text/javascript">';
  echo 'alert("User is now Admin!");';
  echo 'window.location.href="super-admin_dashboard.php"';
  echo '</script>';
}
else{
  if(isset($_POST['deny'])){
  $id=$_POST['id'];

  $sql="DELETE from users where id='$id'";
  $result=mysqli_query($conn,$sql);
  echo '<script type="text/javascript">';
  echo 'alert("User Denied!");';
  echo 'window.location.href="super-admin_dashboard.php"';
  echo '</script>';
    }
  }
?>

 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>  
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>   

  <script src="css/sidebars.js"></script>
  <script>
    var menu_btn = document.querySelector("#menu-btn");
    var sidebar = document.querySelector("#sidebar");
    var container = document.querySelector(".my-container");

    menu_btn.addEventListener("click", () => {
      sidebar.classList.toggle("active-nav");
      container.classList.toggle("active-cont");
    });
  </script>
  </body>
</html>
<?php
} else {
  echo "<h1>Welcome, User!</h1>";
  // Display user-specific content
  header("Location: user_dashboard.php");
}
?>


