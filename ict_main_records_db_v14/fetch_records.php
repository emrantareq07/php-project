<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = "localhost";
$user = "root";
$password = "";
$database = "ict_main_records_db";
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

// DataTables parameters
$draw = intval($_POST['draw'] ?? 0);
$start = intval($_POST['start'] ?? 0);
$length = intval($_POST['length'] ?? 10);
$searchValue = $conn->real_escape_string($_POST['search']['value'] ?? '');

$start_date = $conn->real_escape_string($_POST['start_date'] ?? '');
$end_date = $conn->real_escape_string($_POST['end_date'] ?? '');
$division = $conn->real_escape_string($_POST['division'] ?? '');

// Filter conditions
$conditions = [];

if (!empty($start_date) && !empty($end_date)) {
    $conditions[] = "date BETWEEN '$start_date' AND '$end_date'";
}

if (!empty($division)) {
    $conditions[] = "division_dept = '$division'";
}

if (!empty($searchValue)) {
    $searchConditions = [];
    $searchColumns = ['requisition_no', 'user_name', 'division_dept', 'product_name', 'emp_id', 'remarks', 'p_sn'];
    foreach ($searchColumns as $col) {
        $searchConditions[] = "$col LIKE '%$searchValue%'";
    }
    $conditions[] = "(" . implode(" OR ", $searchConditions) . ")";
}

// WHERE clause
$whereClause = '';
if (!empty($conditions)) {
    $whereClause = ' WHERE ' . implode(' AND ', $conditions);
}

// Query for filtered data
$sql = "SELECT * FROM records_tbl $whereClause";

// Get total records without filter
$totalRecordsResult = $conn->query("SELECT COUNT(*) AS total FROM records_tbl");
$totalRecords = $totalRecordsResult->fetch_assoc()['total'] ?? 0;

// Get total records with filter
$totalFilteredResult = $conn->query("SELECT COUNT(*) AS total FROM records_tbl $whereClause");
$totalFiltered = $totalFilteredResult->fetch_assoc()['total'] ?? $totalRecords;

// Apply pagination
$sql .= " LIMIT $start, $length";
$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product_ids = explode(',', $row["product_name"]);
        $amounts = explode(',', $row["amounts"]);
        $p_sns = explode(',', $row["p_sn"]);
        $product_names = [];

        foreach ($product_ids as $index => $product_id) {
            $product_id = trim($product_id);
            $amount = $amounts[$index] ?? "0";
            $p_sn = $p_sns[$index] ?? "0";

            $sql22 = "SELECT name FROM product_tbl WHERE id = '$product_id'";
            $productResult = $conn->query($sql22);

            if ($productResult && $productResult->num_rows > 0) {
                $product_name = $productResult->fetch_assoc()['name'];

                if ($amount !== "0") {
                    $product_info = "$product_name [<b><span style='color:green;'>$amount Pcs</span></b>";
                    if ($p_sn !== "0") {
                        $product_info .= ", <b><span style='color:green;'>SN- $p_sn</span></b>";
                    }
                    $product_info .= "]";
                } elseif ($p_sn !== "0") {
                    $product_info = "$product_name [<b><span style='color:green;'>SN- $p_sn</span></b>]";
                } else {
                    $product_info = $product_name;
                }

                $product_names[] = $product_info;
            } else {
                $product_names[] = 'N/A';
            }
        }

        $product_names_str = str_replace(', ', '<b>,</b> ', implode(', ', $product_names));

        $data[] = [
            "id" => $row["id"],
            "requisition_no" => $row["requisition_no"],
            "emp_id" => $row["emp_id"],
            "user_name" => $row["user_name"],
            "division_dept" => $row["division_dept"],
            "designation" => $row["designation"],
            "date" => $row["date"],
            "product_name" => $product_names_str,
            "srm" => $row["srm"],
            "srm_ref_no" => $row["srm_ref_no"],
            "bill_or_challan_no" => $row["bill_or_challan_no"],
            "remarks" => $row["remarks"],
            "vendor_name" => $row["vendor_name"]
        ];
    }
}

// JSON response
$response = [
    "draw" => $draw,
    "recordsTotal" => $totalRecords,
    "recordsFiltered" => $totalFiltered,
    "data" => $data
];

header('Content-Type: application/json');
echo json_encode($response);
$conn->close();
?>
