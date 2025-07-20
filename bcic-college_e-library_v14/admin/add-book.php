<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0){   
header('location:index.php');
}
else{ 
    // Get the next accession number
    $sql_d_number = "SELECT MAX(accession_no) AS max_accession_no FROM tblbooks";
    $result_d_number = mysqli_query($conn, $sql_d_number);

    if (!$result_d_number) {
        die("Error fetching max accession number: " . mysqli_error($conn));
    }

    $row_d_number = mysqli_fetch_assoc($result_d_number);
    $max_accession_no_db = ($row_d_number['max_accession_no'] ?? 0) + 1;

if (isset($_POST['add'])) {
    $entry_date = $_POST['entry_date'];
    $accession_no = $_POST['accession_no'];
    $book_name = $_POST['book_name'];
    $category = $_POST['category'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $edition = $_POST['edition'];
    $publisher = $_POST['publisher'];
    $publication_place = $_POST['publication_place'];
    $year = $_POST['year'];
    $page_no = $_POST['page_no'];
    $price = $_POST['price'];
    $source = $_POST['source'];
    $call_no = $_POST['call_no'];
    $remarks = $_POST['remarks'];

    $series = $_POST['series'];
    $volume = $_POST['volume'];
    $language = $_POST['language'];
    $self_no = $_POST['self_no'];

    $purchase_date = $_POST['purchase_date'];
    $challan_no = $_POST['challan_no'];
    $e_book_link = $_POST['e_book_link'];

    $sql = "INSERT INTO tblbooks (
                entry_date, accession_no, book_name, category_id, author_id, edition,isbn, 
                publisher, publication_place, year, page_no, price, source, call_no, remarks,series,volume,language,self_no,purchase_date,challan_no,e_book_link
            ) 
            VALUES (
                :entry_date, :accession_no, :book_name, :category, :author, :edition,:isbn, 
                :publisher, :publication_place, :year, :page_no, :price, :source, :call_no, :remarks,:series, :volume, :language, :self_no, :purchase_date, :challan_no, :e_book_link
            )";

    $query = $dbh->prepare($sql);
    $query->bindParam(':entry_date', $entry_date, PDO::PARAM_STR);
    $query->bindParam(':accession_no', $accession_no, PDO::PARAM_STR);
    $query->bindParam(':book_name', $book_name, PDO::PARAM_STR);
    $query->bindParam(':category', $category, PDO::PARAM_INT);
    $query->bindParam(':author', $author, PDO::PARAM_INT);
    $query->bindParam(':edition', $edition, PDO::PARAM_INT);
    $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
    $query->bindParam(':publisher', $publisher, PDO::PARAM_STR);
    $query->bindParam(':publication_place', $publication_place, PDO::PARAM_STR);
    $query->bindParam(':year', $year, PDO::PARAM_STR);
    $query->bindParam(':page_no', $page_no, PDO::PARAM_INT);
    $query->bindParam(':price', $price, PDO::PARAM_STR);
    $query->bindParam(':source', $source, PDO::PARAM_STR);
    $query->bindParam(':call_no', $call_no, PDO::PARAM_STR);
    $query->bindParam(':remarks', $remarks, PDO::PARAM_STR);

    $query->bindParam(':series', $series, PDO::PARAM_STR);
    $query->bindParam(':volume', $volume, PDO::PARAM_STR);
    $query->bindParam(':language', $language, PDO::PARAM_STR);
    $query->bindParam(':self_no', $self_no, PDO::PARAM_STR);

    $query->bindParam(':purchase_date', $purchase_date, PDO::PARAM_STR);
    $query->bindParam(':challan_no', $challan_no, PDO::PARAM_STR);
    $query->bindParam(':e_book_link', $e_book_link, PDO::PARAM_STR);

    $query->execute();
    $lastInsertId = $dbh->lastInsertId();

    if ($lastInsertId) {
        $_SESSION['msg'] = "Book listed successfully!";
        header('location:manage-books.php');
        exit();
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
        header('location:manage-books.php');
        exit();
    }
}

?>
 <!------MENU SECTION START-->
<?php include('includes/header.php');?>
    <div class="container">
        <div class="row">
  <!--          <div class="row mb-0 my-4">
        <div class="col-md-12">
            <h4 class="header-line text-uppercase fw-bold">Add Books</h4>
        </div>
    </div> -->
    <!-- <hr> -->
          <div class="col-sm-2"></div>
         <?php echo  $next_accession_no;?>
        <div class="col-md-8 col-sm-8 col-xs-12 ">
          <div class="card my-2">
          <div class="card-header bg-primary text-white"><i class="fas fa-book me-2"></i>
           Add Book
          </div>
  
          <div class="card-body"> 
        <form role="form" method="post">
             <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label class="form-label">Entry Date <span style="color:red;">*</span></label>
                  <input class="form-control" type="date" name="entry_date" autocomplete="off" required value="<?php echo date('Y-m-d')?>" />
                </div>
              </div>
       
            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Accession No <span style="color:red;">*</span></label>
                    <input 
                        class="form-control" 
                        type="text" 
                        name="accession_no" 
                        id="accession_no" 
                        value="<?php //echo htmlspecialchars($max_accession_no_db); ?>" 
                        autocomplete="off" 
                         
                    />
                    <span id="accession-status" style="font-size: 12px;"></span>
                </div>
            </div>
    <div class="col-md-4">
    <div class="form-group">
        <label class="form-label">ISBN/ISSN<span style="color:red;">*</span></label>
        <input list="isbns" 
               type="text" 
               class="form-control" 
               id="isbn" 
               name="isbn" 
               placeholder="Enter ISBN" 
               required>
        <datalist id="isbns">
            <?php
                $sql = "SELECT isbn FROM tblbooks";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                if ($query->rowCount() > 0) {
                    foreach ($results as $row) {
                        echo "<option value='" . htmlspecialchars($row->isbn, ENT_QUOTES, 'UTF-8') . "'>";
                    }
                }
            ?>
        </datalist>
    </div>
</div>
              
            </div>

             <div class="form-group">
            <label for="book_name" class="form-label">Book Name or Title <span style="color:red;">*</span></label>
            <input class="form-control" type="text" name="book_name" id="book_name" placeholder="Select Book Name or Title" autocomplete="on" required />
          </div>             

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
              <label for="category_name" class="form-label">Subject <span style="color:red;">*</span></label>              
              <input list="categoryList" class="form-control" id="category_name" name="category_name" placeholder="Select Subject" required oninput="setCategoryId(this.value)">              
              <datalist id="categoryList">
                <?php
                $status = 1;
                $sql = "SELECT * FROM tblcategory WHERE Status = :status";
                $query = $dbh->prepare($sql);
                $query->bindParam(':status', $status, PDO::PARAM_STR);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);

                $categoryData = [];

                if ($query->rowCount() > 0) {
                  foreach ($results as $result) {
                    $categoryData[$result->CategoryName] = $result->id;
                    echo '<option value="' . htmlentities($result->CategoryName) . '">';
                  }
                }
                ?>
              </datalist>
              <!-- Hidden input to store actual category_id -->
              <input type="hidden" name="category" id="category_id">
            </div>
              <script>
                const categoryMap = <?php echo json_encode($categoryData); ?>;

                // Create a reverse map: id => CategoryName
                const reverseCategoryMap = {};
                for (const name in categoryMap) {
                  const id = categoryMap[name];
                  reverseCategoryMap[id] = name;
                }

                function setCategoryId(categoryName) {
                  const categoryId = categoryMap[categoryName] || '';
                  document.getElementById('category_id').value = categoryId;
                }
              </script>
              <!-- <div class="form-group">
                <label for="category_id"> Subject <span style="color:red;">*</span></label>
                <select class="form-control" name="category" id="category_id" required="required">
                  <option value=""> Select Subject</option>
                  <?php 
                  $status=1;
                  $sql = "SELECT * from tblcategory where Status=:status";
                  $query = $dbh->prepare($sql);
                  $query->bindParam(':status', $status, PDO::PARAM_STR);
                  $query->execute();
                  $results = $query->fetchAll(PDO::FETCH_OBJ);
                  //if($query->rowCount() > 0){
                    //foreach($results as $result) { ?>  
                      <option value="<?php echo htmlentities($result->id);?>">
                        <?php //echo htmlentities($result->CategoryName);?>
                      </option>
                  <?php //}} ?> 
                </select>
              </div> -->
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="author_id" class="form-label">Author Name or Authority <span style="color:red;">*</span></label>
                <select class="form-control" name="author" id="author_id" required="required">
                  <option value=""> Select Author or Authority</option>
                  <?php 
                  $sql = "SELECT * from tblauthors";
                  $query = $dbh->prepare($sql);
                  $query->execute();
                  $results = $query->fetchAll(PDO::FETCH_OBJ);
                  if($query->rowCount() > 0){
                    foreach($results as $result) { ?>  
                      <option value="<?php echo htmlentities($result->id);?>">
                        <?php echo htmlentities($result->AuthorName);?>
                      </option>
                  <?php }} ?> 
                </select>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="edition" class="form-label">Edition</label>
                <input class="form-control" type="text" name="edition" id="edition" placeholder="Enter Edition" autocomplete="off" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="series" class="form-label">Series</label>
                <input class="form-control" type="text" name="series" id="series" placeholder="Enter Series" autocomplete="off" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="volume" class="form-label">Volume</label>
                <input class="form-control" type="text" name="volume" id="volume" placeholder="Enter Volume" autocomplete="on" />
              </div>
            </div>
            
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="publisher" class="form-label">Publisher</label>
                <input class="form-control" type="text" name="publisher" id="publisher" placeholder="Enter Publisher" autocomplete="off" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="publication_place" class="form-label">Publication Place</label>
                <input class="form-control" type="text" name="publication_place"  id="publication_place" autocomplete="off" placeholder="Enter Publication Place"/>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="year" class="form-label">Publication Year</label>
                <input class="form-control" type="text" name="year" id="year" autocomplete="off" placeholder="Enter Publication Year"/>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="language" class="form-label">Language</label>
                <input class="form-control" type="text" name="language" id="language" placeholder="Enter Publication Language" autocomplete="on" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="page_no" class="form-label">Page No.</label>
                <input class="form-control" type="number" name="page_no" id="page_no" placeholder="Enter Page No." autocomplete="off" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="price" class="form-label">Price</label>
                <input class="form-control" type="text" step="0.01" name="price" id="price" placeholder="Enter Price" autocomplete="off" />
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="source" class="form-label">Source</label>
                <input class="form-control" type="text" name="source" id="source" placeholder="Enter Source" autocomplete="off" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="call_no" class="form-label">Call No.</label>
                <input class="form-control" type="text" name="call_no" id="call_no" placeholder="Enter Call No." autocomplete="off" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="call_no" class="form-label">Self No./ Location</label>
                <input class="form-control" type="text" name="self_no" id="self_no" autocomplete="on" placeholder="Enter Self No./ Location"/>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label for="purchase_date" class="form-label">Purchase Date</label>
                <input class="form-control" type="date" name="purchase_date" id="purchase_date" placeholder="Enter Purchase Date" autocomplete="off" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="challan_no" class="form-label">Challan No.</label>
                <input class="form-control" type="text" name="challan_no" id="challan_no" placeholder="Enter Challan No." autocomplete="on" />
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="e_book_link" class="form-label">e-Book (Weblink)</label>
                <input class="form-control" type="text" name="e_book_link" id="e_book_link" autocomplete="on" placeholder="Enter e-Book (Weblink)"/>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="remarks">Remarks / Keyword</label>
            <textarea class="form-control" name="remarks" id="remarks" rows="1" placeholder="Enter Remarks / Keyword" autocomplete="off"></textarea>
          </div>     
        <button type="submit" name="add" class="btn btn-primary my-1 float-start"><i class="fa fa-plus"></i> Add </button>
        </form>
             </div>

            </div>
           </div>
           <div class="col-sm-2"></div>
        </div>   
    </div>
        <!-- jQuery 3.7.1 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5.3.3 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/custom.js"></script>
 
    <script type="text/javascript">
$(document).ready(function() {
    // Trigger AJAX when the ISBN field changes
    $('#isbn').on('change', function() {
        const isbn = $(this).val().trim();

        if (isbn === "") {
            // Clear all fields if no ISBN is provided
            $('#book_name, #category_id,#category_name, #author_id, #edition, #publisher, #publication_place, #year, #page_no, #price, #source, #call_no, #remarks,#series,#self_no,#language,#volume').val("");
            return;
        }

        // AJAX request to fetch book details
        $.ajax({
            url: "fetch_isbn_allbook.php",
            type: "POST",
            data: { isbn: isbn },
            dataType: "json", // Expect JSON response
            success: function(data) {
                if (data.success) {
                    // Fill all form fields with fetched data
                    $('#book_name').val(data.book_name);

                      const categoryId = data.category_id;
                      const categoryName = reverseCategoryMap[categoryId] || '';
                      $('#category_name').val(categoryName);
                      $('#category_id').val(categoryId);

                    $('#author_id').val(data.author_id);
                    $('#edition').val(data.edition);
                    $('#publisher').val(data.publisher);
                    $('#publication_place').val(data.publication_place);
                    $('#year').val(data.year);
                    $('#page_no').val(data.page_no);
                    $('#price').val(data.price);
                    $('#source').val(data.source);
                    $('#call_no').val(data.call_no);
                    $('#remarks').val(data.remarks);
                   $('#volume').val(data.volume);
                    $('#language').val(data.language);
                    $('#self_no').val(data.self_no);
                    $('#series').val(data.series);
                } else {
                    // alert("Book not found in database. Please enter details manually.");
                    // Optionally clear fields if no data found
                    // $('#book_name, #category_id, #author_id, #edition, #publisher, #publication_place, #year, #page_no, #price, #source, #call_no, #remarks').val("");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                alert("Error fetching book details. Please try again.");
            }
        });
    });
});
</script>
<!-- // for accession no -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    $('#accession_no').on('keyup blur', function() {
      let accession = $(this).val();
      if (accession.length > 0) {
        $.ajax({
          type: 'POST',
          url: 'check_accession.php',
          data: { accession_no: accession },
          success: function(response) {
            $('#accession-status').html(response);
          }
        });
      } else {
        $('#accession-status').html('');
      }
    });
  });
</script>
     <!-- CONTENT-WRAPPER SECTION END-->
  <?php include('includes/footer.php');?>
</body>
</html>
<?php } ?>
