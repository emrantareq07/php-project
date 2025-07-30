<?php
require_once '../db/db.php';

// Fetch all categories with their subcategories
$categories = $pdo_conn->query("
    SELECT c.id AS cat_id, c.category_name, sc.id AS subcat_id, sc.sub_category_name 
    FROM categories c
    LEFT JOIN sub_categories sc ON c.id = sc.category_id
    ORDER BY c.category_name, sc.sub_category_name
")->fetchAll(PDO::FETCH_ASSOC);

// Organize data by category
$organizedData = [];
foreach ($categories as $item) {
    $catId = $item['cat_id'];
    if (!isset($organizedData[$catId])) {
        $organizedData[$catId] = [
            'name' => $item['category_name'],
            'subcategories' => []
        ];
    }
    if ($item['subcat_id']) {
        $organizedData[$catId]['subcategories'][] = [
            'id' => $item['subcat_id'],
            'name' => $item['sub_category_name']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .category-card {
            margin-bottom: 20px;
            border-left: 4px solid #0d6efd;
        }
        .subcategory-item {
            padding-left: 30px;
            border-left: 2px solid #6c757d;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-4">
            <h2>Category Management</h2>
            <div>
                <a href="add_category.php" class="btn btn-primary me-2">Add Category</a>
                <a href="add_subcategory.php" class="btn btn-success">Add Subcategory</a>
                <a href="../dashboard.php" class="btn btn-secondary">Back</a>
            </div>
        </div>
        
        <div class="row">
            <?php foreach ($organizedData as $catId => $category): ?>
                <div class="col-md-6">
                    <div class="card category-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                                <div>
                                    <a href="edit_category.php?id=<?php echo $catId; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="delete_category.php?id=<?php echo $catId; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <h6>Subcategories:</h6>
                                <?php if (!empty($category['subcategories'])): ?>
                                    <ul class="list-unstyled">
                                        <?php foreach ($category['subcategories'] as $subcat): ?>
                                            <li class="subcategory-item py-2">
                                                <div class="d-flex justify-content-between">
                                                    <span><?php echo htmlspecialchars($subcat['name']); ?></span>
                                                    <div>
                                                        <a href="edit_subcategory.php?id=<?php echo $subcat['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
                                                        <a href="delete_subcategory.php?id=<?php echo $subcat['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <div class="alert alert-info">No subcategories found</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>