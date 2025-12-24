<?php
include('db/db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Retrieve and sanitize data
        $id = $_POST['id'] ?? null;
        $requisition_no = $_POST['requisition_no'] ?? null;
        $date = $_POST['date'] ?? null;

         // Fetch date from records_tbl
        $sql = "SELECT date FROM records_tbl WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $date1 = $row['date'];

            // Get fiscal start and end dates
            $sql1 = "SELECT DISTINCT fiscal_start, fiscal_end FROM product_tbl WHERE ? BETWEEN fiscal_start AND fiscal_end";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("s", $date1);
            $stmt1->execute();
            $result1 = $stmt1->get_result();

            if ($result1->num_rows > 0) {
                $row1 = $result1->fetch_assoc();
                $fiscal_start = $row1['fiscal_start'];
                $fiscal_end = $row1['fiscal_end'];

                if ($date < $fiscal_start || $date > $fiscal_end) {
                    $sql12 = "SELECT DISTINCT fiscal_start, fiscal_end FROM product_tbl WHERE ? BETWEEN fiscal_start AND fiscal_end";
                    $stmt12 = $conn->prepare($sql12);
                    $stmt12->bind_param("s", $date);
                    $stmt12->execute();
                    $result12 = $stmt12->get_result();

                    if ($result12->num_rows > 0) {
                        $row12 = $result12->fetch_assoc();
                        $fiscal_start = $row12['fiscal_start'];
                        $fiscal_end = $row12['fiscal_end'];

                        $sql_req_no = "SELECT MAX(requisition_no) AS max_requisition FROM records_tbl WHERE date BETWEEN ? AND ?";
                        $stmt15 = $conn->prepare($sql_req_no);
                        $stmt15->bind_param("ss", $fiscal_start, $fiscal_end);
                        $stmt15->execute();
                        $result15 = $stmt15->get_result();

                        if ($result15->num_rows > 0) {
                            $row51 = $result15->fetch_assoc();
                            $max_requisition = $row51['max_requisition'] ?? 0;
                            $requisition_no = $max_requisition + 1;
                        } else {
                            $requisition_no = 1;
                        }
                    }
                }
            }
        }


        $product_ids = $_POST['selected_product_ids'] ?? null;
        $amounts = $_POST['product_amounts'] ?? null;
        $emp_id = $_POST['emp_id'] ?? null;
        $user_name = $_POST['user_name'] ?? null;
        $division_dept = $_POST['division_dept'] ?? null;
        $designation = $_POST['designation'] ?? null;
        $place_of_posting = $_POST['place_of_posting'] ?? 'BCIC H.O.'; // Default value
        // $p_sn = $_POST['p_sn'] ?? null;
        $srm = $_POST['srm'] ?? null;
        $srm_ref_no = $_POST['srm_ref_no'] ?? null;
        $bill_or_challan_no = $_POST['bill_or_challan_no'] ?? null;
        $vendor_name = $_POST['vendor_name'] ?? null;
        $remarks = $_POST['remarks'] ?? null;

        // Validate required fields
        // if (empty($id)) {
        //     throw new Exception("Record is required.");
        // }

        // // Ensure amounts are formatted correctly
        // if (!empty($amounts)) {
        //     $amountsArray = explode(",", $amounts);
        //     $amountsArray = array_map(fn($amt) => ($amt === "" || $amt === null) ? "0" : $amt, $amountsArray);
        //     $amounts = implode(",", $amountsArray);
        // }

        $p_sn = $_POST['selected_p_sn'];

        // Ensure amounts are properly formatted and replace empty values with 0
        $amountsArray = explode(",", $amounts);
        $amountsArray = array_map(function ($amt) {
            return ($amt === "" || $amt === null) ? "0" : $amt;
        }, $amountsArray);
        $amounts = implode(",", $amountsArray);

        // Ensure p_sn is properly formatted (replace empty values with empty string)
        $pSnArray = explode(",", $p_sn);
        $pSnArray = array_map(function ($sn) {
            return ($sn === null) ? "" : $sn;
        }, $pSnArray);
        $p_sn = implode(",", $pSnArray);

        // Update `records_tbl`
        $sql = "UPDATE records_tbl 
                SET requisition_no = ?, emp_id = ?, user_name = ?, division_dept = ?, designation = ?, 
                    place_of_posting = ?, date = ?, product_name = ?, p_sn = ?, 
                    srm = ?, srm_ref_no = ?, bill_or_challan_no = ?, 
                    vendor_name = ?, remarks = ?, amounts = ? 
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Statement preparation failed: " . $conn->error);
        }

        $stmt->bind_param(
            'sssssssssssssssi',
            $requisition_no, $emp_id, $user_name, $division_dept, $designation, 
            $place_of_posting, $date, $product_ids, $p_sn, 
            $srm, $srm_ref_no, $bill_or_challan_no, 
            $vendor_name, $remarks, $amounts, $id
        );

        if (!$stmt->execute()) {
            throw new Exception("Error updating `records_tbl`: " . $stmt->error);
        }

        // Update `employees` if `emp_id` exists
        if (!empty($emp_id)) {
            $sql_employees = "UPDATE employees 
                              SET designation = ?, 
                                  user_name = ?, 
                                  place_of_posting = ?, 
                                  division_dept = ? 
                              WHERE emp_id = ?";

            $stmt_employees = $conn->prepare($sql_employees);
            if (!$stmt_employees) {
                throw new Exception("Statement preparation for `employees` failed: " . $conn->error);
            }

            $stmt_employees->bind_param(
                "ssssi", 
                $designation, $user_name, $place_of_posting, $division_dept, $emp_id
            );

            if (!$stmt_employees->execute()) {
                throw new Exception("Error updating `employees`: " . $stmt_employees->error);
            }

            $stmt_employees->close();
        }

        // Success response
        echo json_encode(["status" => "success", "message" => "Record updated successfully"]);

        $stmt->close();
    } catch (Exception $e) {
        // Error response
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
} else {
    // Invalid request method
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}

// Close the database connection
$conn->close();
?>
