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
        $category=$_POST['category'];
        $status=$_POST['status'];
        $catid=intval($_GET['catid']);
        $sql="update tblcategory set CategoryName=:category,Status=:status where id=:catid";
        $query = $dbh->prepare($sql);
        $query->bindParam(':category',$category,PDO::PARAM_STR);
        $query->bindParam(':status',$status,PDO::PARAM_STR);
        $query->bindParam(':catid',$catid,PDO::PARAM_STR);
        $query->execute();
        $_SESSION['updatemsg']="Category updated successfully";
        header('location:manage-categories.php');
    }
?>
 <?php //include('includes/navbar.php');?> 
    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    
    <div class="content-wrapper">
        <div class="container">
            <div class="row justify-content-center mt-4">
                <div class="col-md-8">
                    <div class="card shadow">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Category</h4>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <?php 
                                $catid=intval($_GET['catid']);
                                $sql="SELECT * from tblcategory where id=:catid";
                                $query=$dbh->prepare($sql);
                                $query->bindParam(':catid',$catid, PDO::PARAM_STR);
                                $query->execute();
                                $results=$query->fetchAll(PDO::FETCH_OBJ);
                                if($query->rowCount() > 0)
                                {
                                    foreach($results as $result)
                                    {               
                                ?> 
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category Name</label>
                                    <input type="text" class="form-control" id="category" name="category" 
                                           value="<?php echo htmlentities($result->CategoryName);?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Sub Category </label>
                                    <input type="text" class="form-control" id="sub_category" name="sub_category" 
                                           value="<?php echo htmlentities($result->CategoryName);?>" required>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="form-label">Status</label>
                                    <div class="d-flex gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="active" 
                                                   value="1" <?php echo ($result->Status==1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label text-success" for="active">
                                                <i class="fas fa-check-circle me-1"></i> Active
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status" id="inactive" 
                                                   value="0" <?php echo ($result->Status==0) ? 'checked' : ''; ?>>
                                            <label class="form-check-label text-danger" for="inactive">
                                                <i class="fas fa-times-circle me-1"></i> Inactive
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <?php }} ?>
                                
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="manage-categories.php" class="btn btn-secondary me-md-2">
                                        <i class="fas fa-arrow-left me-1"></i> Back
                                    </a>
                                    <button type="submit" name="update" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Update
                                    </button>
                                </div>
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
    
    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php } ?>