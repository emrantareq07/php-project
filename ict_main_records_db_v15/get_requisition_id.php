<?php
include('db/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {
    // $date = '2025-05-03';
    $date = $_POST['date'];
    $requisition_no = 1; // Default requisition number

    // Fetch fiscal year for the given date
    $sql1 = "SELECT DISTINCT fiscal_start, fiscal_end FROM product_tbl WHERE ? BETWEEN fiscal_start AND fiscal_end";
    $stmt1 = mysqli_prepare($conn, $sql1);
    mysqli_stmt_bind_param($stmt1, "s", $date);
    mysqli_stmt_execute($stmt1);
    $result1 = mysqli_stmt_get_result($stmt1);

    if ($result1 && mysqli_num_rows($result1) > 0) {
        $row1 = mysqli_fetch_assoc($result1);
        $fiscal_start = $row1['fiscal_start'];
        $fiscal_end = $row1['fiscal_end'];

        // Fetch max requisition number in the fiscal year
        $sql_req_no = "SELECT MAX(requisition_no) AS max_requisition FROM records_tbl WHERE date BETWEEN ? AND ?";
        $stmt2 = mysqli_prepare($conn, $sql_req_no);
        mysqli_stmt_bind_param($stmt2, "ss", $fiscal_start, $fiscal_end);
        mysqli_stmt_execute($stmt2);
        $result_req_no = mysqli_stmt_get_result($stmt2);

        if ($result_req_no && mysqli_num_rows($result_req_no) > 0) {
            $row_req_no = mysqli_fetch_assoc($result_req_no);
            $max_requisition = $row_req_no['max_requisition'];

            // Log the fetched max requisition number for debugging
            error_log("Max requisition found: $max_requisition");

            if ($max_requisition !== null) {
                $requisition_no = $max_requisition + 1; // Increment requisition number
            }
        }
    }

    // Log the final requisition number for debugging
    error_log("Final requisition number: $requisition_no");

    echo json_encode(['requisition_no' => $requisition_no]);
    exit();
}
?>
