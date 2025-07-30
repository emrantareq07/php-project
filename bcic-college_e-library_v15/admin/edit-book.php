<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0){   
    header('location:../index.php');
}
else {
    if(isset($_POST['update'])){
        $bookname = $_POST['bookname'];
        $entry_date = $_POST['entry_date'];
        $category = $_POST['category'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];
        $publisher = $_POST['publisher'];
        $publication_place = $_POST['publication_place'];
        $year = $_POST['year'];
        $page_no = $_POST['page_no'];
        $price = $_POST['price'];
        $source = $_POST['source'];
        $call_no = $_POST['call_no'];
        $remarks = $_POST['remarks'];
        $accession_no = $_POST['accession_no'];
        
        $bookid = intval($_GET['bookid']);
        $edition = $_POST['edition'];
        $series = $_POST['series'];
        $volume = $_POST['volume'];
        $language = $_POST['language'];
        $self_no = $_POST['self_no'];
        $purchase_date = $_POST['purchase_date'];
        $challan_no = $_POST['challan_no'];
        $e_book_link = $_POST['e_book_link'];
        
        $sql = "UPDATE tblbooks SET 
                entry_date = :entry_date,
                book_name = :bookname,
                category_id = :category,
                author_id = :author,
                isbn = :isbn,
                publisher = :publisher,
                publication_place = :publication_place,
                year = :year,
                page_no = :page_no,
                price = :price,
                source = :source,
                call_no = :call_no,
                remarks = :remarks,
                accession_no = :accession_no,
                edition = :edition,
                series = :series,
                volume = :volume,
                language = :language,
                self_no = :self_no,
                purchase_date = :purchase_date,
                challan_no = :challan_no,
                e_book_link = :e_book_link,
                updation_date = CURRENT_TIMESTAMP
                WHERE id = :bookid";
                
        $query = $dbh->prepare($sql);
        $query->bindParam(':entry_date', $entry_date, PDO::PARAM_STR);
        $query->bindParam(':bookname', $bookname, PDO::PARAM_STR);
        $query->bindParam(':category', $category, PDO::PARAM_STR);
        $query->bindParam(':author', $author, PDO::PARAM_STR);
        $query->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $query->bindParam(':publisher', $publisher, PDO::PARAM_STR);
        $query->bindParam(':publication_place', $publication_place, PDO::PARAM_STR);
        $query->bindParam(':year', $year, PDO::PARAM_STR);
        $query->bindParam(':page_no', $page_no, PDO::PARAM_STR);
        $query->bindParam(':price', $price, PDO::PARAM_STR);
        $query->bindParam(':source', $source, PDO::PARAM_STR);
        $query->bindParam(':call_no', $call_no, PDO::PARAM_STR);
        $query->bindParam(':remarks', $remarks, PDO::PARAM_STR);
        $query->bindParam(':accession_no', $accession_no, PDO::PARAM_STR);
        $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
        $query->bindParam(':edition', $edition, PDO::PARAM_STR);
        $query->bindParam(':series', $series, PDO::PARAM_STR);
        $query->bindParam(':volume', $volume, PDO::PARAM_STR);
        $query->bindParam(':language', $language, PDO::PARAM_STR);
        $query->bindParam(':self_no', $self_no, PDO::PARAM_STR);

        $query->bindParam(':purchase_date', $purchase_date, PDO::PARAM_STR);
        $query->bindParam(':challan_no', $challan_no, PDO::PARAM_STR);
        $query->bindParam(':e_book_link', $e_book_link, PDO::PARAM_STR);
        
        $query->execute();
        $_SESSION['msg'] = "Book info updated successfully!!!";
        header('location:manage-books.php');
    }
?>
 <?php //include('includes/navbar.php');?> 
    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    <div class="container-fluid">
        <div class="container">
<!--             <div class="row mb-0 my-2">
                <div class="col-md-12">
                    <h4 class="header-line text-uppercase fw-bold">Edit Book</h4>
                </div>
            </div>
            <hr> -->
            <div class="row justify-content-center my-2">
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-edit me-1"></i>Edit Book Info</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" method="post">
                                <?php 
                                $bookid = intval($_GET['bookid']);
                                $sql = "SELECT tblbooks.*, tblcategory.CategoryName, tblcategory.id as cid, 
                                        tblauthors.AuthorName, tblauthors.id as athrid 
                                        FROM tblbooks 
                                        JOIN tblcategory ON tblcategory.id = tblbooks.category_id 
                                        JOIN tblauthors ON tblauthors.id = tblbooks.author_id 
                                        WHERE tblbooks.id = :bookid";
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':bookid', $bookid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);
                                $cnt = 1;
                                if($query->rowCount() > 0) {
                                    foreach($results as $result) { 
                                ?>  
    <div class="row">
    <div class="col-md-4">        
            <label class="form-label">Entry Date <span style="color:red;">*</span></label>
            <input class="form-control" type="date" name="entry_date"  
                   value="<?php echo htmlentities(date('Y-m-d', strtotime($result->entry_date))); ?>" />

        </div>

        <div class="col-md-4">
            <label class="form-label">Accession No <span class="text-danger">*</span></label>
            <input class="form-control" type="text" name="accession_no" 
                   value="<?php echo htmlentities($result->accession_no); ?>" required />
        </div>

        <div class="col-md-4">
            <label class="form-label">ISBN <span class="text-danger">*</span></label>
            <input class="form-control" type="text" name="isbn" 
                   value="<?php echo htmlentities($result->isbn); ?>" required />
            <!-- <div class="form-text">An ISBN is an International Standard Book Number. Must be unique.</div> -->
        </div>
    </div>

        <div class="mb-3">
            <label class="form-label">Book Name or Title <span class="text-danger">*</span></label>
            <input class="form-control" type="text" name="bookname" 
                   value="<?php echo htmlentities($result->book_name); ?>" required />
        </div>

           <div class="row">
            <div class="col-md-6">
            <label class="form-label">Subject <span class="text-danger">*</span></label>
            <select class="form-select" name="category" required>
                <option value="<?php echo htmlentities($result->cid); ?>">
                    <?php echo htmlentities($catname = $result->CategoryName); ?>
                </option>
                <?php 
                $status = 1;
                $sql1 = "SELECT * FROM tblcategory WHERE Status = :status";
                $query1 = $dbh->prepare($sql1);
                $query1->bindParam(':status', $status, PDO::PARAM_STR);
                $query1->execute();
                $resultss = $query1->fetchAll(PDO::FETCH_OBJ);
                if ($query1->rowCount() > 0) {
                    foreach ($resultss as $row) {
                        if ($catname != $row->CategoryName) {
                ?>
                <option value="<?php echo htmlentities($row->id); ?>">
                    <?php echo htmlentities($row->CategoryName); ?>
                </option>
                <?php
                        }
                    }
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Author or Authority <span class="text-danger">*</span></label>
            <select class="form-select" name="author" required>
                <option value="<?php echo htmlentities($result->athrid); ?>">
                    <?php echo htmlentities($athrname = $result->AuthorName); ?>
                </option>
                <?php 
                $sql2 = "SELECT * FROM tblauthors";
                $query2 = $dbh->prepare($sql2);
                $query2->execute();
                $result2 = $query2->fetchAll(PDO::FETCH_OBJ);
                if ($query2->rowCount() > 0) {
                    foreach ($result2 as $ret) {
                        if ($athrname != $ret->AuthorName) {
                ?>
                <option value="<?php echo htmlentities($ret->id); ?>">
                    <?php echo htmlentities($ret->AuthorName); ?>
                </option>
                <?php
                        }
                    }
                }
                ?>
            </select>
        </div>
    </div>

           <div class="row">
                <div class="col-md-4">
                <label class="form-label">Publisher</label>
                <input class="form-control" type="text" name="publisher" 
                       value="<?php echo htmlentities($result->publisher); ?>" />
                </div>       

        <div class="col-md-4">            
            <label class="form-label">Publication Place</label>
            <input class="form-control" type="text" name="publication_place" 
                   value="<?php echo htmlentities($result->publication_place); ?>" />
            </div>

            <div class="col-md-4">
                <label class="form-label">Year</label>
                <input class="form-control" type="text" name="year" 
                       value="<?php echo htmlentities($result->year); ?>" />
            </div>
        </div>
           <div class="row">
                <div class="col-md-4">
            <label class="form-label">Page No</label>
            <input class="form-control" type="text" name="page_no" 
                   value="<?php echo htmlentities($result->page_no); ?>" />
        </div>

        <div class="col-md-4">
            <label class="form-label">Price</label>
            <input class="form-control" type="text" name="price" 
                   value="<?php echo htmlentities($result->price); ?>" />
        </div>

        <div class="col-md-4">
            <label class="form-label">Source</label>
            <input class="form-control" type="text" name="source" 
                   value="<?php echo htmlentities($result->source); ?>" />
        </div>
    </div>
           <div class="row">
                <div class="col-md-4">
            <label class="form-label">Call No</label>
            <input class="form-control" type="text" name="call_no" 
                   value="<?php echo htmlentities($result->call_no); ?>" />
        </div>

         <div class="col-md-4">
            <label class="form-label">Edition</label>
            <input class="form-control" type="text" name="edition" 
                   value="<?php echo htmlentities($result->edition); ?>" />
        </div>

         <div class="col-md-4">
            <label class="form-label">Series</label>
            <input class="form-control" type="text" name="series" 
                   value="<?php echo htmlentities($result->series); ?>" />
        </div>
    </div>
           <div class="row">
                <div class="col-md-4">
            <label class="form-label">Volume</label>
            <input class="form-control" type="text" name="volume" 
                   value="<?php echo htmlentities($result->volume); ?>" />
        </div>

        <div class="col-md-4">
            <label class="form-label">Language</label>
            <input class="form-control" type="text" name="language" 
                   value="<?php echo htmlentities($result->language); ?>" />
        </div>

        <div class="col-md-4">
            <label class="form-label">Shelf No</label>
            <input class="form-control" type="text" name="self_no" 
                   value="<?php echo htmlentities($result->self_no); ?>" />
        </div>
    </div>

    <div class="row">
                <div class="col-md-4">
            <label class="form-label">Purchase Date</label>
            <input class="form-control" type="text" name="purchase_date" 
                   value="<?php echo htmlentities($result->purchase_date); ?>" />
        </div>

        <div class="col-md-4">
            <label class="form-label">Challan No.</label>
            <input class="form-control" type="text" name="challan_no" 
                   value="<?php echo htmlentities($result->challan_no); ?>" />
        </div>

        <div class="col-md-4">
            <label class="form-label">e-Book (Weblink)</label>
            <input class="form-control" type="text" name="e_book_link" 
                   value="<?php echo htmlentities($result->e_book_link); ?>" />
        </div>
    </div>

        <div class="mb-3">
            <label class="form-label">Remarks/ Keyword</label>
            <textarea class="form-control" name="remarks" rows="1"><?php echo htmlentities($result->remarks); ?></textarea>
        </div>
    </div>


<div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
    <button type="submit" name="update" class="btn btn-primary">
        <i class="fa fa-save"></i> Update
    </button>
    <a href="manage-books.php" class="btn btn-secondary me-md-2">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>
<?php 
    }
}
?>
</form>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- BOOTSTRAP 5 JS BUNDLE WITH POPPER -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>