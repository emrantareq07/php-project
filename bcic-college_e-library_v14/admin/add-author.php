<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
include('includes/config.php');

if(strlen($_SESSION['alogin'])==0){   
header('location:../index.php');
}
else{ 

if(isset($_POST['create'])){
$author=$_POST['author'];
$sql="INSERT INTO  tblauthors(AuthorName) VALUES(:author)";
$query = $dbh->prepare($sql);
$query->bindParam(':author',$author,PDO::PARAM_STR);
$query->execute();
$lastInsertId = $dbh->lastInsertId();
if($lastInsertId) {
$_SESSION['msg']="Author Listed successfully";
header('location:manage-authors.php');
}else{
$_SESSION['error']="Something went wrong. Please try again";
header('location:manage-authors.php');
    }

}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Add Author</title>
    <!-- Bootstrap 5.3.3 CSS (latest as of June 2024) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome 6.5.1 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
     <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    
    <div class="content-wrapper">
        <div class="container py-4">
            <div class="row mb-4">
                <div class="col-12">
                    <h2 class="fw-bold text-uppercase">Add Author</h2>
                    <hr>
                </div>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8">
                    <div class="card  shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0"><i class="fas fa-user-edit me-2"></i>Author Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="post">
                                <div class="mb-3">
                                    <label for="authorName" class="form-label fw-semibold">Author Name</label>
                                    <input type="text" class="form-control form-control-lg" id="authorName" 
                                           name="author" autocomplete="off" required placeholder="Enter author name">
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" name="create" class="btn btn-primary btn-lg">
                                        <i class="fas fa-plus-circle me-2"></i>Add Author
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
    
    <!-- Bootstrap 5.3.3 JS Bundle with Popper (latest as of June 2024) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom JS -->
    
</body>
</html>
<?php } ?>