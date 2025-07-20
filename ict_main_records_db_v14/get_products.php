<?php
include('db/db.php');

header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {
    $date = $_POST['date'];

    $sql_products = "SELECT id, name, code FROM product_tbl WHERE ? BETWEEN fiscal_start AND fiscal_end";
    $stmt = mysqli_prepare($conn, $sql_products);
    mysqli_stmt_bind_param($stmt, "s", $date);
    mysqli_stmt_execute($stmt);
    $result_products = mysqli_stmt_get_result($stmt);

    $options = "";
    if ($result_products && mysqli_num_rows($result_products) > 0) {
        while ($row = mysqli_fetch_assoc($result_products)) {
            $productId = $row['id'];
            $productName = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
            $productCode = htmlspecialchars($row['code'], ENT_QUOTES, 'UTF-8');
            
            // Format: "Product Name (PROD001)"
            $displayText = "($productCode) $productName";
            
            $options .= "<option 
                value='$displayText'
                data-id='$productId'
                data-code='$productCode'
                data-name='$productName'
            >$displayText</option>";
        }
    } else {
        $options = "<option value=''>No products available</option>";
    }

    echo json_encode(["product_options" => $options]);
    exit();
}
?>