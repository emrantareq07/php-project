<?php
session_start();  
require_once("config/config.php");
require_once("db/db.php");

if (isset($_SESSION["uid"]) || isset($_COOKIE['user_login'])) { 
    include_once(ROOT_PATH.'/libs/function.php');
    $usercredentials = new DB_con();

    $uname = "";
    if (isset($_SESSION["uname"])) {
        $uname = $_SESSION['uname'];
    } elseif (isset($_COOKIE['user_login'])) {
        $uname = $_COOKIE['user_login'];
    }

    $uname = mysqli_real_escape_string($conn, $uname);
    $query = "SELECT * FROM tblusers WHERE Username = '$uname'";
    $result = $usercredentials->runBaseQuery($query);

    foreach ($result as $row) {
        $uun = $row['Username'];
        $uup = $row['Password'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notice Board</title>
        <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container" style="margin-top:30px">
    <div class="row">
        <div class="col-lg-4 col-md-4 col-12">
            <form method="post" enctype="multipart/form-data">
                <?php
                if (isset($_POST['submit'])) {
                    $notice = mysqli_real_escape_string($conn, $_POST['notice']);
                    $date = mysqli_real_escape_string($conn, $_POST['date']);

                    if (isset($_FILES['pdf_file']['name'])) {
                        if (is_uploaded_file($_FILES["pdf_file"]["tmp_name"]) && ($_FILES["pdf_file"]["type"] === 'application/pdf')) {
                            if ($_FILES['pdf_file']['size'] > 1000000) {
                                echo '<div class="alert alert-danger alert-dismissible fade show text-center">
                                        <a class="close" data-dismiss="alert" aria-label="close">×</a>
                                        <strong>Failed!</strong> File Size too Large!
                                      </div>';
                            } else {
                                $file_name = basename($_FILES['pdf_file']['name']);
                                $file_tmp = $_FILES['pdf_file']['tmp_name'];
                                move_uploaded_file($file_tmp, "./pdf/" . $file_name);

                                $insertquery = "INSERT INTO notice_board(notice, filename, dated) VALUES('$notice', '$file_name', '$date')";
                                $iquery = mysqli_query($conn, $insertquery);

                                if ($iquery) {
                                    echo '<div class="alert alert-success alert-dismissible fade show text-center">
                                            <a class="close" data-dismiss="alert" aria-label="close">×</a>
                                            <strong>Success!</strong> Data submitted successfully.
                                          </div>';
                                } else {
                                    echo '<div class="alert alert-danger alert-dismissible fade show text-center">
                                            <a class="close" data-dismiss="alert" aria-label="close">×</a>
                                            <strong>Failed!</strong> Try Again!
                                          </div>';
                                }
                            }
                        } else {
                            echo '<div class="alert alert-danger alert-dismissible fade show text-center">
                                    <a class="close" data-dismiss="alert" aria-label="close">×</a>
                                    <strong>Failed!</strong> File must be uploaded in PDF format!
                                  </div>';
                        }
                    }
                }
                ?>
                <div class="card">
                    <div class="card-header bg-success text-center text-white">
                        <h4 class="card-title">Notice Board</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="notice">Notice Title</label>
                            <textarea class="form-control" placeholder="Enter Notice Title" rows="3" name="notice" required></textarea>
                        </div>
                        <div class="form-group mb-2">
                            <label for="date">Date</label>
                            <input type="date" name="date" class="form-control" required />
                        </div>
                        <div class="form-group mb-2">
                            <input type="file" name="pdf_file" class="form-control" accept=".pdf" required />
                        </div>
                        <div class="form-group mb-2">
                            <input type="submit" class="btn btn-primary form-control" name="submit" value="Submit">
                        </div>
                        <a href="dashboard.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Back
                            </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-lg-8 col-md-8 col-12">
            <div class="card">
                <div class="card-header text-center">
                    <h4>Notice List</h4>
                </div>
                <div class="card-body">
                    <table class="table table-responsive table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Notice</th>
                                <th>FileName</th>
                                <th colspan="3" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $selectQuery = "SELECT * FROM notice_board ORDER BY id DESC";
                        $squery = mysqli_query($conn, $selectQuery);

                        while ($row = mysqli_fetch_assoc($squery)) {
                            $id = $row['id'];
                            echo "<tr>
						    <td>" . htmlspecialchars($id) . "</td>
						    <td>" . htmlspecialchars($row['notice']) . "</td>
						    <td>" . htmlspecialchars($row['filename']) . "</td>
						    <td>
						        <a target='_blank' href='pdf_view.php?id=" . urlencode($row['id']) . "' class='btn btn-info btn-sm mb-1'>View</a><br>
						        <a href='pdf/" . htmlspecialchars($row['filename']) . "' target='_blank'>" . htmlspecialchars($row['filename']) . "</a>
						    </td>
						    <td>
						        <a href='edit_notice.php?id=$id' class='btn btn-warning btn-sm'>
						            <i class='fas fa-edit'></i> Edit
						        </a>
						    </td>
						    <td>
						        <button onclick='confirmDelete($id)' class='btn btn-danger btn-sm'>
						            <i class='fas fa-trash'></i> Delete
						        </button>
						    </td>
						</tr>";

                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this notice? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a id="deleteLink" href="#" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- JS Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function confirmDelete(id) {
        document.getElementById('deleteLink').href = 'delete_notice.php?id=' + id;
        var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
        modal.show();
    }
</script>
</body>
</html>
<?php 
} else {
    header('Location: login.php');
    exit;
}
?>
