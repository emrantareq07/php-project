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

// Handle photo deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // First get the filename to delete from server
    $query = "SELECT photo FROM photo_gallary WHERE id = $id";
    $result = $db->runBaseQuery($query);
    
    if (!empty($result)) {
        $filename = $result[0]['photo'];
        $filepath = "./uploads/" . $filename;
        
        // Delete from database
        $deleteQuery = "DELETE FROM photo_gallary WHERE id = $id";
        $deleteResult = $db->executeQuery($deleteQuery);
        
        if ($deleteResult) {
            // Delete the file
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            $msg = "<div class='alert alert-success'>Photo deleted successfully!</div>";
        } else {
            $msg = "<div class='alert alert-danger'>Error deleting photo: " . mysqli_error($db->dbh) . "</div>";
        }
    }
}

// Get all unique categories
$categories = $db->runBaseQuery("SELECT DISTINCT category FROM photo_gallary ORDER BY category");

// Get sub-categories based on selected category (if any)
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
$subCategories = [];

if (!empty($selectedCategory)) {
    $escapedCategory = mysqli_real_escape_string($db->dbh, $selectedCategory);
    $subCategories = $db->runBaseQuery("SELECT DISTINCT sub_category FROM photo_gallary WHERE category = '$escapedCategory' ORDER BY sub_category");
} else {
    // If no category selected, get all sub-categories
    $subCategories = $db->runBaseQuery("SELECT DISTINCT sub_category FROM photo_gallary ORDER BY sub_category");
}

// Get selected sub-category
$selectedSubCategory = isset($_GET['sub_category']) ? $_GET['sub_category'] : '';

// Build the base query
$query = "SELECT * FROM photo_gallary WHERE 1=1";

// Apply category filter if selected
if (!empty($selectedCategory)) {
    $escapedCategory = mysqli_real_escape_string($db->dbh, $selectedCategory);
    $query .= " AND category = '$escapedCategory'";
}

// Apply sub-category filter if selected
if (!empty($selectedSubCategory)) {
    $escapedSubCategory = mysqli_real_escape_string($db->dbh, $selectedSubCategory);
    $query .= " AND sub_category = '$escapedSubCategory'";
}

// Add sorting
$query .= " ORDER BY publish_date DESC";

// Fetch filtered photos
$photos = $db->runBaseQuery($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Photos - Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>/static/css/login_form_style.css">
    
    <style>
        .photo-thumb {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
        }
        .action-btns .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .dataTables_wrapper .dataTables_filter input {
            margin-left: 0.5em;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .filter-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filter-section .form-group {
            margin-bottom: 0;
        }
    </style>
</head>
<body>
<div class="container-fluid bg-light">
    <div class="container bg-white shadow rounded border mt-3 pt-4 pb-4">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Hello, <span class="text-success"><?php echo htmlspecialchars($userData['Username']); ?></span></h1>
                    <div>
                        <a href="dashboard.php" class="btn btn-secondary mr-2">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                        <a href="add_photo.php" class="btn btn-primary mr-2">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                        <a href="logout.php" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>

                <h2 class="text-center text-muted mb-4">Manage Photos</h2>
                
                <?php if (isset($msg)) echo $msg; ?>
                
                <!-- Filter Section -->
                <div class="filter-section mb-4">
                    <form method="get" action="manage_photo.php" class="form-inline">
                        <div class="form-group mr-3">
                            <label for="category" class="mr-2">Category:</label>
                            <select name="category" id="category" class="form-control" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo htmlspecialchars($cat['category']); ?>" 
                                        <?php echo ($selectedCategory == $cat['category']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['category']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group mr-3">
                            <label for="sub_category" class="mr-2">Sub-Category:</label>
                            <select name="sub_category" id="sub_category" class="form-control" onchange="this.form.submit()">
                                <option value="">All Sub-Categories</option>
                                <?php foreach ($subCategories as $subCat): ?>
                                    <option value="<?php echo htmlspecialchars($subCat['sub_category']); ?>" 
                                        <?php echo ($selectedSubCategory == $subCat['sub_category']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($subCat['sub_category']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <a href="manage_photo.php" class="btn btn-outline-secondary">Reset Filters</a>
                    </form>
                </div>
                
                <div class="table-responsive">
                    <table id="photosTable" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Photo</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Sub-Category</th>
                                <th>Publish Date</th>
                                <th>Publish Month</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($photos as $photo): ?>
                            <tr>
                                <td><?php echo $photo['id']; ?></td>
                                <td>
                                    <img src="./uploads/<?php echo htmlspecialchars($photo['photo']); ?>" 
                                         alt="<?php echo htmlspecialchars($photo['title']); ?>"
                                         class="photo-thumb">
                                </td>
                                <td><?php echo htmlspecialchars($photo['title']); ?></td>
                                <td><?php echo htmlspecialchars($photo['category']); ?></td>
                                <td><?php echo htmlspecialchars($photo['sub_category']); ?></td>
                                <td><?php echo date('M d, Y', strtotime($photo['publish_date'])); ?></td>
                                <td><?php echo htmlspecialchars($photo['publish_month']); ?></td>
                                <td class="action-btns">
                                    <a href="./uploads/<?php echo htmlspecialchars($photo['photo']); ?>" 
                                       target="_blank" 
                                       class="btn btn-info btn-sm" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="edit_photo.php?id=<?php echo $photo['id']; ?>" 
                                       class="btn btn-warning btn-sm" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="manage_photo.php?delete=<?php echo $photo['id']; ?>" 
                                       class="btn btn-danger btn-sm" 
                                       title="Delete"
                                       onclick="return confirm('Are you sure you want to delete this photo?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    $('#photosTable').DataTable({
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [1, 7] }, // Disable sorting on photo and actions columns
            { searchable: false, targets: [1, 7] } // Disable searching on photo and actions columns
        ],
        order: [[0, 'desc']] // Default sort by ID descending
    });
    
    // Update sub-category dropdown based on selected category
    $('#category').change(function() {
        // Reset sub-category when category changes
        $('#sub_category').val('');
        
        // If a category is selected, fetch its sub-categories via AJAX
        var category = $(this).val();
        if (category) {
            $.ajax({
                url: 'get_subcategories.php',
                type: 'GET',
                data: { category: category },
                success: function(data) {
                    $('#sub_category').html('<option value="">All Sub-Categories</option>' + data);
                }
            });
        } else {
            // If no category selected, show all sub-categories
            $.ajax({
                url: 'get_subcategories.php',
                type: 'GET',
                success: function(data) {
                    $('#sub_category').html('<option value="">All Sub-Categories</option>' + data);
                }
            });
        }
    });
});
</script>
</body>
</html>