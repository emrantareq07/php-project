<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
    header('location:../index.php');
}
else{ 
    if(isset($_POST['update']))
    {
        $athrid=intval($_GET['athrid']);
        $author=$_POST['author'];
        $sql="update tblauthors set AuthorName=:author where id=:athrid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':author',$author,PDO::PARAM_STR);
        $query->bindParam(':athrid',$athrid,PDO::PARAM_STR);
        $query->execute();
        $_SESSION['updatemsg']="Author info updated successfully";
        header('location:manage-authors.php');
    }
?>
 <?php //include('includes/navbar.php');?> 
    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    
    <div class="content-wrapper">
        <div class="container">
            <div class="row my-4">
                <div class="col-md-12">
                    <h4 class="header-line text-uppercase fw-bold">Edit Author</h4>
                </div>
            </div>
            <hr>
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-user me-2"></i> Author Information</h5>
                        </div>
                        <div class="card-body">
                            <form role="form" method="post">
                                <div class="mb-3">
                                    <label for="author" class="form-label">Author Name</label>
                                    <?php 
                                    $athrid=intval($_GET['athrid']);
                                    $sql = "SELECT * from tblauthors where id=:athrid";
                                    $query = $dbh->prepare($sql);
                                    $query->bindParam(':athrid',$athrid,PDO::PARAM_STR);
                                    $query->execute();
                                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                                    if($query->rowCount() > 0) {
                                        foreach($results as $result) {               
                                    ?>   
                                    <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlentities($result->AuthorName);?>" required>
                                    <?php }} ?>
                                </div>
                                <button type="submit" name="update" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update
                                </button>

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
    
    <!-- BOOTSTRAP 5 JS BUNDLE WITH POPPER -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- CUSTOM SCRIPTS -->
   
</body>
</html>
<?php } ?>