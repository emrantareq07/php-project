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
if ($role === 'admin') {
    // if (($role === 'admin') || ($role === 'sadmin')) {
    include 'db.php';
    // Handle search functionality
    $search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';    
    if (!empty($search_query)) {
        // If a search query is present, execute the specific search query
        $query = "SELECT * FROM users WHERE status='approved' AND role='user' AND emp_id LIKE '%$search_query%'";
    } else {
        // If no search query is present, fetch all users
        $query = "SELECT * FROM users WHERE status='approved' AND role='user'";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>BCIC PMIS</title>
</head>
<body>
<div class="container-fluid mt-2">
    <?php include('message.php'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow border border-success border-2">
                <div class="card-header">
                    <h4 class="text-center text-uppercase ">
                        <?php
                        if ($role === 'admin') {
                            echo '<a href="admin_dashboard.php?role=' . $_SESSION["role"] . '" class="btn btn-primary float-start"><i class="fa fa-arrow-left"></i> Previous Page</a>';
                        } else {
                            echo '<a href="super-admin_dashboard.php?role=' . $_SESSION["role"] . '" class="btn btn-primary float-start"> <i class="fa fa-arrow-left"></i> Previous Page</a>';
                        }
                        ?>
                        <b>User Management</b>
                        <span class="float-end">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by Emp ID" aria-label="Search" aria-describedby="searchBtn" id="searchInput" value="<?= $search_query ?>">
                               
                            </div>
                            <div style="margin-top: 10px;">
                                <a href="#" class="btn btn-primary"> <i class="fa fa-plus"></i> Add</a>
                                <a href="logout.php" class="btn btn-danger"> <i class="fa fa-sign-out"></i> Logout</a>
                            </div>
                        </span>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>Emp ID</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Password</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th class='text-center'>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_run = mysqli_query($conn, $query);
                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $student) {
                                    ?>
                                    <!-- Display user data -->
                                  <tr>
                                    <td><?= $student['id']; ?></td>
                                    <td><?= $student['emp_id']; ?></td>
                                    <td><?= $student['name']; ?></td>
                                    <td><?= $student['designation']; ?></td>
                                    <td><?= $student['password']; ?></td>
                                    <td><?= $student['status']; ?></td>
                                    <td><?= $student['role']; ?></td>
                                    <td class='text-center'>
                                    <a href="manage_user-edit.php?id=<?= $student['id']; ?>" class="btn btn-success btn-sm"> <i class="fa fa-edit"></i> Edit</a>
                                        
                                    <form action="manage_user-code.php" method="POST" class="d-inline">
                                    <button type="submit" name="delete_student" value="<?= $student['id']; ?>" class="btn btn-danger btn-sm"> <i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                    </td>
                                </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'><h5 class='text-danger'> No Record Found!!! </h5></td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    // Perform live search when the user types in the input field
    $('#searchInput').on('input', function() {
        var searchQuery = $(this).val();
        fetchSearchResults(searchQuery);
    });

    // Perform live search when the user clicks the search button
    $('#searchBtn').on('click', function() {
        var searchQuery = $('#searchInput').val();
        fetchSearchResults(searchQuery);
    });

    function fetchSearchResults(searchQuery) {
        $.ajax({
            url: 'search_users.php', // Create a separate PHP file (e.g., search_users.php) to handle the search logic
            method: 'GET',
            data: { search: searchQuery },
            success: function(response) {
                $('tbody').html(response);
            }
        });
    }
</script>
</body>
</html>

<?php
}
else{
    include 'db.php';
    // Handle search functionality
    $search_query = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';    
    if (!empty($search_query)) {
        // If a search query is present, execute the specific search query
        $query = "SELECT * FROM users WHERE status='approved'  AND emp_id LIKE '%$search_query%'";
    } else {
        // If no search query is present, fetch all users
        $query = "SELECT * FROM users WHERE status='approved' ";
    }
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>BCIC PMIS</title>
</head>
<body>

<div class="container-fluid mt-2">
    <?php include('message.php'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow border border-success border-2">
                <div class="card-header">
                    <h4 class="text-center text-uppercase ">
                        <?php
                        if ($role === 'admin') {
                            echo '<a href="admin_dashboard.php?role=' . $_SESSION["role"] . '" class="btn btn-primary float-start"><i class="fa fa-arrow-left"></i> Previous Page</a>';
                        } else {
                            echo '<a href="super-admin_dashboard.php?role=' . $_SESSION["role"] . '" class="btn btn-primary float-start"><i class="fa fa-arrow-left"></i> Previous Page</a>';
                        }
                        ?>
                        <b>User Management</b>
                        <span class="float-end">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search by Emp ID" aria-label="Search" aria-describedby="searchBtn" id="searchInput" value="<?= $search_query ?>">
                               
                            </div>
                            <div style="margin-top: 10px;">
                                <a href="add_adminuser.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add</a>
                                <a href="logout.php" class="btn btn-danger"> <i class="fa fa-sign-out"></i> Logout</a>
                            </div>
                        </span>
                    </h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                              <tr>
                            <th>ID</th>
                            <th>Emp ID</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Password</th>
                            <th>Status</th>
                            <th>Role</th>
                            <th class='text-center'>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query_run = mysqli_query($conn, $query);
                            if (mysqli_num_rows($query_run) > 0) {
                                foreach ($query_run as $student) {
                                    ?>
                                    <!-- Display user data -->
                                  <tr>
                                    <td><?= $student['id']; ?></td>
                                    <td><?= $student['emp_id']; ?></td>
                                    <td><?= $student['name']; ?></td>
                                    <td><?= $student['designation']; ?></td>
                                    <td><?= $student['password']; ?></td>
                                    <td><?= $student['status']; ?></td>
                                    <td><?= $student['role']; ?></td>
                                    <td class='text-center'>
                                    <a href="manage_user-edit.php?id=<?= $student['id']; ?>" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> </a>
                                        
                                    <form action="manage_user-code.php" method="POST" class="d-inline">
                                    <button type="submit" name="delete_student" value="<?= $student['id']; ?>" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> </button>
                                    </form>
                                    </td>
                                </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='8' class='text-center'><h5 class='text-danger'> No Record Found!!! </h5></td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    // Perform live search when the user types in the input field
    $('#searchInput').on('input', function() {
        var searchQuery = $(this).val();
        fetchSearchResults(searchQuery);
    });

    // Perform live search when the user clicks the search button
    $('#searchBtn').on('click', function() {
        var searchQuery = $('#searchInput').val();
        fetchSearchResults(searchQuery);
    });

    function fetchSearchResults(searchQuery) {
        $.ajax({
            url: 'search_all.php', // Create a separate PHP file (e.g., search_users.php) to handle the search logic
            method: 'GET',
            data: { search: searchQuery },
            success: function(response) {
                $('tbody').html(response);
            }
        });
    }
</script>
</body>
</html>
<?php
}
?>
