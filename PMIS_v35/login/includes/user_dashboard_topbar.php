<?php include 'db.php' ;
//session_start();
if (isset($_SESSION['emp_id'])) {
    $emp_id = $_SESSION['emp_id']; // Store the value in a variable for easier access
    $role = $_SESSION['role'];
} else {
    // Redirect to the login page or handle the case when the 'email' session variable is not set
    header("Location: index.php");
    exit();
}

?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/>
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">-->
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg  " style="background-color:#5200cc">
    <!-- Container wrapper -->
    <div class="container-fluid">
      <!-- Toggle button -->
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false"
        aria-label="Toggle navigation">
        <i class="fas fa-bars"></i>
      </button>
	  
	<div class="row">
		<div class="col-md-12">		
		<!-- Collapsible wrapper -->
      <div class="collapse navbar-collapse responsive" id="navbarSupportedContent">
        <!-- Navbar brand -->
        <a class="navbar-brand mt-2 mt-lg-0" href="#" >
          <img class="shadow-lg" 
            src="image/bcic_logo.png"
            height="40"
            alt="BCIC PMIS"
            loading="lazy"
          /> 
        </a>
        <!-- Left links -->
        <ul class="navbar-nav me-auto mb-2 mb-lg-0 text-uppercase">
          <li class="nav-item">
            <a class="nav-link text-white" href="user_dashboard.php"><i class="fa fa-home"></i> Home</a>
          </li>
          
          <li class="nav-item ">
            <a class="nav-link text-white" href="#"><i class="fa fa-print"></i> Increment Letter</a>
          </li>
		  <li class="nav-item ">
            <a class="nav-link text-white" href="./bio-data_users.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"><i class="fa fa-eye"></i> View Bio-Data</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link text-white" id="collapse-all"  href="edit_user_image.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"><i class="fa fa-upload"></i> Upload Profile Picture</a>
          </li>

           <li class="nav-item ">
            <a class="nav-link text-white" href="./forget_password.php?emp_id=<?php echo $_SESSION['emp_id'] ?>"><i class="fa fa-edit"></i> Change Password</a>
          </li>
          <li class="nav-item ">
            <a class="nav-link text-white" href="../view/view_profile_details.php?emp_id=<?php echo $_SESSION['emp_id'] ?>&role=<?php echo $_SESSION['role']?>"><i class="fa fa-edit"></i> Edit Profile</a>
          </li>

        </ul>
        <!-- Left links -->
      </div>
      <!-- Collapsible wrapper -->		
		</div>
	</div>      

      <!-- Right elements -->
      <div class="d-flex align-items-center">
        <!-- Icon -->
        <!-- <a class="text-reset me-3" href="#">
          <i class="fas fa-shopping-cart"></i>
        </a> -->

        <!-- Notifications -->
      <!--   <div class="dropdown">
          <a
            class="text-reset me-3 dropdown-toggle hidden-arrow"
            href="#"
            id="navbarDropdownMenuLink"
            role="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >
            <i class="fas fa-bell"></i>
            <span class="badge rounded-pill badge-notification bg-danger">1</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
            <li>
              <a class="dropdown-item" href="#">Some news</a>
            </li>
            <li>
              <a class="dropdown-item" href="#">Another news</a>
            </li>
            <li>
              <a class="dropdown-item" href="#">Something else here</a>
            </li>
          </ul>
        </div> -->

        <!-- Avatar -->
        <div class="dropdown">
          <a
            class="dropdown-toggle d-flex align-items-center hidden-arrow"
            href="#"
            id="navbarDropdownMenuAvatar"
            role="button"
            data-bs-toggle="dropdown"
            aria-expanded="false"
          >

          <?php 
          $emp_id = $_SESSION['emp_id'];
          $sql = "SELECT * FROM users WHERE emp_id='$emp_id'";
          $result = mysqli_query($conn,$sql);
          while($row = mysqli_fetch_array($result)) {
             if ($row['image'] != '' && file_exists('' . $row['image'])) {
            print "<center>&nbsp;<input class='rounded-circle shadow-lg' type=\"image\" name=\"imageField\" 
           width=\"30\" height=\"30\" src=\"".$row['image']."\">&nbsp; </center>";
            } 
            else {
           
            echo "<img class='mx-auto d-block rounded-circle shadow border border-muted border-success img-fluid my-2' src='image/avatar.png' width='30' height='30'>";
              }

 //    print "<center>&nbsp;<input class='rounded-circle shadow-lg' type=\"image\" name=\"imageField\" 
 // width=\"30\" height=\"30\" src=\"".$row['image']."\">&nbsp; </center>";
            }
          ?>
             <!-- <img
                src="image/<?php echo $row['image']; ?>"
                class="rounded-circle"
                height="25"
                alt="image"
                loading="lazy"
              /> -->
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
            <li>
              <a class="dropdown-item" href="#">My profile</a>
            </li>
            <li>
              <a class="dropdown-item" href="#"><i class="fa fa-cog"></i> Settings</a>
            </li>
            <li>
              <a class="dropdown-item" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
            </li>
          </ul>
        </div>
      </div>
      <!-- Right elements -->
    </div>
    <!-- Container wrapper -->
  </nav>
  <!-- Navbar -->

    <script>
document.addEventListener("DOMContentLoaded", function () {
  const collapseAllButton = document.getElementById("collapse-all");
  const collapseButtons = document.querySelectorAll("[data-bs-toggle='collapse']");

  collapseAllButton.addEventListener("click", function () {
    collapseButtons.forEach(function (button) {
      const targetId = button.getAttribute("data-bs-target");
      const target = document.querySelector(targetId);
      if (target && target.classList.contains("show")) {
        button.click();
      }
    });
  });
});
</script>