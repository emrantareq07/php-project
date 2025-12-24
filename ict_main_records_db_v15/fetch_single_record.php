<?php
include('db/db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Fetch the record from the database
    $sql = "SELECT requisition_no, emp_id, user_name, division_dept, designation, 
                   place_of_posting, moblie_pabx, date, product_name, p_sn, srm, 
                   srm_ref_no, bill_or_challan_no, remarks, vendor_name, amounts 
            FROM records_tbl WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($record = $result->fetch_assoc()) {
        // Convert product names, amounts, and p_sn into arrays
        $productIds = explode(',', $record['product_name']);
        $amounts = explode(',', $record['amounts']);
        $pSnValues = explode(',', $record['p_sn']); // Retrieve p_sn

        $products = [];
        for ($i = 0; $i < count($productIds); $i++) {
            $products[$productIds[$i]] = [
                "amount" => $amounts[$i] ?? "0",
                "p_sn" => $pSnValues[$i] ?? "0"
            ];
        }

        echo json_encode([
            "status" => "success",
            "data" => [
                "id" => $id,
                "requisition_no" => $record['requisition_no'],
                "emp_id" => $record['emp_id'],
                "user_name" => $record['user_name'],
                "division_dept" => $record['division_dept'],
                "designation" => $record['designation'],
                "place_of_posting" => $record['place_of_posting'],
                // "moblie_pabx" => $record['moblie_pabx'],
                "date" => $record['date'],
                // "p_sn" => $record['p_sn'],
                "srm" => $record['srm'],
                "srm_ref_no" => $record['srm_ref_no'],
                "bill_or_challan_no" => $record['bill_or_challan_no'],
                "remarks" => $record['remarks'],
                "vendor_name" => $record['vendor_name'],
                "products" => $products
            ]
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Record not found"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}

$conn->close();
?>

