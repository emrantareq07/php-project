<?php
session_name('bcic_e-library');
session_start();
error_reporting(0);
date_default_timezone_set('Asia/Dhaka');
include('includes/config.php');
include('includes/db.php');

if(strlen($_SESSION['alogin'])==0) {   
    header('location:index.php');
    exit;
}

if(isset($_POST['return'])) {
    $rid = intval($_GET['rid']);
    $fine = $_POST['fine'];
    $ReturnDate = $_POST['ReturnDate'];
    $RetrunStatus = 1;
    
    // Get the accession_no before updating
    $sql_get = "SELECT accession_no FROM tblissuedbookdetails WHERE id=:rid";
    $query_get = $dbh->prepare($sql_get);
    $query_get->bindParam(':rid', $rid, PDO::PARAM_INT);
    $query_get->execute();
    $result = $query_get->fetch(PDO::FETCH_OBJ);
    $accession_no = $result->accession_no;
    
    // Start transaction
    $dbh->beginTransaction();
    
    try {
        // Update issued book details
        $sql = "UPDATE tblissuedbookdetails SET ReturnDate=:ReturnDate, fine=:fine, RetrunStatus=:RetrunStatus WHERE id=:rid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':rid', $rid, PDO::PARAM_INT);
        $query->bindParam(':fine', $fine, PDO::PARAM_STR);
        $query->bindParam(':ReturnDate', $ReturnDate, PDO::PARAM_STR);
        $query->bindParam(':RetrunStatus', $RetrunStatus, PDO::PARAM_INT);
        $query->execute();
        
        // Update book status to available
        $updateSql = "UPDATE tblbooks SET status = 0 WHERE accession_no = :accession_no";
        $updateQuery = $dbh->prepare($updateSql);
        $updateQuery->bindParam(':accession_no', $accession_no, PDO::PARAM_STR);
        $updateQuery->execute();
        
        // Commit transaction
        $dbh->commit();
        
        $_SESSION['msg'] = "Book Returned successfully!!!";
        header('location:manage-issued-books.php');
        exit;
    } catch (PDOException $e) {
        // Rollback transaction on error
        $dbh->rollBack();
        $_SESSION['error'] = "Something went wrong. Please try again. Error: " . $e->getMessage();
        header('location:manage-issued-books.php');
        exit;
    }
}
?>

    <!-- MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->    
    
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="fw-bold">Issued Book Details</h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="manage-issued-books.php">Issued Books</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Book Details</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="fa fa-book me-2"></i> Issued Book Details</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" class="needs-validation" novalidate>
                            <?php 
                            $rid = intval($_GET['rid']);
                            $sql_fetch = "SELECT tblstudents.StudentId, tblstudents.FullName, tblbooks.book_name, tblbooks.isbn, 
                                         tblissuedbookdetails.IssuesDate, tblissuedbookdetails.accession_no, 
                                         tblissuedbookdetails.ReturnDate, tblissuedbookdetails.id as rid, 
                                         tblissuedbookdetails.fine, tblissuedbookdetails.RetrunStatus 
                                         FROM tblissuedbookdetails 
                                         JOIN tblstudents ON tblstudents.StudentId = tblissuedbookdetails.StudentId 
                                         JOIN tblbooks ON tblbooks.accession_no = tblissuedbookdetails.accession_no 
                                         WHERE tblissuedbookdetails.id = :rid";
                            
                            $query = $dbh->prepare($sql_fetch);
                            $query->bindParam(':rid', $rid, PDO::PARAM_INT);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                            
                            if($query->rowCount() > 0) {
                                foreach($results as $result) {               
                            ?>                                      
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Student ID</label>
                                    <div class="form-control bg-light"><?php echo htmlentities($result->StudentId); ?></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Student Name</label>
                                    <div class="form-control bg-light"><?php echo htmlentities($result->FullName); ?></div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Book Name</label>
                                    <div class="form-control bg-light"><?php echo htmlentities($result->book_name); ?></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Accession No</label>
                                    <div class="form-control bg-light"><?php echo htmlentities($result->accession_no); ?></div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">ISBN</label>
                                    <div class="form-control bg-light"><?php echo htmlentities($result->isbn); ?></div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Book Issued Date</label>
                                    <div class="form-control bg-light"><?php echo htmlentities($result->IssuesDate); ?></div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="ReturnDate" class="form-label fw-bold">Book Returned Date</label>
                                    <input type="date" class="form-control" name="ReturnDate" id="ReturnDate" 
                                        value="<?php echo $result->ReturnDate ? htmlentities(date('Y-m-d', strtotime($result->ReturnDate))) : htmlentities(date('Y-m-d')); ?>" required />
                                    <div class="invalid-feedback">Please select a return date</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="fine" class="form-label fw-bold">Fine (in TK)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" class="form-control" name="fine" id="fine" 
                                            value="<?php echo $result->fine ? htmlentities($result->fine) : '0'; ?>" min="0" step="0.01" required />
                                        <div class="invalid-feedback">Please enter a valid fine amount</div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <a href="manage-issued-books.php" class="btn btn-outline-dark">
                                    <i class="fa fa-arrow-left me-2"></i> Back to List
                                </a>
                                <?php if($result->RetrunStatus == 0) { ?>
                                    <button type="submit" name="return" class="btn btn-primary">
                                        <i class="fa fa-check-circle me-2"></i> Return Book
                                    </button>
                                <?php } else { ?>
                                    <div class="alert alert-success mb-0">
                                        <i class="fa fa-check-circle me-2"></i> This book has already been returned.
                                    </div>
                                <?php } ?>
                            </div>
                            
                            <?php 
                                } // end foreach
                            } // end if
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER SECTION -->
    <?php include('includes/footer.php');?>
    
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Scripts -->
    <script src="assets/js/custom.js"></script>
    <script>
        // Form validation
        (function () {
            'use strict'
            
            var forms = document.querySelectorAll('.needs-validation')
            
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }
                        
                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    </script>
</body>
</html>