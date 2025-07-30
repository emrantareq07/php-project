<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0){   
header('location:../index.php');
}
else{ 

if(isset($_GET['del'])){
$id=$_GET['del'];
$sql = "delete from tblcategory  WHERE id=:id";
$query = $dbh->prepare($sql);
$query -> bindParam(':id',$id, PDO::PARAM_STR);
$query -> execute();
$_SESSION['delmsg']="Category deleted scuccessfully ";
header('location:manage-categories.php');

}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Manage Categories</title>
     <!-- Bootstrap CSS (latest version) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS (for Bootstrap 5) -->
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery (latest version) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
    <style>
        body {
            font-size: 16px;
            padding-top: 70px;
        }
        .navbar-nav .nav-link {
            font-size: 16px;
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>
 <?php include('includes/navbar.php');?> 
    
    <div class="content-wrapper">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="header-line fw-bold">Manage Categories</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php if($_SESSION['error']!="") { ?>
                                <div class="col-md-6">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Error:</strong> 
                                        <?php echo htmlentities($_SESSION['error']);?>
                                        <?php echo htmlentities($_SESSION['error']="");?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                                <?php } ?>
                                
                                <?php if($_SESSION['msg']!="") { ?>
                                <div class="col-md-6">
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success:</strong> 
                                        <?php echo htmlentities($_SESSION['msg']);?>
                                        <?php echo htmlentities($_SESSION['msg']="");?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                                <?php } ?>
                                
                                <?php if($_SESSION['updatemsg']!="") { ?>
                                <div class="col-md-6">
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success:</strong> 
                                        <?php echo htmlentities($_SESSION['updatemsg']);?>
                                        <?php echo htmlentities($_SESSION['updatemsg']="");?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                                <?php } ?>
                                
                                <?php if($_SESSION['delmsg']!="") { ?>
                                <div class="col-md-6">
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <strong>Success:</strong> 
                                        <?php echo htmlentities($_SESSION['delmsg']);?>
                                        <?php echo htmlentities($_SESSION['delmsg']="");?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" id="dataTables-example">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Creation Date</th>
                                            <th>Updation Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $sql = "SELECT * from tblcategory";
                                        $query = $dbh->prepare($sql);
                                        $query->execute();
                                        $results=$query->fetchAll(PDO::FETCH_OBJ);
                                        $cnt=1;
                                        if($query->rowCount() > 0) {
                                        foreach($results as $result)
                                        {               ?>                                      
                                        <tr>
                                            <td><?php echo htmlentities($cnt);?></td>
                                            <td><?php echo htmlentities($result->CategoryName);?></td>
                                            <td>
                                                <?php if($result->Status==1) { ?>
                                                <span class="badge bg-success">Active</span>
                                                <?php } else { ?>
                                                <span class="badge bg-danger">Inactive</span>
                                                <?php } ?>
                                            </td>
                                            <td><?php echo htmlentities($result->CreationDate);?></td>
                                            <td><?php echo htmlentities($result->UpdationDate);?></td>
                                            <td>
                                                <a href="edit-category.php?catid=<?php echo htmlentities($result->id);?>" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a> 
                                                <a href="manage-categories.php?del=<?php echo htmlentities($result->id);?>" onclick="return confirm('Are you sure you want to delete?');" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $cnt=$cnt+1;}} ?>                                      
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
    
    <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search...",
                }
            });
        });
    </script>
</body>
</html>
<?php } ?>