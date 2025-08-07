<?php
require_once '../db/db.php';

// Check if ID parameter exists
if (!isset($_GET['id'])) {
    header("Location: manage_categories.php");
    exit();
}

$subcategoryId = $_GET['id'];

// Fetch the subcategory data
try {
    $stmt = $pdo_conn->prepare("SELECT sc.*, c.category_name 
                          FROM sub_categories sc
                          JOIN categories c ON sc.category_id = c.id
                          WHERE sc.id = :id");
    $stmt->bindParam(':id', $subcategoryId);
    $stmt->execute();
    $subcategory = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$subcategory) {
        header("Location: manage_categories.php");
        exit();
    }
} catch(PDOException $e) {
    die("Error fetching subcategory: " . $e->getMessage());
}

// Fetch all categories for dropdown
$categories = $pdo_conn->query("SELECT * FROM categories ORDER BY category_name")->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = $_POST['category_id'];
    $subCategoryName = trim($_POST['sub_category_name']);
    
    if (!empty($subCategoryName) && !empty($categoryId)) {
        try {
            $stmt = $pdo_conn->prepare("UPDATE sub_categories 
                                   SET category_id = :cat_id, 
                                       sub_category_name = :name 
                                   WHERE id = :id");
            $stmt->bindParam(':cat_id', $categoryId);
            $stmt->bindParam(':name', $subCategoryName);
            $stmt->bindParam(':id', $subcategoryId);
            $stmt->execute();
            
            $success = "Subcategory updated successfully!";
            // Update the displayed data
            $subcategory['category_id'] = $categoryId;
            $subcategory['sub_category_name'] = $subCategoryName;
        } catch(PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    } else {
        $error = "Please fill all fields!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subcategory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="form-container border border-dark">
            <h2 class="text-center mb-4">Edit Subcategory</h2>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="mb-3">
                    <label for="category_id" class="form-label">Parent Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" 
                                <?php echo ($category['id'] == $subcategory['category_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['category_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="sub_category_name" class="form-label">Subcategory Name</label>
                    <input type="text" class="form-control" id="sub_category_name" 
                           name="sub_category_name" value="<?php echo htmlspecialchars($subcategory['sub_category_name']); ?>" required>
                </div>
                
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary me-md-2">Update Subcategory</button>
                    <a href="manage_categories.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
            
            <div class="mt-4 pt-3 border-top">
                <h5>Current Information:</h5>
                <ul class="list-unstyled">
                    <li><strong>Parent Category:</strong> <?php echo htmlspecialchars($subcategory['category_name']); ?></li>
                    <li><strong>Subcategory Name:</strong> <?php echo htmlspecialchars($subcategory['sub_category_name']); ?></li>
                </ul>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>