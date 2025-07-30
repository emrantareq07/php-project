<?php
session_start();
require_once("config/config.php");
require_once("db/db.php");

// Authentication check
if (!isset($_SESSION["uid"]) && !isset($_COOKIE['user_login'])) {
    header('Location: login.php');
    exit();
}

// Include necessary functions
include_once(ROOT_PATH . '/libs/function.php');
$db = new DB_con();

// Get logged in username
$uname = isset($_SESSION["uname"]) ? $_SESSION['uname'] : $_COOKIE['user_login'];
$userData = ['Username' => ''];

// Fetch user data securely
$query = "SELECT Username FROM tblusers WHERE Username = '$uname'";
$result = $db->runBaseQuery($query);

if (!empty($result)) {
    $userData = $result[0];
}

// Get photo ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch photo data
$photo = [];
if ($id > 0) {
    $result = $db->runBaseQuery("SELECT * FROM photo_gallary WHERE id = $id");
    if (!empty($result)) {
        $photo = $result[0];
    }
}

// If photo not found, redirect
if (empty($photo)) {
    header('Location: manage_photo.php');
    exit();
}

// Fetch categories and subcategories
$categories = $db->runBaseQuery("SELECT * FROM categories");
$subcategories = $db->runBaseQuery("SELECT * FROM sub_categories");

// Handle form submission
$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $title = trim($_POST['title']);
    $category_id = $_POST['category_id'];
    $subcategory_id = $_POST['subcategory_id'];
    $publish_date = $_POST['publish_date'];
    $publish_month = $_POST['publish_month'];
    
    // Get category and subcategory names
    $category_name = '';
    $subcategory_name = '';
    
    foreach ($categories as $cat) {
        if ($cat['id'] == $category_id) {
            $category_name = $cat['category_name'];
            break;
        }
    }
    
    foreach ($subcategories as $subcat) {
        if ($subcat['id'] == $subcategory_id) {
            $subcategory_name = $subcat['sub_category_name'];
            break;
        }
    }
    
    // Check if new photo is uploaded
    $filename = $photo['photo'];
    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
        $new_filename = basename($_FILES["photo"]["name"]);
        $tempname = $_FILES["photo"]["tmp_name"];
        $folder = "./uploads/" . $new_filename;
        
        // Allowed file types
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($tempname);
        
        if (!in_array($file_type, $allowed_types)) {
            $msg = "<div class='alert alert-danger'>Only JPG, PNG, and GIF files are allowed.</div>";
        } else {
            // Move new file
            if (move_uploaded_file($tempname, $folder)) {
                // Delete old file
                $old_file = "./uploads/" . $photo['photo'];
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
                $filename = $new_filename;
            } else {
                $msg = "<div class='alert alert-danger'>File upload failed.</div>";
            }
        }
    }
    
    // Update database
    $query = "UPDATE photo_gallary SET 
              title = '".mysqli_real_escape_string($db->dbh, $title)."',
              photo = '".mysqli_real_escape_string($db->dbh, $filename)."',
              category_id = '".mysqli_real_escape_string($db->dbh, $category_id)."',
              subcategory_id = '".mysqli_real_escape_string($db->dbh, $subcategory_id)."',
              category = '".mysqli_real_escape_string($db->dbh, $category_name)."',
              sub_category = '".mysqli_real_escape_string($db->dbh, $subcategory_name)."',
              publish_date = '".mysqli_real_escape_string($db->dbh, $publish_date)."',
              publish_month = '".mysqli_real_escape_string($db->dbh, $publish_month)."'
              WHERE id = $id";
    
    $result = $db->executeQuery($query);
    
    if ($result) {
        $msg = "<div class='alert alert-success'>Photo updated successfully!</div>";
        // Refresh photo data
        $result = $db->runBaseQuery("SELECT * FROM photo_gallary WHERE id = $id");
        if (!empty($result)) {
            $photo = $result[0];
        }
    } else {
        $msg = "<div class='alert alert-danger'>Error updating photo: " . mysqli_error($db->dbh) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Photo - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>/static/css/login_form_style.css">
    <style>
        .photo-preview {
            max-width: 300px;
            max-height: 300px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container-fluid bg-light">
    <div class="container bg-white shadow border mt-3 pt-4 pb-4">
        <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Hello, <span class="text-success"><?php echo htmlspecialchars($userData['Username']); ?></span></h1>
                    <div>
                        <a href="dashboard.php" class="btn btn-secondary mr-2">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                        <a href="manage_photo.php" class="btn btn-primary mr-2">
                            <i class="fas fa-cogs"></i> Manage
                        </a>
                        <a href="logout.php" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>

                <h2 class="text-center text-muted mb-4">Edit Photo</h2>
                
                <?php echo $msg; ?>
                
                <form method="POST" enctype="multipart/form-data" class="upload-form">
                    <div class="form-group">
                        <label>Current Photo:</label><br>
                        <img src="./uploads/<?php echo htmlspecialchars($photo['photo']); ?>" 
                             class="photo-preview img-thumbnail">
                    </div>
                    
                    <div class="form-group">
                        <label>Change Photo (optional):</label>
                        <input type="file" name="photo" accept="image/*" class="form-control">
                        <small class="text-muted">Leave blank to keep current photo</small>
                    </div>

                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" name="title" class="form-control" 
                               value="<?php echo htmlspecialchars($photo['title']); ?>" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Category:</label>
                            <select class="form-control" id="category" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>"
                                        <?php if ($category['id'] == $photo['category_id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($category['category_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Sub-Category:</label>
                            <select class="form-control" id="subcategory" name="subcategory_id" required>
                                <option value="">Select Sub-category</option>
                                <?php foreach ($subcategories as $subcat): ?>
                                    <option value="<?php echo $subcat['id']; ?>" 
                                        data-category="<?php echo $subcat['category_id']; ?>"
                                        <?php if ($subcat['id'] == $photo['subcategory_id']) echo 'selected'; ?>>
                                        <?php echo htmlspecialchars($subcat['sub_category_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Publish Date:</label>
                            <input type="date" name="publish_date" class="form-control" 
                                   value="<?php echo $photo['publish_date']; ?>" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Publish Month:</label>
                            <select name="publish_month" class="form-control" required>
                                <?php
                                $months = ['January', 'February', 'March', 'April', 'May', 'June', 
                                          'July', 'August', 'September', 'October', 'November', 'December'];
                                foreach ($months as $month): ?>
                                    <option value="<?php echo $month; ?>"
                                        <?php if ($month == $photo['publish_month']) echo 'selected'; ?>>
                                        <?php echo $month; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <button type="submit" name="update" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Update Photo
                    </button>
                </form>
            </div>
            <div class="col-sm-3"></div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function() {
    // Filter subcategories based on selected category
    $('#subcategory option').each(function() {
        if ($(this).val() !== '') {
            $(this).hide();
        }
    });

    // Show only subcategories for the current category
    var currentCategory = $('#category').val();
    $('#subcategory option').each(function() {
        var subcat = $(this);
        if (subcat.val() === '' || subcat.data('category') == currentCategory) {
            subcat.show();
        }
    });

    $('#category').change(function() {
        var categoryId = $(this).val();
        $('#subcategory option').each(function() {
            var subcat = $(this);
            if (subcat.val() === '' || subcat.data('category') == categoryId) {
                subcat.show();
            } else {
                subcat.hide();
            }
        });
        $('#subcategory').val('');
    });
});
</script>
</body>
</html>