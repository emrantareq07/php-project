<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>e-Library Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Custom Style -->
    <style>
        body {
            font-size: 16px;
            padding-top: 70px;
        }
        .navbar-nav .nav-link {
            font-size: 16px;
            padding: 0.5rem 1rem;
        }
        .dropdown-item {
            font-size: 15px;
        }
        .carousel-inner > .carousel-item > img {
            width: 100%;
            height: 500px;
            object-fit: cover;
        }
        .slide-bdr {
            border: 2px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
        }
        .input-wrapper {
            position: relative;
            width: 100%;
        }
        .with-clear {
            padding-right: 30px;
        }
        .clear-btn {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #999;
            cursor: pointer;
            display: none;
        }
        .clear-btn:hover { 
            color: #555; 
        }
        textarea.with-clear + .clear-btn { 
            top: 20px; 
            right: 15px; 
        }
        .student-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid #ddd;
        }
        .student-img:hover {
            transform: scale(2);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .student-card {
            transition: transform 0.2s;
            margin-bottom: 20px;
        }
        .student-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 20px;
        }
        .student-img-preview {
            max-width: 100px;
            max-height: 100px;
            border: 1px solid #ccc;
            margin-top: 0px;
        }
        .alert-stat {
            min-height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .navbar {
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<!-- Navbar Section -->
<nav class="navbar navbar-expand-lg navbar-success bg-success fixed-top">
    <!-- <nav class="navbar navbar-expand-lg  fixed-top" style="color:#6f47b3; background: #6f47b3;"> -->
    <div class="container">
        <a class="navbar-brand bg-white rounded" href="dashboard.php">
            <img src="assets/img/logo5556.png" alt="Logo" height="40">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link active text-white"><i class="fas fa-home"></i> Home</a>
                </li>

                <!-- Categories Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-list"></i> Categories
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="categoriesDropdown">
                        <li><a class="dropdown-item" href="add-category.php">Add Category</a></li>
                        <li><a class="dropdown-item" href="manage-categories.php">Manage Categories</a></li>
                    </ul>
                </li>

                <!-- Authors Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="authorsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Authors
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="authorsDropdown">
                        <li><a class="dropdown-item" href="add-author.php">Add Author</a></li>
                        <li><a class="dropdown-item" href="manage-authors.php">Manage Authors</a></li>
                    </ul>
                </li>

                <!-- Books Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Books
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="booksDropdown">
                        <li><a class="dropdown-item" href="add-book.php">Add Book</a></li>
                        <li><a class="dropdown-item" href="manage-books.php">Manage Books</a></li>

                    </ul>
                </li>

                <!-- Issue Books Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="issueDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Issue Books
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="issueDropdown">
                        <li><a class="dropdown-item" href="issue-book.php"><i class="fas fa-plus"></i> Issue New Book</a></li>
                        <li><a class="dropdown-item" href="manage-issued-books.php">Manage Issued Books</a></li>
                         <li><a class="dropdown-item" href="not-return-books.php">Not Return Books</a></li>
                    </ul>
                </li>

                <!-- Other Links -->
                <li class="nav-item"><a class="nav-link" href="std_signup.php">Registration</a></li>
                <li class="nav-item"><a class="nav-link" href="reg-students.php">Registered EMP</a></li>
                <!-- <li class="nav-item"><a class="nav-link" href="change-password.php">Change Password</a></li> -->

                <!-- Settings Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown">
                        <!-- <li><a class="dropdown-item" href="inactive_process.php"><i class="fas fa-power-off"></i> Inactive/Active Process</a></li>
                        <li> -->
                            <form id="downloadForm" action="dawnload_database.php" method="post" class="px-3 py-2">
                                <button class="btn btn-warning btn-sm w-100" type="submit" name="submit">
                                    <i class="fas fa-download"></i> Download DB
                                </button>
                            </form>
                        </li>
                        <!-- <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="dawnload_database.php"><i class="fas fa-database"></i> DB Backup</a></li> -->
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link btn btn-danger  ms-2" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>