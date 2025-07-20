<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
date_default_timezone_set('Asia/Dhaka');
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0){   
   header('location:../index.php');
} else { 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>Online Library Management System | Manage Issued Books</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        .book-author {
            color: #28a745;
            font-size: 0.9em;
        }
        .badge-not-returned {
            background-color: #dc3545;
        }
        .badge-returned {
            background-color: #28a745;
        }
    </style>
</head>
<body>
<?php include('includes/header.php');?>

<div class="content-wrapper">
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-book-open me-2"></i>Yet Not Return Books</h4>
                    </div>
                    <div class="card-body">
                        <!-- Alert Messages -->
                        <?php if($_SESSION['error']!="") { ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <strong><i class="fas fa-exclamation-circle me-2"></i>Error:</strong> 
                                <?php echo htmlentities($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <?php echo htmlentities($_SESSION['error']=""); ?>
                            </div>
                        <?php } ?>
                        <?php if($_SESSION['msg']!="") { ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <strong><i class="fas fa-check-circle me-2"></i>Success:</strong> 
                                <?php echo htmlentities($_SESSION['msg']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <?php echo htmlentities($_SESSION['msg']=""); ?>
                            </div>
                        <?php } ?>
                        <?php if($_SESSION['delmsg']!="") { ?>
                            <div class="alert alert-success alert-dismissible fade show">
                                <strong><i class="fas fa-check-circle me-2"></i>Success:</strong> 
                                <?php echo htmlentities($_SESSION['delmsg']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                <?php echo htmlentities($_SESSION['delmsg']=""); ?>
                            </div>
                        <?php } ?>

                        <!-- Issued Books Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="issuedBooksTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Student ID</th>
                                        <th>Student Name</th>
                                        <th>Mobile No.</th>
                                        <th>Book Details</th>
                                        <th>Accession No.</th>
                                        <th>ISBN</th>
                                        <th>Issued Date</th>
                                        <th>Return Date</th>
                                        <th>Status</th>
                                        <th>Fine</th>
                                        <!-- <th>Actions</th> -->
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $sql = "SELECT DISTINCT
                                        tblstudents.StudentId,
                                        tblstudents.FullName,
                                        tblstudents.MobileNumber,
                                        tblbooks.book_name,
                                        tblbooks.accession_no,
                                        tblbooks.isbn,
                                        tblauthors.AuthorName, 
                                        tblbooks.edition,
                                        tblissuedbookdetails.IssuesDate,
                                        tblissuedbookdetails.ReturnDate,
                                        tblissuedbookdetails.RetrunStatus,
                                        tblissuedbookdetails.fine,
                                        tblissuedbookdetails.id as rid
                                    FROM tblissuedbookdetails 
                                    JOIN tblstudents ON tblissuedbookdetails.StudentId = tblstudents.StudentId
                                    JOIN tblbooks ON tblissuedbookdetails.accession_no = tblbooks.accession_no
                                    JOIN tblauthors ON tblbooks.author_id = tblauthors.id
                                    AND tblissuedbookdetails.RetrunStatus=0
                                    ORDER BY tblissuedbookdetails.id DESC";
                                $query = $dbh->prepare($sql);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                if($query->rowCount() > 0){
                                    foreach($results as $result) {
                                ?>
                                    <tr>
                                        <td><?php echo htmlentities($cnt);?></td>
                                        <td><?php echo htmlentities($result->StudentId);?></td>
                                        <td><?php echo htmlentities($result->FullName);?></td>
                                        <td><?php echo htmlentities($result->MobileNumber);?></td>
                                        <td>
                                            <strong><?php echo htmlentities($result->book_name); ?></strong>
                                            <div class="book-author">
                                                <?php echo htmlentities('by ' . $result->AuthorName . ' (' . $result->edition . ')'); ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlentities($result->accession_no);?></td>
                                        <td><?php echo htmlentities($result->isbn);?></td>
                                        <td><?php echo htmlentities($result->IssuesDate);?></td>
                                        <td>
                                            <?php 
                                            if($result->ReturnDate==""){
                                                echo '<span class="text-danger">Not Returned Yet</span>';
                                            } else {
                                                echo htmlentities($result->ReturnDate);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if($result->RetrunStatus==0){
                                                echo '<span class="badge bg-danger">Not Returned</span>';
                                            } else {
                                                echo '<span class="badge bg-success">Returned</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                            if($result->fine==0){
                                                echo '<span class="text-success">৳0.00</span>';
                                            } else {
                                                echo '<span class="text-danger">৳' . htmlentities($result->fine) . '</span>';
                                            }
                                            ?>
                                        </td>
                                       <!--  <td>
                                            <div class="d-flex gap-2">
                                                <a href="update-issue-bookdeails.php?rid=<?php echo htmlentities($result->rid);?>" 
                                                   class="btn btn-sm btn-primary" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="re-issue-bookdeails.php?rid=<?php echo htmlentities($result->rid);?>" 
                                                   class="btn btn-sm btn-warning" 
                                                   title="Re-Issue">
                                                    <i class="fas fa-redo"></i>
                                                </a>
                                            </div>
                                        </td> -->
                                    </tr>
                                <?php 
                                        $cnt++;
                                    }
                                } 
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include('includes/footer.php');?>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- DataTables Export Buttons -->
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<!-- JSZip for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- PDFMake for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
    $(document).ready(function() {
        $('#issuedBooksTable').DataTable({
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search...",
            },
            // dom: '<"top"Bf>rt<"bottom"lip><"clear">',
            dom: '<"row"<"col-md-6"B><"col-md-6"f>>rt<"bottom"lip><"clear">',
            buttons: [
                {
                    extend: 'copy',
                    text: '<i class="fas fa-copy fa-xs"></i> Copy',
                    className: 'btn btn-secondary',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'csv',
                    text: '<i class="fas fa-file-csv fa-xs"></i> CSV',
                    className: 'btn btn-info',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: 'Issued_Books_List'
                },
                {
                    extend: 'excel',
                    text: '<i class="fas fa-file-excel fa-xs"></i> Excel',
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: 'Issued_Books_List'
                },
                {
                    extend: 'pdf',
                    text: '<i class="fas fa-file-pdf fa-xs"></i> PDF',
                    className: 'btn btn-danger',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: 'Issued_Books_List',
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 8;
                        doc.styles.tableHeader.fontSize = 9;
                        doc.pageMargins = [20, 20, 20, 20];
                    }
                },
                {
                    extend: 'print',
                    text: '<i class="fas fa-print fa-xs"></i> Print',
                    className: 'btn btn-warning',
                    exportOptions: {
                        columns: ':visible'
                    },
                    title: 'Issued Books List'
                }
            ],
            pageLength: 25,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]]
        });
    });
</script>

</body>
</html>
<?php } ?>