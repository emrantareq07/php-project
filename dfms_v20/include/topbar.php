<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DFMS Dashboard</title>
  <!-- Bootstrap CSS CDN -->
  <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet"> -->
</head>
<body>
  <nav class="navbar navbar-expand-lg bg-body-tertiary bg-dark navbar-dark fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">DFMS Dashboard</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <!-- <a class="nav-link" href="#">Download DB</a> -->
              <form id="downloadForm" action="dawnload_database.php" method="post" class="m-0">
                <button class="btn btn-warning" type="submit" name="submit">
                    <i class="fa fa-download"></i> Download DB
                </button>
            </form>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Factory Name
            </a>
            <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="urea_form.php?val=sfcl&user_type=<?php echo $user_type; ?>">SFCL</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=jfcl&user_type=<?php echo $user_type; ?>">JFCL</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=afccl&user_type=<?php echo $user_type; ?>">AFCCL</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=cufl&user_type=<?php echo $user_type; ?>">CUFL</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=gpfplc&user_type=<?php echo $user_type; ?>">GPFPLC</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=tspcl&user_type=<?php echo $user_type; ?>">TSPCL</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=dapfcl&user_type=<?php echo $user_type; ?>">DAPFCL</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=bisf&user_type=<?php echo $user_type; ?>">BISF</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=cccl&user_type=<?php echo $user_type; ?>">CCCL</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=ugsf&user_type=<?php echo $user_type; ?>">UGSF</a></li>
            <li><a class="dropdown-item" href="urea_form.php?val=kpml&user_type=<?php echo $user_type; ?>">KPML</a></li>              
            </ul>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link disabled" aria-disabled="true">Disabled</a>
          </li> -->
        </ul>
        <form class="d-flex float-end" role="search">
          <input class="form-control me-2 col-sm-6" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>
  <h2 class="my-3 mt-3 pt-5 text-success fw-bold text-center">Welcome Superadmin!!!  </h2>

