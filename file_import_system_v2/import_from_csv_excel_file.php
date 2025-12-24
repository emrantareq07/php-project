<?php
include 'db_connection.php';

// Include PhpSpreadsheet Autoload
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['import'])) {
    $file = $_FILES['file']['tmp_name'];
    $ext  = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);

    // Detect file type
    if ($ext == 'csv') {
        $reader = IOFactory::createReader('Csv');
        $reader->setDelimiter(",");
        $reader->setEnclosure('"');
    } else {
        $reader = IOFactory::createReader('Xlsx'); // supports .xlsx and .xls
    }

    $spreadsheet = $reader->load($file);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    // Skip header
    $header = array_shift($rows);

    // Prepared statement for candidates_tbl_new
    $sql_candidates_tbl = "
        INSERT INTO candidates_tbl_new (
            roll_no, post_name, name, father, mother, dob, gender, religion,
            quota, home_district, ssc_result, hsc_result, gra_result, mas_result,
            written_marks, viva_marks, committe_name, created_at, updated_at
        ) VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW()
        )
    ";
    $stmt = $conn->prepare($sql_candidates_tbl);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    foreach ($rows as $row) {

        // function to get column value from CSV/Excel
        $get = function($colName) use ($header, $row) {
            $index = array_search($colName, $header);
            return $index !== false ? $row[$index] : NULL;
        };

        // Extract fields
        $roll_no        = $get('roll_no');
        $post_name      = $get('post_name');
        $name           = $get('name');
        $father         = $get('father');
        $mother         = $get('mother');
        $dob_raw        = $get('dob'); // Raw date from CSV/Excel
        $gender         = $get('gender');
        $religion       = $get('religion');
        $quota          = $get('quota');
        $home_district  = $get('home_district');
        $ssc_result     = $get('ssc_result');
        $hsc_result     = $get('hsc_result');
        $gra_result     = $get('gra_result');
        $mas_result     = $get('mas_result');

        // Convert DOB to MySQL date format
        $dob = null;
        if (!empty($dob_raw)) {
            // Try Excel numeric date
            if (is_numeric($dob_raw)) {
                $dob = date('Y-m-d', ($dob_raw - 25569) * 86400); // Excel to UNIX timestamp
            } else {
                $timestamp = strtotime($dob_raw);
                if ($timestamp !== false) {
                    $dob = date('Y-m-d', $timestamp);
                }
            }
        }

        // Default values
        $writtenMarks   = 0;
        $vivaMarks      = 20;
        $committee      = "Com-1";

        // Bind + Insert
        $stmt->bind_param(
            "sssssssssssssssii",
            $roll_no,
            $post_name,
            $name,
            $father,
            $mother,
            $dob,
            $gender,
            $religion,
            $quota,
            $home_district,
            $ssc_result,
            $hsc_result,
            $gra_result,
            $mas_result,
            $writtenMarks,
            $vivaMarks,
            $committee
        );

        $stmt->execute();

        // Find the index of 'dob' column in CSV
        $dobIndex = array_search('dob', $header);
        if ($dobIndex !== false) {
            $row[$dobIndex] = $dob; // replace raw DOB with formatted MySQL date
        }

        // Now prepare values for viva_applicants
        $values = array_map(function($v) use ($conn) {
            return "'" . $conn->real_escape_string($v) . "'";
        }, $row);

        $sql1 = "INSERT INTO viva_applicants (`" . implode("`,`", $header) . "`)
                 VALUES (" . implode(",", $values) . ")";
        $conn->query($sql1);

    }

    $stmt->close();
    echo "<script>alert('CSV/Excel Imported Successfully with DOB!');</script>";
}
?>

<form method="post" enctype="multipart/form-data">
    <input type="file" name="file" accept=".csv,.xlsx,.xls" required>
    <button type="submit" name="import">Import File</button>
</form>
