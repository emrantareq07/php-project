
<?php
require_once 'db/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        // First check if there are any products in this subcategory
        $product_check = $pdo_conn->query("SELECT COUNT(*) FROM photo_gallary WHERE subcategory_id = $id")->fetchColumn();
        
        if ($product_check > 0) {
            header("Location: manage_categories.php?error=Cannot delete subcategory - it contains products");
            exit();
        }

        // Delete the subcategory
        $pdo_conn->exec("DELETE FROM sub_categories WHERE id = $id");
        header("Location: manage_categories.php?deleted=1");
    } catch(PDOException $e) {
        die("Error deleting subcategory: " . $e->getMessage());
    }
} else {
    header("Location: manage_categories.php");
}