<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {   
    header('location:../index.php');
} else { 
    if(isset($_POST['create'])){

        $category=$_POST['category'].'  '.$_POST['sub_category'];        
        $categoryParent=$_POST['category'];
        $status=$_POST['status'];
        $sql="INSERT INTO tblcategory(categoryParent,CategoryName,Status) VALUES(:categoryParent,:category,:status)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':categoryParent',$categoryParent,PDO::PARAM_STR);
        $query->bindParam(':category',$category,PDO::PARAM_STR);
        $query->bindParam(':status',$status,PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if($lastInsertId){
            $_SESSION['msg']="Brand Listed successfully";
            header('location:manage-categories.php');
        } else {
            $_SESSION['error']="Something went wrong. Please try again";
            header('location:manage-categories.php');
        }
    }
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Online Library Management System | Add Categories</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 mb-0">
                <h4 class=" text-uppercase fw-bold">Add category</h4>
            </div>
        </div>
        <hr>
        
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Category Info</h5>
                    </div>
                    <div class="card-body">
                        <form role="form" method="post">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <!-- <input type="text" class="form-control" id="category" name="category" autocomplete="off" required> -->
                            <input list="categoryList" type="text" class="form-control " id="category" name="category" placeholder="Enter Category" required oninput="fetchStudentDetails(this)">
                    <datalist id="categoryList">
                        <?php
                            $sql = "SELECT DISTINCT categoryParent FROM tblcategory ";
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<option value='" . htmlspecialchars($row['categoryParent'], ENT_QUOTES, 'UTF-8') . "'>";
                            }
                        ?>
                    </datalist>
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Sub-Category/ Additional</label>
                                <input type="text" class="form-control" id="sub_category" placeholder="Enter Sub Category" name="sub_category" autocomplete="off" >
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" checked>
                                    <label class="form-check-label" for="statusActive">
                                        Active
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0">
                                    <label class="form-check-label" for="statusInactive">
                                        Inactive
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" name="create" class="btn btn-primary">Create</button>
                        </form>
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
    <!-- JAVASCRIPT FILES PLACED AT THE BOTTOM TO REDUCE THE LOADING TIME  -->
    <!-- CUSTOM SCRIPTS  -->
    <script src="assets/js/custom.js"></script>
</body>
</html>
<?php } ?>