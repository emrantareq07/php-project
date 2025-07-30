<!-- Navbar Section -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success fixed-top">
    <div class="container">
        <!-- Brand Logo -->
        <a class="navbar-brand bg-white rounded" href="dashboard.php">
            <img src="assets/img/logo5556.png" alt="Logo" height="40">
        </a>
        
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Menu -->
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto">
                <!-- Home Link -->
                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link active"><i class="fas fa-home me-1"></i> Home</a>
                </li>

                <!-- Categories Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoriesDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-list me-1"></i> Categories
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="categoriesDropdown">
                        <li><a class="dropdown-item" href="add-category.php"><i class="fas fa-plus me-1"></i>Add Category</a></li>
                        <li><a class="dropdown-item" href="manage-categories.php"><i class="fas fa-tasks me-1"></i>Manage Categories</a></li>
                    </ul>
                </li>

                <!-- Authors Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="authorsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-edit me-1"></i> Authors
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="authorsDropdown">
                        <li><a class="dropdown-item" href="add-author.php"><i class="fas fa-plus me-1"></i>Add Author</a></li>
                        <li><a class="dropdown-item" href="manage-authors.php"><i class="fas fa-tasks me-1"></i>Manage Authors</a></li>
                    </ul>
                </li>

                <!-- Books Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="booksDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-book me-1"></i> Books
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="booksDropdown">
                        <li><a class="dropdown-item" href="add-book.php"><i class="fas fa-plus me-1"></i>Add Book</a></li>
                        <li><a class="dropdown-item" href="manage-books.php"><i class="fas fa-tasks me-1"></i>Manage Books</a></li>
                    </ul>
                </li>

                <!-- Issue Books Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="issueDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-exchange-alt me-1"></i> Issue Books
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="issueDropdown">
                        <li><a class="dropdown-item" href="issue-book.php"><i class="fas fa-plus me-1"></i> Issue New Book</a></li>
                        <li><a class="dropdown-item" href="manage-issued-books.php"><i class="fas fa-tasks me-1"></i> Manage Issued Books</a></li>
                        <li><a class="dropdown-item" href="not-return-books.php"><i class="fas fa-exclamation-circle me-1"></i> Not Returned Books</a></li>
                    </ul>
                </li>

                <!-- Other Links -->
                <li class="nav-item">
                    <a class="nav-link" href="std_signup.php"><i class="fas fa-user-plus me-1"></i> Registration</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="reg-students.php"><i class="fas fa-users me-1"></i> Registered EMP</a>
                </li>

                <!-- Settings Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="settingsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-cog me-1"></i> Settings
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="settingsDropdown">
                        <li>
                            <form id="downloadForm" action="dawnload_database.php" method="post">
                                <button class="dropdown-item" type="submit" name="submit">
                                    <i class="fas fa-download me-1"></i> Download DB
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
                
                <!-- Logout Button -->
                <li class="nav-item">
                    <a class="nav-link btn btn-danger ms-lg-2 mt-2 mt-lg-0" href="logout.php">
                        <i class="fa fa-sign-out me-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>