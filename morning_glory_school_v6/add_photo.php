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

// Fetch categories and subcategories
$categories = $db->runBaseQuery("SELECT * FROM categories");
$subcategories = $db->runBaseQuery("SELECT * FROM sub_categories");

// Variables
$msg = "";
$success = isset($_GET['submitted']);

// Handle image upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload'])) {
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
//     foreach ($subcategories as $subcat) {
//     if ($subcat['id'] == $subcategory_id) {
//         $subcategory_name = $subcat['sub_category_name'];
//         break;
//     }
// }

 //    if (isset($_FILES["photo"]) && $_FILES["photo"]["error"] == 0) {
 //        $filename = basename($_FILES["photo"]["name"]);
 //        $tempname = $_FILES["photo"]["tmp_name"];
 //        $folder = "./uploads/" . $filename;

 //        // Allowed file types
 //        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
 //        $file_type = mime_content_type($tempname);

 //        if (!in_array($file_type, $allowed_types)) {
 //            $msg = "<div class='alert alert-danger'>Only JPG, PNG, and GIF files are allowed.</div>";
 //        } else {
 //            $query = "INSERT INTO `photo_gallary` 
 // (`title`, `photo`, `category_id`, `subcategory_id`, `category`, `sub_category`, `publish_date`, `publish_month`) 
 // VALUES (
 //    '".mysqli_real_escape_string($db->dbh, $title)."', 
 //    '".mysqli_real_escape_string($db->dbh, $filename)."', 
 //    '".mysqli_real_escape_string($db->dbh, $category_id)."', 
 //    '".mysqli_real_escape_string($db->dbh, $subcategory_id)."', 
 //    '".mysqli_real_escape_string($db->dbh, $category_name)."', 
 //    '".mysqli_real_escape_string($db->dbh, $subcategory_name)."', 
 //    '".mysqli_real_escape_string($db->dbh, $publish_date)."', 
 //    '".mysqli_real_escape_string($db->dbh, $publish_month)."'
 // )";

            
 //            $result = $db->executeQuery($query);
            
 //            if ($result) {
 //                if (move_uploaded_file($tempname, $folder)) {
 //                    header("Location: add_photo.php?submitted=successfully");
 //                    exit();
 //                } else {
 //                    $msg = "<div class='alert alert-danger'>File upload failed.</div>";
 //                }
 //            } else {
 //                $msg = "<div class='alert alert-danger'>Database error: " . mysqli_error($db->dbh) . "</div>";
 //            }
 //        }
 //    } else {
 //        $msg = "<div class='alert alert-danger'>Please select an image to upload.</div>";
 //    }

 if (isset($_FILES["photos"])) {
    // Allowed file types
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $uploaded_files = [];
    $errors = [];
    
    // Create uploads directory if it doesn't exist
    if (!file_exists('./uploads')) {
        mkdir('./uploads', 0777, true);
    }
    
    // Loop through each file
    foreach ($_FILES['photos']['tmp_name'] as $key => $tmp_name) {
        $filename = basename($_FILES['photos']['name'][$key]);
        $tempname = $_FILES['photos']['tmp_name'][$key];
        $file_type = mime_content_type($tempname);
        $folder = "./uploads/" . $filename;
        
        // Validate file type
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "File $filename: Only JPG, PNG, and GIF files are allowed.";
            continue;
        }
        
        // Move uploaded file
        if (move_uploaded_file($tempname, $folder)) {
            $uploaded_files[] = $filename;
            
            // Insert into database for each file
            $query = "INSERT INTO `photo_gallary` 
                     (`title`, `photo`, `category_id`, `subcategory_id`, `category`, `sub_category`, `publish_date`, `publish_month`) 
                     VALUES (
                        '".mysqli_real_escape_string($db->dbh, $title)."', 
                        '".mysqli_real_escape_string($db->dbh, $filename)."', 
                        '".mysqli_real_escape_string($db->dbh, $category_id)."', 
                        '".mysqli_real_escape_string($db->dbh, $subcategory_id)."', 
                        '".mysqli_real_escape_string($db->dbh, $category_name)."', 
                        '".mysqli_real_escape_string($db->dbh, $subcategory_name)."', 
                        '".mysqli_real_escape_string($db->dbh, $publish_date)."', 
                        '".mysqli_real_escape_string($db->dbh, $publish_month)."'
                     )";
            
            $result = $db->executeQuery($query);
            if (!$result) {
                $errors[] = "Failed to insert $filename into database: " . mysqli_error($db->dbh);
            }
        } else {
            $errors[] = "Failed to upload $filename";
        }
    }
    
    // Display success/error messages
    if (!empty($uploaded_files)) {
        $msg = "<div class='alert alert-success'>Successfully uploaded " . count($uploaded_files) . " files.</div>";
    }
    if (!empty($errors)) {
        $msg .= "<div class='alert alert-danger'>" . implode("<br>", $errors) . "</div>";
    }
} else {
        $msg = "<div class='alert alert-danger'>Please select an image to upload.</div>";
     }   
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Photo Upload - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Replace this line -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

<!-- With this one -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <!-- Bootstrap CSS & Font Awesome -->
    <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/static/css/login_form_style.css">

    <style>
        .gallery-thumbnail {
            width: 200px;
            height: 240px;
            object-fit: cover;
            cursor: pointer;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .upload-form {
            max-width: 600px;
            margin: auto;
        }
        /* Thumbnail Container Styles */
        .thumbnail-container {
            width: 100%;
            overflow-x: auto;
            padding: 10px 0;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
        }
        
        .thumbnail-scroller {
            display: flex;
            gap: 15px;
            padding: 0 15px;
        }
        
        .thumbnail-item {
            flex: 0 0 auto;
            width: 200px;
            background: white;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .thumbnail-item:hover {
            transform: translateY(-5px);
        }
        
        .thumbnail-caption {
            padding: 10px;
        }
        
        /* Modal Styles */
        .image-viewer-container {
            max-height: 70vh;
            overflow-y: auto;
            text-align: center;
        }
        
        #fullsizeImage {
            max-width: 100%;
            height: auto;
            margin: 0 auto;
        }
        
        /* Custom scrollbar */
        .thumbnail-container::-webkit-scrollbar {
            height: 8px;
        }
        
        .thumbnail-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .thumbnail-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        .thumbnail-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

            .modal-close-btn {
        position: absolute;
        top: 15px;
        right: 20px;
        z-index: 1051;
        font-size: 1.8rem;
        color: white;
        background: rgba(0, 0, 0, 0.5);
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        line-height: 36px;
        text-align: center;
        cursor: pointer;
    }
    .modal-close-btn:hover {
        background: rgba(0, 0, 0, 0.8);
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
                        <a href="manage_photo.php" class="btn btn-primary">
                            <i class="fas fa-cogs"></i> Manage Photos
                        </a>
                        
                    </div>
                </div>

                <!-- Upload Form -->
                <h2 class="text-center text-muted mb-4">Upload Photo</h2>

                <?php if ($success): ?>
                    <div class="alert alert-success">Image uploaded successfully!</div>
                <?php endif; ?>
                <?php echo $msg; ?>

                <form method="POST" enctype="multipart/form-data" class="upload-form">
                    <div class="form-group">
                        <label>Title:</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Select Images:</label>
                        <input type="file" name="photos[]" multiple accept="image/*" class="form-control" required>
                        <small class="text-muted">Allowed formats: JPG, PNG, GIF</small>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Category:</label>
                            <select class="form-control" id="category" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category['id']; ?>">
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
                                        data-category="<?php echo $subcat['category_id']; ?>">
                                        <?php echo htmlspecialchars($subcat['sub_category_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Publish Date:</label>
                            <input type="date" name="publish_date" class="form-control" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Publish Month:</label>
                            <select name="publish_month" class="form-control" required>
                                <?php
                                $months = ['January', 'February', 'March', 'April', 'May', 'June', 
                                          'July', 'August', 'September', 'October', 'November', 'December'];
                                foreach ($months as $month): ?>
                                    <option value="<?php echo $month; ?>"><?php echo $month; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <button type="submit" name="upload" class="btn btn-primary btn-block">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </form>

                <!-- Full-size Image Modal -->
                <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xl">
                        <div class="modal-content">
                            <!-- <div class="modal-header">
                                <h5 class="modal-title" id="imageModalLabel"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div> -->
                            <div class="modal-body p-0">
                                <div class="image-viewer-container position-relative">
                                <button type="button" class="modal-close-btn" data-dismiss="modal" aria-label="Close">&times;</button>
                                <img src="" id="fullsizeImage" class="img-fluid w-100">
                            </div>

                            </div>
                            <!-- <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div> -->
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-sm-3"><a href="logout.php" class="btn btn-danger my-2">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a></div>
        </div>
        <div class="row">
                <hr class="my-4">

                <!-- Gallery Section -->
                <h3 class="text-center mb-4">Gallery Preview</h3>

                <!-- Scrollable Thumbnail Container -->
                <div class="thumbnail-container mb-4">
                    <div class="thumbnail-scroller">
                        <?php
                        $query = "SELECT * FROM photo_gallary ORDER BY publish_date DESC";
                        $result = $db->runBaseQuery($query);
                        foreach ($result as $photo): ?>
                            <div class="thumbnail-item">
                                <img src="./uploads/<?php echo htmlspecialchars($photo['photo']); ?>" 
                                     alt="<?php echo htmlspecialchars($photo['title']); ?>"
                                     class="gallery-thumbnail"
                                     data-fullsize="./uploads/<?php echo htmlspecialchars($photo['photo']); ?>"
                                     title="<?php echo htmlspecialchars($photo['title']); ?>">
                                <div class="thumbnail-caption">
                                    <h6><?php echo htmlspecialchars($photo['title']); ?></h6>
                                    <small class="text-muted">
                                        <?php echo htmlspecialchars($photo['category']); ?> / 
                                        <?php echo htmlspecialchars($photo['sub_category']); ?>
                                    </small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
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

    // Handle thumbnail click
    $(document).on('click', '.gallery-thumbnail', function() {
        var imgSrc = $(this).data('fullsize');
        var imgTitle = $(this).attr('title');
        
        $('#fullsizeImage').attr('src', imgSrc);
        $('#imageModalLabel').text(imgTitle);
        $('#imageModal').modal('show');
    });

    // Click image to close (optional)
    $(document).on('click', '#fullsizeImage', function() {
        $('#imageModal').modal('hide');
    });

    // Keyboard navigation
    $(document).keydown(function(e) {
        if ($('#imageModal').hasClass('show')) {
            var currentImg = $('#fullsizeImage').attr('src');
            var thumbnails = $('.gallery-thumbnail');
            var currentIndex = thumbnails.index(thumbnails.filter('[data-fullsize="' + currentImg + '"]'));
            
            if (e.keyCode == 37 && currentIndex > 0) { // Left arrow
                e.preventDefault();
                thumbnails.eq(currentIndex - 1).click();
            } else if (e.keyCode == 39 && currentIndex < thumbnails.length - 1) { // Right arrow
                e.preventDefault();
                thumbnails.eq(currentIndex + 1).click();
            }
        }
    });
    
    // Center modal image on load
    $('#imageModal').on('shown.bs.modal', function() {
        $('.image-viewer-container').scrollTop(0);
    });
});
</script>
</body>
</html>