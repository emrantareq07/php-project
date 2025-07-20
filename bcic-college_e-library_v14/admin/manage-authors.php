<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {   
    header('location:../index.php');
} else {
    if(isset($_GET['del'])) {
        $id=$_GET['del'];
        $sql = "delete from tblauthors WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id',$id, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['delmsg']="Author deleted";
        header('location:manage-authors.php');
    }
?>

    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    
    <div class="content-wrapper">
        <div class="container py-2">
            <div class="row mb-2">
                <div class="col-12">
                    <h2 class="fw-bold">Manage Authors</h2>
                    <hr>
                </div>
            </div>
            
            <!-- Alert Messages -->
            <div class="row">
                <?php if($_SESSION['error']!="") { ?>
                <div class="col-md-6">
                    <div class="alert alert-danger alert-dismissible fade show">
                        <strong>Error :</strong> 
                        <?php echo htmlentities($_SESSION['error']); ?>
                        <?php echo htmlentities($_SESSION['error']=""); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
                <?php } ?>
                
                <?php if($_SESSION['msg']!="") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success alert-dismissible fade show">
                        <strong>Success :</strong> 
                        <?php echo htmlentities($_SESSION['msg']); ?>
                        <?php echo htmlentities($_SESSION['msg']=""); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
                <?php } ?>
                
                <?php if($_SESSION['updatemsg']!="") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success alert-dismissible fade show">
                        <strong>Success :</strong> 
                        <?php echo htmlentities($_SESSION['updatemsg']); ?>
                        <?php echo htmlentities($_SESSION['updatemsg']=""); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
                <?php } ?>
                
                <?php if($_SESSION['delmsg']!="") { ?>
                <div class="col-md-6">
                    <div class="alert alert-success alert-dismissible fade show">
                        <strong>Success :</strong> 
                        <?php echo htmlentities($_SESSION['delmsg']); ?>
                        <?php echo htmlentities($_SESSION['delmsg']=""); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
                <?php } ?>
            </div>

            <div class="row ">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-list me-2"></i>Authors Listing</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="authorsTable">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Author</th>
                                            <th>Creation Date</th>
                                            <th>Updation Date</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $sql = "SELECT * from tblauthors";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt=1;
                                        if($query->rowCount() > 0) {
                                            foreach($results as $result) { 
                                        ?>                                      
                                        <tr>
                                            <td><?php echo htmlentities($cnt); ?></td>
                                            <td><?php echo htmlentities($result->AuthorName); ?></td>
                                            <td><?php echo htmlentities($result->creationDate); ?></td>
                                            <td><?php echo htmlentities($result->UpdationDate); ?></td>
                                            <td class="text-center">
                                                <a href="edit-author.php?athrid=<?php echo htmlentities($result->id); ?>" class="btn btn-sm btn-primary me-1">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a> 
                                                <a href="manage-authors.php?del=<?php echo htmlentities($result->id); ?>" 
                                                   onclick="return confirm('Are you sure you want to delete this author?');" 
                                                   class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                        <?php 
                                            $cnt=$cnt+1;
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

    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php');?>
    <!-- FOOTER SECTION END-->
    
    <!-- jQuery 3.7.1 -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap 5.3.3 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/v/bs5/dt-2.0.7/datatables.min.js"></script>
    <!-- Custom JS -->
    <script src="assets/js/custom.js"></script>
    
    <script>
        $(document).ready(function() {
            $('#authorsTable').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search authors...",
                }
            });
        });
    </script>
</body>
</html>
<?php } ?>