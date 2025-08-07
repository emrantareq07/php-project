<?php
// session_start();
?>
<!-- <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css"> -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap" rel="stylesheet">
  <style>
    .navbar-nav .nav-item .profile-picture {
      width: 30px;
      height: 30px;
      border-radius: 50%;
      object-fit: cover;
    }
    .navbar-collapse {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .username-center {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      color: white;
    }
        .navbar-brand-text {
      color: white;
      font-weight: bold;
       font-size: 2.2rem;
    }
    .poetsen-one-regular {
  font-family: "Poetsen One", sans-serif;
  font-weight: 400;
  font-style: normal;
}

  </style>
<!-- </head>
  style="background-color: #751aff;
<body> -->
  <nav class="navbar navbar-expand-sm   navbar-dark" style="background-color: #751aff;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#"><img src="../images/bcic_logo.png" alt="Profile" width="100%" height="40" class="profile-picture"></a><h3 class="text-white poetsen-one-regular">BCIC-(DFMS)</h3>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="collapsibleNavbar">
 <!--        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Link</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Dropdown</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Link</a></li>
              <li><a class="dropdown-item" href="#">Another link</a></li>
              <li><a class="dropdown-item" href="#">A third link</a></li>
            </ul>
          </li>
        </ul> -->
       <!--  <div class="username-center text-uppercase "><b><h2 class="text-white">BCIC - Welcome DFMS (<?php echo $_SESSION['username']; ?>)</h2></b></div> -->

       <?php 
       $title= $_SESSION['username'];

        if ($title == 'sfcl') {
            $title = 'Shahjalal Fertilizer Company Ltd. (SFCL)';
        } elseif ($title == 'jfcl') {
            $title = 'Jamuna Fertilizer Company Ltd. (JFCL)';
        } elseif ($title == 'afccl') {
            $title = 'Ashuganj Fertilizer Company Ltd. (AFCCL)';
        } elseif ($title == 'gpfplc') {
            $title = 'Ghorashal Polash Fertilizer PLC (GPFPLC)';
        } elseif ($title == 'cufl') {
            $title = 'Chittagong Urea Fertilizer Ltd. (CUFL)';
        } elseif ($title == 'tspcl') {
            $title = 'TSP Complex Limited (TSPCL)';
        } elseif ($title == 'dapfcl') {
            $title = 'DAP Fertilizer Company Limited (DAPFCL)';
        } elseif ($title == 'bisf') {
            $title = 'Bangladesh Industrial Salt Factory (BISF)';
        } elseif ($title == 'cccl') {
            $title = 'Chatak Cement Company Limited (CCCL)';
        } elseif ($title == 'ugsf') {
            $title = 'Osmania Glass Sheet Factory Limited (UGSFL)';
        } elseif ($title == 'kpml') {
            $title = 'Karnaphuli Paper Mills Limited (KPML)';
        }
       ?>
        <div class="username-center text-uppercase "><b><h3 class="text-white poetsen-one-regular"> <?php echo $title; ?></h3></b></div>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              <img src="../images/fd.png" alt="Profile" class="profile-picture">
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#">Profile</a></li>
              <li><a class="dropdown-item" href="#">Settings</a></li>
              <li><a class="dropdown-item" href="./logout.php"><i class="fa fa-sign-out"></i> Sign Out</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>



  <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script> -->
<!-- </body>
</html> -->
