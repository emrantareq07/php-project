<?php
include('db/db.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date'])) {
    $date = $_POST['date']; // Get the selected date

    // Prepare and execute SQL query
    $sql = "SELECT id, name FROM product_tbl WHERE ? BETWEEN fiscal_start AND fiscal_end";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    // Generate options for the datalist
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option data-id='" . $row['id'] . "' value='" . htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8') . "'></option>";
        }
    } else {
        echo "<option value=''>No products available</option>";
    }

    $stmt->close();
    exit();
}
?>
