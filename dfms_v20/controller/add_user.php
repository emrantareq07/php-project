<?php 
session_name('dfms');
session_start();
$table = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
echo $user_type;

// Check if the user is already logged in
if (!isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

require_once('../db/db.php');
include('../include/header.php');
?>  

<div class="container mt-3">
    <div class="row">
        <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
        <div class="col-md-12 col-sm-6 col-lg-6 col-xs-12">
            <div class="card shadow">
                <div class="card-header">
                    <h4 class="text-uppercase fw-bold text-muted">
                        <b>Add User</b>
                        <a href="manage_user.php" class="btn btn-danger float-end">
                            <i class="fa fa-arrow-left"></i> BACK
                        </a>
                    </h4>
                </div>
                <div class="card-body">
                    <form action="manage_user-code.php" method="POST">
                        
                        <!-- Username -->
                        <div class="mb-3">
                            <label for="username">User Name</label>
                            <input type="text" name="username" id="username" class="form-control" required> 
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control">
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input type="text" name="password" id="password" class="form-control" required>
                        </div>

                        <!-- User Type -->
                        <div class="mb-3">
                            <label>User Type</label>
                            <select class="form-select" name="user_type" required>
                                <option value="" disabled selected>--Select--</option>
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>

                        <!-- Product Type -->
                        <div class="mb-3">
                            <label>Product Type</label>
                            <select class="form-select" name="product_type" required>
                                <option value="" disabled selected>--Select--</option>
                                <option value="urea">Urea</option>
                                <option value="non-urea">Non-Urea</option>
                            </select>
                        </div>  

                        <!-- User Status -->
                        <div class="mb-3">
                            <label>User Status</label>
                            <select class="form-select" name="user_status" required>
                                <option value="" disabled selected>--Select--</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <!-- Office Title -->
                        <div class="mb-3">
                            <label for="office_title">Office Title</label>
                            <input type="text" name="office_title" id="office_title" class="form-control">
                        </div>

                        <!-- Product -->
                        <div class="mb-3">
                            <label for="product">Product</label>
                            <input type="text" name="product" id="product" class="form-control">
                        </div>

                        <!-- Hidden Created At (Dhaka Time) -->
                        <?php 
                            date_default_timezone_set("Asia/Dhaka");
                            $created_at = date("Y-m-d H:i:s");
                        ?>
                        <input type="hidden" name="created_at" value="<?php echo $created_at; ?>">

                        <!-- Submit -->
                        <div class="mb-3">
                            <button type="submit" name="save" class="btn btn-primary w-100">
                                <i class="fa fa-save"></i> Save
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-3 col-lg-3 col-xs-12"></div>
    </div>
</div>

<?php include('../include/footer.php'); ?>
