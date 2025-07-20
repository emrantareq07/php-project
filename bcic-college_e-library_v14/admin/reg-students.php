<?php
session_name('bcic_college_e-library');
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0) {   
    header('location:../index.php');
}
else { 
    // code for block student    
    if(isset($_GET['inid'])) {
        $id=$_GET['inid'];
        $status=0;
        $sql = "UPDATE tblstudents SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id',$id, PDO::PARAM_STR);
        $query->bindParam(':status',$status, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['msg']="Student blocked successfully";
        header('location:reg-students.php');
    }

    //code for active students
    if(isset($_GET['id'])) {
        $id=$_GET['id'];
        $status=1;
        $sql = "UPDATE tblstudents SET Status=:status WHERE id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id',$id, PDO::PARAM_STR);
        $query->bindParam(':status',$status, PDO::PARAM_STR);
        $query->execute();
        $_SESSION['msg']="Student activated successfully";
        header('location:reg-students.php');
    }
?>

    <!------MENU SECTION START-->
    <?php include('includes/header.php');?>
    <!-- MENU SECTION END-->
    <div class="content-wrapper">
        <div class="container">
            <div class="row my-2">
                <div class="col-md-12">
                    <h4 class="header-line text-uppercase fw-bold">Manage Registered Students</h4>
                </div>
            </div>
            <hr>
            <?php if(isset($_SESSION['msg']) && $_SESSION['msg']!="") { ?>
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show">
                    <strong>Success :</strong> 
                    <?php echo htmlentities($_SESSION['msg']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <?php $_SESSION['msg']=""; ?>
                </div>
            </div>
            <?php } ?>
            
            <!-- Card View Toggle -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="btn-group float-end">
                        <button id="tableViewBtn" class="btn btn-outline-primary active">
                            <i class="fas fa-table"></i> Table View
                        </button>
                        <button id="cardViewBtn" class="btn btn-outline-primary">
                            <i class="fas fa-th-large"></i> Card View
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Table View -->
            <div id="tableView">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">Registered Students</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Image</th>
                                                <th>Student ID</th>
                                                <th>Full Name</th>
                                                <th>Class</th>
                                                <th>Section</th>
                                                <th>Group</th>
                                                <th>Session</th>
                                                <th>Email</th>
                                                <th>Mobile</th>
                                                <th>Reg Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody> 
                                            <?php 
                                            $sql = "SELECT * FROM tblstudents order by id desc";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results=$query->fetchAll(PDO::FETCH_OBJ);
                                            $cnt=1;
                                            if($query->rowCount() > 0) {
                                                foreach($results as $result) {               
                                            ?>                                      
                                            <tr class="odd gradeX">
                                                <td class="center"><?php echo htmlentities($cnt); ?></td>
                                                <td class="center">
                                                    <?php 
                                                    $imagePath = htmlentities($result->Image);
                                                    $defaultImage = "student_images/default.jpg";
                                                    
                                                    if(file_exists($imagePath) && !empty($result->Image)) {
                                                        echo '<img src="'.$imagePath.'" class="student-img" alt="Student Image">';
                                                    } else {
                                                        echo '<img src="'.$defaultImage.'" class="student-img" alt="Default Image">';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="center"><?php echo htmlentities($result->StudentId); ?></td>
                                                <td class="center"><?php echo htmlentities($result->FullName); ?></td>
                                                <td class="center"><?php echo htmlentities($result->std_class); ?></td>
                                                <td class="center"><?php echo htmlentities($result->std_section); ?></td>
                                                <td class="center"><?php echo htmlentities($result->std_group); ?></td>
                                                <td class="center"><?php echo htmlentities($result->std_session); ?></td>
                                                <td class="center"><?php echo htmlentities($result->EmailId); ?></td>
                                                <td class="center"><?php echo htmlentities($result->MobileNumber); ?></td>
                                                <td class="center"><?php echo htmlentities($result->RegDate); ?></td>
                                                <td class="center">
                                                    <?php if($result->Status==1) {
                                                        echo '<span class="badge bg-success status-badge">Active</span>';
                                                    } else {
                                                        echo '<span class="badge bg-danger status-badge">Blocked</span>';
                                                    } ?>
                                                </td>
                                                <td class="center">
                                                    <?php if($result->Status==1) { ?>
                                                        <a href="reg-students.php?inid=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Are you sure you want to block this student?');" class="btn btn-danger btn-sm"><i class="fa fa-lock"></i> </a>
                                                    <?php } else { ?>
                                                        <a href="reg-students.php?id=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Are you sure you want to activate this student?');" class="btn btn-primary btn-sm"><i class="fa fa-unlock"></i> </a>
                                                    <?php } ?>
                                                    <a href="edit-student.php?id=<?php echo htmlentities($result->id); ?>" class="btn btn-warning btn-sm"> <i class="fa fa-edit"></i> </a>
                                                    <a href="print_card_1.php?id=<?php echo htmlentities($result->id); ?>" class="btn btn-info btn-sm" target="_blank"><i class="fas fa-print"></i> Print Card</a>
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
            
            <!-- Card View -->
            <div id="cardView" style="display: none;">
                <div class="row">
                    <?php 
                    $sql = "SELECT * FROM tblstudents order by id desc";
                    $query = $dbh->prepare($sql);
                    $query->execute();
                    $results=$query->fetchAll(PDO::FETCH_OBJ);
                    $cnt=1;
                    if($query->rowCount() > 0) {
                        foreach($results as $result) {               
                    ?> 
                    <div class="col-md-4 col-lg-3">
                        <div class="card student-card">
                            <div class="card-img-container text-center pt-3">
                                <?php 
                                $imagePath = htmlentities($result->Image);
                                $defaultImage = "student_images/default.jpg";
                                
                                if(file_exists($imagePath) && !empty($result->Image)) {
                                    echo '<img src="'.$imagePath.'" class="student-img" alt="Student Image">';
                                } else {
                                    echo '<img src="'.$defaultImage.'" class="student-img" alt="Default Image">';
                                }
                                ?>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlentities($result->FullName); ?></h5>
                                <p class="card-text">
                                    <small class="text-muted">ID: <?php echo htmlentities($result->StudentId); ?></small><br>
                                    <small>Class: <?php echo htmlentities($result->std_class); ?></small><br>
                                    <small>Section: <?php echo htmlentities($result->std_section); ?></small><br>
                                    <small>Group: <?php echo htmlentities($result->std_group); ?></small><br>
                                    <small>Session: <?php echo htmlentities($result->std_session); ?></small>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <?php if($result->Status==1) {
                                            echo '<span class="badge bg-success status-badge">Active</span>';
                                        } else {
                                            echo '<span class="badge bg-danger status-badge">Blocked</span>';
                                        } ?>
                                    </div>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu">
                                        <?php if ($result->Status == 1) { ?>
                                            <li>
                                                <a class="dropdown-item text-danger" href="reg-students.php?inid=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Are you sure you want to block this student?');" title="Block">
                                                    <i class="fa fa-lock"></i>
                                                </a>
                                            </li>
                                        <?php } else { ?>
                                            <li>
                                                <a class="dropdown-item text-success" href="reg-students.php?id=<?php echo htmlentities($result->id); ?>" onclick="return confirm('Are you sure you want to activate this student?');" title="Activate">
                                                    <i class="fa fa-unlock"></i>
                                                </a>
                                            </li>
                                        <?php } ?>
                                        <li>
                                            <a class="dropdown-item" href="edit-student.php?id=<?php echo htmlentities($result->id); ?>" title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="print_card.php?id=<?php echo htmlentities($result->id); ?>" target="_blank" title="Print Card">
                                                <i class="fa fa-print"></i>
                                            </a>
                                        </li>
                                    </ul>

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-muted">
                                <small>Registered: <?php echo htmlentities($result->RegDate); ?></small>
                            </div>
                        </div>
                    </div>
                    <?php 
                        $cnt=$cnt+1;
                        }
                    } 
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- CONTENT-WRAPPER SECTION END-->
    <?php include('includes/footer.php'); ?>
    <!-- FOOTER SECTION END-->
    
    <!-- JAVASCRIPT FILES -->
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Datatables JS -->
    <script src="https://cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.js"></script>
    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                responsive: true
            });
            
            // Toggle between table and card view
            $('#tableViewBtn').click(function() {
                $('#tableView').show();
                $('#cardView').hide();
                $(this).addClass('active');
                $('#cardViewBtn').removeClass('active');
            });
            
            $('#cardViewBtn').click(function() {
                $('#tableView').hide();
                $('#cardView').show();
                $(this).addClass('active');
                $('#tableViewBtn').removeClass('active');
            });
        });
    </script>
</body>
</html>
<?php } ?>