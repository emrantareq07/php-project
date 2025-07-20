<?php
session_name('bcic_e-library');
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0){   
    header('location:index.php');
}
else { 
    if(isset($_GET['del'])){
        $id=$_GET['del'];
        $sql = "delete from tblbooks WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query -> bindParam(':id',$id, PDO::PARAM_STR);
        $query -> execute();
        $_SESSION['delmsg']="Book deleted successfully";
        header('location:manage-books.php');
    }
?>

    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->    
        <div class="container">
            <div class="row ">
                <div class="col-md-12 mt-4">
                    <h4 class="text-uppercase fw-bold">Manage Books</h4>
                </div>
                <hr>
                <!-- Display Messages -->
                <div class="row">
                    <?php if($_SESSION['error']!="") { ?>
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <strong>Error :</strong> 
                                <?php echo htmlentities($_SESSION['error']);?>
                                <?php echo htmlentities($_SESSION['error']="");?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if($_SESSION['msg']!="") { ?>
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <strong>Success :</strong> 
                                <?php echo htmlentities($_SESSION['msg']);?>
                                <?php echo htmlentities($_SESSION['msg']="");?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if($_SESSION['updatemsg']!="") { ?>
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <strong>Success :</strong> 
                                <?php echo htmlentities($_SESSION['updatemsg']);?>
                                <?php echo htmlentities($_SESSION['updatemsg']="");?>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if($_SESSION['delmsg']!="") { ?>
                        <div class="col-md-12">
                            <div class="alert alert-success">
                                <strong>Success :</strong> 
                                <?php echo htmlentities($_SESSION['delmsg']);?>
                                <?php echo htmlentities($_SESSION['delmsg']="");?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
            
            <!-- Add New Book Button -->
            <!-- <div class="row mb-4">
                <div class="col-md-12 text-end">
                    <a href="add-book.php" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add New Book
                    </a>
                </div>
            </div> -->
            
          <!-- Card View Toggle -->
        <div class="row mb-3">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <div class="btn-group" role="group">
                    <button type="button" id="cardViewBtn" class="btn btn-outline-primary active">
                        <i class="fas fa-th-large"></i> Card View
                    </button>
                    <button type="button" id="tableViewBtn" class="btn btn-outline-primary">
                        <i class="fas fa-table"></i> Table View
                    </button>
                </div>
                <a href="add-book.php" class="btn btn-success">
                    <i class="fas fa-plus"></i> Add New Book
                </a>
            </div>
        </div>

            
            <!-- Cards Container -->
            <div id="cardsContainer">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4" id="booksGrid">
                    <?php 
                    $sql = "SELECT tblbooks.*, tblcategory.CategoryName, tblauthors.AuthorName 
                            FROM tblbooks 
                            JOIN tblcategory ON tblcategory.id = tblbooks.category_id 
                            JOIN tblauthors ON tblauthors.id = tblbooks.author_id";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results = $query->fetchAll(PDO::FETCH_OBJ);
                    $cnt = 1;
                    if($query->rowCount() > 0) {
                        foreach($results as $result) {               
                    ?>                                      
                    <div class="col">
                        <div class="card h-100 shadow-sm book-card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0"><?php echo htmlentities($result->book_name);?></h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <strong>Author:</strong> <?php echo htmlentities($result->AuthorName);?>
                                </div>
                                <div class="mb-2">
                                    <strong>Category:</strong> <?php echo htmlentities($result->CategoryName);?>
                                </div>
                                <div class="mb-2">
                                    <strong>ISBN:</strong> <?php echo htmlentities($result->isbn);?>
                                </div>
                                <div class="mb-2">
                                    <strong>Publisher:</strong> <?php echo htmlentities($result->publisher);?>
                                </div>
                                <div class="mb-2">
                                    <strong>Year:</strong> <?php echo htmlentities($result->year);?>
                                </div>
                                <div class="mb-2">
                                    <strong>Price:</strong> ৳ <?php echo htmlentities($result->price);?>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent">
                                <div class="d-flex justify-content-between">
                                    <a href="edit-book.php?bookid=<?php echo htmlentities($result->id);?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="manage-books.php?del=<?php echo htmlentities($result->id);?>" 
                                       onclick="return confirm('Are you sure you want to delete this book?');" 
                                       class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    <a href="book-details.php?bookid=<?php echo htmlentities($result->id); ?>" 
                                           class="btn btn-sm btn-info view-book-details" 
                                           data-bookid="<?php echo htmlentities($result->id); ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                        $cnt = $cnt + 1;
                        }
                    } else {
                    ?>
                    <div class="col-12">
                        <div class="alert alert-warning">No books found in the database.</div>
                    </div>
                    <?php } ?>
                </div>
            </div>

                        
            <!-- Table Container (Hidden by Default) -->
            <div id="tableContainer" class="d-none">
                <div class="table-responsive">
                    <table id="booksTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Accession No.</th>
                                <th>Book Name</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>ISBN</th>
                                <th>Publisher</th>
                                <th>Year</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if($query->rowCount() > 0) {
                                foreach($results as $result) {    
                                  
                            ?>                                      
                            <tr>
                                <td><?php echo htmlentities($cnt);?></td>
                                <td><?php echo htmlentities($result->accession_no);?></td>
                                <td>
                                  <?php 
                                    echo htmlentities($result->book_name); 
                                    if (!empty($result->e_book_link)) {
                                        echo ' (<a href="' . htmlentities($result->e_book_link) . '" target="_blank">e-link</a>)';
                                    }
                                  ?>
                                </td>
                                <td><?php echo htmlentities($result->AuthorName);?></td>
                                <td><?php echo htmlentities($result->CategoryName);?></td>
                                <td><?php echo htmlentities($result->isbn);?></td>
                                <td><?php echo htmlentities($result->publisher);?></td>
                                <td><?php echo htmlentities($result->year);?></td>
                                <td>৳ <?php echo htmlentities($result->price);?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="edit-book.php?bookid=<?php echo htmlentities($result->id);?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="manage-books.php?del=<?php echo htmlentities($result->id);?>" 
                                           onclick="return confirm('Are you sure you want to delete this book?');" 
                                           class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <!-- <a href="book-details.php?bookid=<?php echo htmlentities($result->id);?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a> -->
                                        <a href="book-details.php?bookid=<?php echo htmlentities($result->id); ?>" 
                                           class="btn btn-sm btn-info view-book-details" 
                                           data-bookid="<?php echo htmlentities($result->id); ?>">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                    </div>
                                </td>
                            </tr>
                            <?php 
                                $cnt = $cnt + 1;
                                }
                            } 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>       
           

   <!-- Book Details Modal -->
<div class="modal fade" id="bookDetailsModal" tabindex="-1" role="dialog" aria-labelledby="bookDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="bookDetailsModalLabel">Book Details</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loadingIndicator" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Loading book details...</p>
                </div>
                <div class="table-responsive" id="bookDetailsTable" style="display:none;">
                    <table class="table table-bordered table-hover">
                        <tbody id="bookDetailsContent">
                            <!-- Content will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>
                <div id="errorMessage" class="alert alert-danger" style="display:none;"></div>
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-close"></i> Close</button>
                    <button type="button" class="btn btn-danger" id="printBookDetails"><i class="fas fa-print"></i> Print</button>
                </div>

            <script type="text/javascript">
            $('#printBookDetails').click(function() {
                // Create a simple text-only print window
                var printWindow = window.open('', '_blank', 'fullscreen=yes');
                
                // Get just the text content from the table
                var tableContent = $('#bookDetailsContent').clone();
                tableContent.find('th, td').each(function() {
                    $(this).html($(this).text()); // Strip any HTML formatting
                });
                
                // Create simple text-based output
                var printContent = '<pre style="font-family:monospace;padding:20px;">';
                printContent += 'BOOK DETAILS\n';
                printContent += '=============\n\n';
                
                // Convert table to text format
                tableContent.find('tr').each(function() {
                    var th = $(this).find('th').text().trim();
                    var td = $(this).find('td').text().trim();
                    printContent += th + ': ' + td + '\n';
                });
                
                printContent += '</pre>';
                
                // Write to print window
                printWindow.document.write('<html><head><title>Book Details</title></head>');
                printWindow.document.write('<body style="margin:20px;">');
                printWindow.document.write(printContent);
                printWindow.document.write('<script>');
                printWindow.document.write('window.onafterprint = function(){window.close();};');
                printWindow.document.write('setTimeout(function(){window.print();},200);');
                printWindow.document.write('<\/script>');
                printWindow.document.write('</body></html>');
                printWindow.document.close();
            });

            </script>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('.view-book-details').click(function(e) {
        e.preventDefault();
        var bookId = $(this).data('bookid');
        
        // Show modal and loading state
        $('#bookDetailsModal').modal('show');
        $('#loadingIndicator').show();
        $('#bookDetailsTable').hide();
        $('#errorMessage').hide();
        
        $.ajax({
            url: 'book-details.php?bookid=' + bookId,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#loadingIndicator').hide();
                
                if(response.status === 'success') {
                    // Format empty values
                    function formatValue(value) {
                        return (value === '' || value === null || value === undefined || value === '0') ? 'N/A' : value;
                    }
                    
                    // Format date
                    function formatDate(dateString) {
                        if (!dateString || dateString === '0000-00-00') return 'N/A';
                        var date = new Date(dateString);
                        return date.toLocaleDateString();
                    }
                    
                    // Format status
                    function formatStatus(status) {
                        return status == 1 ? 'Active' : 'Inactive';
                    }
                    
                    // Build the table content
                    var content = `
                        <tr><th width="30%">ID</th><td>${formatValue(response.data.id)}</td></tr>
                        <tr><th>Entry Date</th><td>${formatDate(response.data.entry_date)}</td></tr>
                        <tr><th>Accession No</th><td>${formatValue(response.data.accession_no)}</td></tr>
                        <tr><th>Book Name</th><td>${formatValue(response.data.book_name)}</td></tr>
                        <tr><th>Category</th><td>${formatValue(response.data.category_id)}</td></tr>
                        <tr><th>Author</th><td>${formatValue(response.data.author_id)}</td></tr>
                        <tr><th>Edition</th><td>${formatValue(response.data.edition)}</td></tr>
                        <tr><th>ISBN</th><td>${formatValue(response.data.isbn)}</td></tr>
                        <tr><th>Publisher</th><td>${formatValue(response.data.publisher)}</td></tr>
                        <tr><th>Publication Place</th><td>${formatValue(response.data.publication_place)}</td></tr>
                        <tr><th>Year</th><td>${formatValue(response.data.year)}</td></tr>
                        <tr><th>Page No</th><td>${formatValue(response.data.page_no)}</td></tr>
                        <tr><th>Price</th><td>${response.data.price ? '₹' + response.data.price : 'N/A'}</td></tr>
                        <tr><th>Source</th><td>${formatValue(response.data.source)}</td></tr>
                        <tr><th>Call No</th><td>${formatValue(response.data.call_no)}</td></tr>
                        <tr><th>Remarks</th><td>${formatValue(response.data.remarks)}</td></tr>
                        <tr><th>Registration Date</th><td>${formatDate(response.data.reg_date)}</td></tr>
                        <tr><th>Update Date</th><td>${formatDate(response.data.updation_date)}</td></tr>
                        
                        <tr><th>Series</th><td>${formatValue(response.data.series)}</td></tr>
                        <tr><th>Volume</th><td>${formatValue(response.data.volume)}</td></tr>
                        <tr><th>Language</th><td>${formatValue(response.data.language)}</td></tr>
                        <tr><th>Shelf No</th><td>${formatValue(response.data.self_no)}</td></tr>

                        <tr><th>Purchase Date</th><td>${formatValue(response.data.purchase_date)}</td></tr>
                        <tr><th>Chanllan No.</th><td>${formatValue(response.data.challan_no)}</td></tr>
                        <tr><th>Web Link</th><td>${formatValue(response.data.e_book_link)}</td></tr>
                    `;
                    
                    $('#bookDetailsContent').html(content);
                    $('#bookDetailsTable').show();
                } else {
                    $('#errorMessage').text(response.message || 'Error loading book details').show();
                }
            },
            error: function(xhr, status, error) {
                $('#loadingIndicator').hide();
                $('#errorMessage').text('Error connecting to server: ' + error).show();
            }
        });
    });
});
</script>
 
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    
    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- BOOTSTRAP 5 JS BUNDLE WITH POPPER -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DATATABLES JS -->
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.6/datatables.min.js"></script>
    <!-- CUSTOM SCRIPTS -->
    <script src="assets/js/custom.js"></script>
    
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#booksTable').DataTable({
            responsive: true,
            dom: '<"top"lf>rt<"bottom"ip>',
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search books...",
            }
        });
        
        // Toggle between card and table view
        $('#cardViewBtn').click(function() {
            $('#cardsContainer').removeClass('d-none');
            $('#tableContainer').addClass('d-none');
            $(this).addClass('active');
            $('#tableViewBtn').removeClass('active');
        });
        
        $('#tableViewBtn').click(function() {
            $('#cardsContainer').addClass('d-none');
            $('#tableContainer').removeClass('d-none');
            $(this).addClass('active');
            $('#cardViewBtn').removeClass('active');
        });
        
        // Make cards searchable through DataTables
        $('#booksTable_filter input').on('keyup', function() {
            var searchTerm = this.value.toLowerCase();
            if ($('#cardsContainer').is(':visible')) {
                $('.book-card').each(function() {
                    var cardText = $(this).text().toLowerCase();
                    if (cardText.indexOf(searchTerm) > -1) {
                        $(this).closest('.col').show();
                    } else {
                        $(this).closest('.col').hide();
                    }
                });
            }
        });
    });
    </script>
</body>
</html>
<?php } ?>