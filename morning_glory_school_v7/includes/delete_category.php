<?php
require_once 'db/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    try {
        // Delete associated subcategories first due to foreign key constraint
        $pdo_conn->exec("DELETE FROM sub_categories WHERE category_id = $id");
        // Then delete the category
        $pdo_conn->exec("DELETE FROM categories WHERE id = $id");
        header("Location: manage_categories.php?deleted=1");
    } catch(PDOException $e) {
        die("Error deleting category: " . $e->getMessage());
    }
} else {
    header("Location: manage_categories.php");
}