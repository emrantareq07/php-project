<?php
include('db/db.php');
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = intval($_POST['id']);

    $stmt = $conn->prepare("SELECT * FROM records_tbl WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $record = $result->fetch_assoc(); // Get the record data
        
        $product_ids = explode(',', $record["product_name"]);
        $amounts = explode(',', $record["amounts"]);
        $p_sns = explode(',', $record["p_sn"]);
        $product_names = [];

        // Fetch product names for each product ID
        foreach ($product_ids as $index => $product_id) {
            $product_id = trim($product_id);
            $amount = isset($amounts[$index]) && $amounts[$index] !== "" ? trim($amounts[$index]) : "0"; 
            $p_sn = isset($p_sns[$index]) && $p_sns[$index] !== "" ? trim($p_sns[$index]) : "0"; 
            
            $sql22 = "SELECT name, warranty FROM product_tbl WHERE id = '$product_id'";
            $productResult = $conn->query($sql22);
            
            if ($productResult->num_rows > 0) {
                $product_data = $productResult->fetch_assoc();
                $product_name = $product_data['name'];
                $warranty = $product_data['warranty'] ?? 'No warranty';

                $product_info = $product_name;
                $details = [];

                if ($amount !== "0") {
                    $details[] = "<b><span style='color:green;'>$amount Pcs</span></b>";
                }

                if ($p_sn !== "0") {
                    $details[] = "<b><span style='color:green;'>SN: $p_sn</span></b>";
                }

                if ($warranty && $warranty !== 'No warranty') {
                    $details[] = "<b><span style='color:blue;'>Warranty: $warranty Years</span></b>";
                }

                if (!empty($details)) {
                    $product_info .= " [" . implode(', ', $details) . "]";
                }

                $product_names[] = $product_info;
            } else {
                $product_names[] = 'N/A'; // Default if no product is found
            }
        }

        // Join product names into a single string
        $product_names_str = implode(', ', $product_names);

        // Make commas bold
        $product_names_str = str_replace(', ', '<b>,</b> ', $product_names_str);

        echo json_encode([
            "status" => "success",
            "data" => $record,
            "product_names" => $product_names_str
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Record not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request. No ID provided"]);
}

$conn->close();
?>