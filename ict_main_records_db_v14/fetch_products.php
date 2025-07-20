<?php
include('db/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {
    $date = $_POST['date'];

    // Debugging: Check if date is received
    error_log("Received Date: " . $date);

    // Fetch the last fiscal year record
    $query = mysqli_query($conn, "SELECT fiscal_start, fiscal_end FROM product_tbl ORDER BY fiscal_end DESC LIMIT 1");
    $last_record = mysqli_fetch_assoc($query);

    if ($last_record) {
        $fiscal_start = $last_record['fiscal_start'];
        $fiscal_end = $last_record['fiscal_end'];

        // Debugging: Check fetched fiscal year
        error_log("Fiscal Start: " . $fiscal_start . " | Fiscal End: " . $fiscal_end);

        // Check if the selected date falls within the fiscal year range
        if ($date >= $fiscal_start && $date <= $fiscal_end) {
            // Fetch products available in this fiscal year
            $sql = "SELECT id, name FROM product_tbl WHERE '$date' BETWEEN fiscal_start AND fiscal_end";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option data-id='" . $row['id'] . "' value='" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "'></option>";
                }
            } else {
                echo "<option value=''>No products available</option>";
            }
        } else {
            echo "<option value=''>Date is out of fiscal year range</option>";
        }
    } else {
        echo "<option value=''>No fiscal year data</option>";
    }
} else {
    echo "<option value=''>Invalid request</option>";
}
?>
