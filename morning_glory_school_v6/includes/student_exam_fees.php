<?php
session_start();  
require_once("../config/config.php");
require_once("../db/db.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Exam Fees</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .required:after {
            content: " *";
            color: red;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Student Exam Fee Management</h2>
        
        <!-- Success/Error Message -->
        <?php if(isset($_SESSION['message'])): ?>
        <div class="alert alert-<?= $_SESSION['msg_type'] ?> alert-dismissible fade show">
            <?= $_SESSION['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php 
            unset($_SESSION['message']);
            unset($_SESSION['msg_type']);
        endif; ?>
        
        <!-- Form -->
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><?= isset($_GET['edit']) ? 'Edit' : 'Add' ?> Student Fee</h5>
            </div>
            <div class="card-body">
                <form action="process.php" method="post">
                    <input type="hidden" name="id" value="<?= $id ?? '' ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="entry_date" class="form-label required">Entry Date</label>
                            <input type="date" class="form-control" id="entry_date" name="entry_date" 
                                   value="<?= $entry_date ?? date('Y-m-d') ?>" required>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="roll_no" class="form-label required">Roll No</label>
                            <input type="text" class="form-control" id="roll_no" name="roll_no" 
                                   value="<?= $roll_no ?? '' ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="std_name" class="form-label required">Student Name</label>
                            <input type="text" class="form-control" id="std_name" name="std_name" 
                                   value="<?= $std_name ?? '' ?>" required>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="class" class="form-label required">Class</label>
                            <select class="form-select" id="class" name="class" required>
                                <option value="">Select Class</option>
                                <?php
                                $classes = ['Nursery', 'LKG', 'UKG', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'];
                                foreach($classes as $c) {
                                    $selected = (isset($class) && $class == $c) ? 'selected' : '';
                                    echo "<option value='$c' $selected>$c</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="section" class="form-label required">Section</label>
                            <select class="form-select" id="section" name="section" required>
                                <option value="">Select Section</option>
                                <?php
                                $sections = ['A', 'B', 'C', 'D', 'E'];
                                foreach($sections as $s) {
                                    $selected = (isset($section) && $section == $s) ? 'selected' : '';
                                    echo "<option value='$s' $selected>$s</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="month" class="form-label required">Month</label>
                            <select class="form-select" id="month" name="month" required>
                                <option value="">Select Month</option>
                                <?php
                                $months = ['January', 'February', 'March', 'April', 'May', 'June', 
                                          'July', 'August', 'September', 'October', 'November', 'December'];
                                foreach($months as $m) {
                                    $selected = (isset($month) && $month == $m) ? 'selected' : '';
                                    echo "<option value='$m' $selected>$m</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="year" class="form-label required">Year</label>
                            <select class="form-select" id="year" name="year" required>
                                <option value="">Select Year</option>
                                <?php
                                $current_year = date('Y');
                                for($y = $current_year - 5; $y <= $current_year + 5; $y++) {
                                    $selected = (isset($year) && $year == $y) ? 'selected' : '';
                                    echo "<option value='$y' $selected>$y</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="exam_fee" class="form-label required">Exam Fee</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" step="0.01" class="form-control" id="exam_fee" name="exam_fee" 
                                       value="<?= $exam_fee ?? '' ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="total" class="form-label required">Total Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" step="0.01" class="form-control" id="total" name="total" 
                                       value="<?= $total ?? '' ?>" required>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="remarks" class="form-label">Remarks</label>
                            <input type="text" class="form-control" id="remarks" name="remarks" 
                                   value="<?= $remarks ?? '' ?>">
                        </div>
                        
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary" name="save">
                                <i class="fas fa-save me-1"></i> <?= isset($_GET['edit']) ? 'Update' : 'Save' ?>
                            </button>
                            <a href="../dashboard.php" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>