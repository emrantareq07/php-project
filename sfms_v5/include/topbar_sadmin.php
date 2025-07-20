<style>
.navbar-nav .nav-item .profile-picture {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  object-fit: cover;
}
</style>

<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><img src="../images/bcic_logo.png" alt="Profile" width="40" height="40" class="profile-picture">
    </a><h3 class="text-white montserrat-alternates-bold">BCIC-(SFMS)</h3>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link text-uppercase" href="monthly_demand_set.php?username=<?php echo $_SESSION['username']; ?>">Monthly Demand Set</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-uppercase" href="pipeline_details.php?username=<?=$_SESSION['username']?>">Pipeline Data</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link text-uppercase" href="#">Link</a>
        </li> -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-uppercase" href="#" role="button" data-bs-toggle="dropdown">Report</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="report_view.php">Daily Report(MT)</a></li>
            <li><a class="dropdown-item" href="#">Another link</a></li>
            <li><a class="dropdown-item" href="#">A third link</a></li>
          </ul>
        </li>
      </ul>
      <!-- <h1 class="text-center text-white">SFMS<b class="text-success text-uppercase"> (<?=$_SESSION['username'];?>)</b></h1> -->
      <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <img src="../images/fd.png" alt="Profile" class="profile-picture">
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#"><i class="fa fa-user" style="font-size:15px;color:black"></i> Profile</a></li>
            <li><a class="dropdown-item" href="#"><i class="fa fa-cog" style="font-size:15px;color:black"></i> Settings</a></li>
            <li><a class="dropdown-item" href="./logout.php"><i class="fa fa-sign-out" style="font-size:15px;color:black"></i> Sign Out</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>