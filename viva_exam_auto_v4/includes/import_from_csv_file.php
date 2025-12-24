<?php
include 'db_connection.php';

function fixDate($date) {
    // If empty return NULL
    if (trim($date) == "" || $date == null) return "NULL";

    // Try converting using strtotime
    $ts = strtotime($date);

    if ($ts) {
        return "'" . date("Y-m-d", $ts) . "'";
    }

    // Otherwise insert raw
    return "'" . addslashes($date) . "'";
}

if (isset($_POST['import'])) {

    $file = $_FILES['file']['tmp_name'];

    if (($handle = fopen($file, "r")) !== FALSE) {

        $header = fgetcsv($handle); // Skip header row

        while (($row = fgetcsv($handle, 10000, ",")) !== FALSE) {

            $values = [];
            foreach ($row as $index => $value) {

                // Detect date columns by column name
                $col = strtolower($header[$index]);

                if (
                    strpos($col, "date") !== false || 
                    $col == "dob"
                ) {
                    // Convert date automatically
                    $values[] = fixDate($value);
                } else {
                    // Normal text
                    $values[] = "'" . addslashes($value) . "'";
                }
            }

            // Build SQL
            $sql = "INSERT INTO viva_applicants (`" . 
                    implode("`,`", $header) . 
                    "`) VALUES (" . implode(",", $values) . ")";

            mysqli_query($conn, $sql);

            
        }

        fclose($handle);

        echo "<script>alert('Data Imported Successfully!');</script>";
    }
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <button type="submit" name="import">Import CSV</button>
</form>
