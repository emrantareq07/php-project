
<!-- Navbar Section -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">
            <img src="assets/img/logo5556.png" alt="Logo" height="40">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link">Dashboard</a>
                </li>

                <!-- Categories -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown">
                        Categories
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                        <li><a class="dropdown-item" href="add-category.php">Add Category</a></li>
                        <li><a class="dropdown-item" href="manage-categories.php">Manage Categories</a></li>
                    </ul>
                </li>

                <!-- Authors -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="authorsDropdown" role="button" data-bs-toggle="dropdown">
                        Authors
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="authorsDropdown">
                        <li><a class="dropdown-item" href="add-author.php">Add Author</a></li>
                        <li><a class="dropdown-item" href="manage-authors.php">Manage Authors</a></li>
                    </ul>
                </li>

                <!-- Books -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown">
                        Books
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="booksDropdown">
                        <li><a class="dropdown-item" href="add-book.php">Add Book</a></li>
                        <li><a class="dropdown-item" href="manage-books.php">Manage Books</a></li>
                    </ul>
                </li>

                <!-- Issue Books -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="issueDropdown" role="button" data-bs-toggle="dropdown">
                        Issue Books
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="issueDropdown">
                        <li><a class="dropdown-item" href="issue-book.php">Issue New Book</a></li>
                        <li><a class="dropdown-item" href="manage-issued-books.php">Manage Issued Books</a></li>
                    </ul>
                </li>

                <!-- Other Links -->
                <li class="nav-item"><a class="nav-link" href="std_signup.php">Registration</a></li>
                <li class="nav-item"><a class="nav-link" href="reg-students.php">Reg Students</a></li>
                <li class="nav-item"><a class="nav-link" href="change-password.php">Change Password</a></li>
                <li class="nav-item"><a class="nav-link btn btn-danger text-danger" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net@1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/js/dataTables.bootstrap5.min.js"></script>
<!-- <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">
      <img src="assets/img/logo5556.png" height="40" alt="Logo">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse text-uppercase" id="navbarContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>

        <!-- Categories Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Categories
          </a>
          <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
            <li><a class="dropdown-item" href="add-category.php">Add Category</a></li>
            <li><a class="dropdown-item" href="manage-categories.php">Manage Categories</a></li>
          </ul>
        </li>

        <!-- Authors Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="authorsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Authors
          </a>
          <ul class="dropdown-menu" aria-labelledby="authorsDropdown">
            <li><a class="dropdown-item" href="add-author.php">Add Author</a></li>
            <li><a class="dropdown-item" href="manage-authors.php">Manage Authors</a></li>
          </ul>
        </li>

        <!-- Books Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Books
          </a>
          <ul class="dropdown-menu" aria-labelledby="booksDropdown">
            <li><a class="dropdown-item" href="add-book.php">Add Book</a></li>
            <li><a class="dropdown-item" href="manage-books.php">Manage Books</a></li>
          </ul>
        </li>

        <!-- Issue Books Dropdown -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="issueDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Issue Books
          </a>
          <ul class="dropdown-menu" aria-labelledby="issueDropdown">
            <li><a class="dropdown-item" href="issue-book.php">Issue New Book</a></li>
            <li><a class="dropdown-item" href="manage-issued-books.php">Manage Issued Books</a></li>
          </ul>
        </li>

        <li class="nav-item"><a class="nav-link" href="std_signup.php">Registration</a></li>
        <li class="nav-item"><a class="nav-link" href="reg-students.php">Reg Students</a></li>
        <li class="nav-item"><a class="nav-link" href="change-password.php">Change Password</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="logout.php"><i class="fa fa-sign-out"></i> Logout</a></li>
      </ul>
    </div>
  </div>
</nav> -->