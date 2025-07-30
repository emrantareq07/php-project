<?php
session_name('bcic_college_e-library');
session_start();
include('includes/config.php');

// Redirect if not logged in
if (!isset($_SESSION['login']) || strlen($_SESSION['login']) == 0) {
    header('location:index.php');
    exit();
}

// Handle deletion
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $sql = "DELETE FROM tblbooks WHERE id = :id";
    $query = $dbh->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_STR);
    $query->execute();
    $_SESSION['delmsg'] = "Book deleted successfully";
    header('location:manage-books.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>User Dashboard | Online Library</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/v/bs5/dt-1.13.7/datatables.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Custom Styles -->
    <style>
       body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
            padding-top: 70px;
        }
        .card-stat {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        .card-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .card-stat .card-body {
            padding: 2rem;
            text-align: center;
        }
        .card-stat i {
            font-size: 3.5rem;
            margin-bottom: 1rem;
        }
        .user-image {
            width: 35px;
            height: 35px;
            object-fit: cover;
        }
    </style>
</head>
<body>

<?php if (isset($_SESSION['login'])) { ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand bg-white rounded" href="dashboard.php">
          <img src="assets/img/logo5556.png" height="40" alt="Logo">  
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link " href="user_dashboard.php"><i class="fas fa-home me-1"></i> Dashboard</a></li>
                <?php
            $StudentId = $_SESSION['stdid'];
            $sql2 = "SELECT Image FROM tblstudents WHERE StudentId = :StudentId";
            $query2 = $dbh->prepare($sql2);
            $query2->bindParam(':StudentId', $StudentId, PDO::PARAM_STR);
            $query2->execute();
            $student = $query2->fetch(PDO::FETCH_OBJ);

            $imageFile = $student->Image ?? '';
            $imagePath = "admin/" . $imageFile;
            $userImage = (!empty($imageFile) && file_exists($imagePath)) 
                         ? $imagePath 
                         : "admin/student_images/default.png";
            ?>

           <li class="nav-item d-flex align-items-center me-3">
                    <img src="<?php echo htmlentities($userImage); ?>" 
                         class="rounded-circle border border-white user-image" 
                         alt="User Image">
                </li>

                <li class="nav-item"><a class="nav-link text-danger text-uppercase fw-bold" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
<?php } ?>

<!-- Main Content -->
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center">
        <h4 class="fw-bold text-uppercase">Issued Books</h4>
        <h6 class="fw-bold text-uppercase text-primary">[ Student ID: <?php echo htmlentities($_SESSION['stdid']); ?> ]</h6>
    </div>
    <hr>

    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="dataTables-example">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Book Name</th>
                    <th>ISBN</th>
                    <th>Issued Date</th>
                    <th>Return Date</th>
                    <th>Fine (TK)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sid = $_SESSION['stdid'];
                $sql = "SELECT 
                            tblbooks.book_name,
                            tblbooks.isbn,
                            tblissuedbookdetails.IssuesDate,
                            tblissuedbookdetails.ReturnDate,
                            tblissuedbookdetails.RetrunStatus,
                            tblissuedbookdetails.fine
                        FROM tblissuedbookdetails
                        JOIN tblstudents ON tblstudents.StudentId = tblissuedbookdetails.StudentId
                        JOIN tblbooks ON tblbooks.accession_no = tblissuedbookdetails.accession_no
                        WHERE tblstudents.StudentId = :sid
                        ORDER BY tblissuedbookdetails.id DESC";

                $query = $dbh->prepare($sql);
                $query->bindParam(':sid', $sid, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                $cnt = 1;
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) {
                        ?>
                        <tr>
                            <td><?php echo htmlentities($cnt); ?></td>
                            <td><?php echo htmlentities($result->book_name); ?></td>
                            <td><?php echo htmlentities($result->isbn); ?></td>
                            <td><?php echo htmlentities($result->IssuesDate); ?></td>
                            <td>
                                <?php 
                                if ($result->ReturnDate == "") {
                                    echo "<span class='text-danger'>Not Returned Yet</span>";
                                } else {
                                    echo htmlentities($result->ReturnDate);
                                }
                                ?>
                            </td>
                            <td><?php echo htmlentities($result->fine); ?></td>
                            <td>
                                <?php 
                                echo $result->RetrunStatus == 0 
                                    ? "<span class='badge bg-danger'>Not Returned</span>" 
                                    : "<span class='badge bg-success'>Returned</span>";
                                ?>
                            </td>
                        </tr>
                <?php $cnt++; } } ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('includes/footer.php'); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-1.13.7/datatables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            }
        });
    });
</script>

</body>
</html>
