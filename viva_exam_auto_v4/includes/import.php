<?php
// import.php
require_once 'db_connection.php';

// Include PHPExcel library
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
        redirectWithMessage("Please select a valid Excel file.", "error");
    }
    
    $committe_name = $_POST['committe_name'] ?? 'Com-1';
    $default_viva = floatval($_POST['default_viva'] ?? 20);
    $default_written = floatval($_POST['default_written'] ?? 0); // Add default written marks
    
    $file_tmp_path = $_FILES['excel_file']['tmp_name'];
    $file_name = $_FILES['excel_file']['name'];
    
    // Check file extension
    $allowed_extensions = ['xlsx', 'xls'];
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    
    if (!in_array($file_extension, $allowed_extensions)) {
        redirectWithMessage("Invalid file type. Please upload .xlsx or .xls files.", "error");
    }
    
    try {
        // Load the Excel file
        $spreadsheet = IOFactory::load($file_tmp_path);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        $highestColumn = $worksheet->getHighestColumn();
        
        // Get header row - your Excel file starts with A as roll_no
        $headers = [];
        for ($col = 'A'; $col <= $highestColumn; $col++) {
            $cellValue = $worksheet->getCell($col . '1')->getValue();
            // Clean header names - remove any whitespace
            $headers[$col] = trim($cellValue);
        }
        
        echo "<pre>Headers found: ";
        print_r($headers);
        echo "</pre>";
        
        // Prepare SQL statement
        $sql = "INSERT INTO candidates_tbl_new (
                    roll_no, post_name, name, father, mother, dob, gender, religion, 
                    quota, home_district, ssc_result, hsc_result, gra_result, mas_result, 
                    written_marks, viva_marks, committe_name, created_at, updated_at
                ) VALUES (
                    :roll_no, :post_name, :name, :father, :mother, :dob, :gender, :religion,
                    :quota, :home_district, :ssc_result, :hsc_result, :gra_result, :mas_result,
                    :written_marks, :viva_marks, :committe_name, NOW(), NOW()
                )";
        
        $stmt = $pdo->prepare($sql);
        
        $success_count = 0;
        $error_count = 0;
        $errors = [];
        
        // Map Excel columns to database columns
        $column_mapping = [
            'roll_no' => 'A',          // roll_no
            'post_name' => 'C',        // post_name
            'name' => 'D',             // name
            'father' => 'E',           // father
            'mother' => 'F',           // mother
            'dob' => 'G',              // dob
            'gender' => 'I',           // gender
            'religion' => 'M',         // religion
            'quota' => 'N',            // quota
            'home_district' => 'P',    // home_district (using present_district as fallback)
            'ssc_result' => 'AQ',      // ssc_result (Column AQ has ssc_result)
            'hsc_result' => 'AX',      // hsc_result (Column AX has hsc_result)
            'gra_result' => 'BM',      // gra_result (Column BM has gra_result)
            'mas_result' => 'BQ',      // mas_result (Column BQ has mas_result)
        ];
        
        // Process each row starting from row 2 (skip header)
        for ($row = 2; $row <= $highestRow; $row++) {
            try {
                // Extract data based on column mapping
                $roll_no = trim(getCellValue($worksheet, $row, $column_mapping['roll_no']));
                $post_name = trim(getCellValue($worksheet, $row, $column_mapping['post_name']));
                $name = trim(getCellValue($worksheet, $row, $column_mapping['name']));
                
                // Skip if essential fields are empty
                if (empty($roll_no) || empty($post_name) || empty($name)) {
                    $error_count++;
                    $errors[] = "Row $row: Missing essential data (roll_no, post_name, or name)";
                    continue;
                }
                
                // Convert Excel date to MySQL date format
                $dob_excel = getCellValue($worksheet, $row, $column_mapping['dob']);
                $dob = convertExcelDate($dob_excel);
                
                // Get home_district - try multiple columns
                $home_district = trim(getCellValue($worksheet, $row, $column_mapping['home_district']));
                if (empty($home_district)) {
                    // Try column Q (present_district) if P is empty
                    $home_district = trim(getCellValue($worksheet, $row, 'Q'));
                }
                
                // Get academic results
                $ssc_result = getNumericResult(getCellValue($worksheet, $row, $column_mapping['ssc_result']));
                $hsc_result = getNumericResult(getCellValue($worksheet, $row, $column_mapping['hsc_result']));
                $gra_result = getNumericResult(getCellValue($worksheet, $row, $column_mapping['gra_result']));
                $mas_result = getNumericResult(getCellValue($worksheet, $row, $column_mapping['mas_result']));
                
                // Bind parameters
                $stmt->bindValue(':roll_no', $roll_no);
                $stmt->bindValue(':post_name', $post_name);
                $stmt->bindValue(':name', $name);
                $stmt->bindValue(':father', trim(getCellValue($worksheet, $row, $column_mapping['father'])));
                $stmt->bindValue(':mother', trim(getCellValue($worksheet, $row, $column_mapping['mother'])));
                $stmt->bindValue(':dob', $dob);
                $stmt->bindValue(':gender', trim(getCellValue($worksheet, $row, $column_mapping['gender'])));
                $stmt->bindValue(':religion', trim(getCellValue($worksheet, $row, $column_mapping['religion'])));
                $stmt->bindValue(':quota', trim(getCellValue($worksheet, $row, $column_mapping['quota'])));
                $stmt->bindValue(':home_district', $home_district);
                $stmt->bindValue(':ssc_result', $ssc_result);
                $stmt->bindValue(':hsc_result', $hsc_result);
                $stmt->bindValue(':gra_result', $gra_result);
                $stmt->bindValue(':mas_result', $mas_result);
                $stmt->bindValue(':written_marks', $default_written); // Default value or you can calculate
                $stmt->bindValue(':viva_marks', $default_viva);
                $stmt->bindValue(':committe_name', $committe_name);
                
                if ($stmt->execute()) {
                    $success_count++;
                    echo "Successfully imported: $roll_no - $name<br>";
                } else {
                    $error_count++;
                    $errorInfo = $stmt->errorInfo();
                    $errors[] = "Row $row (Roll: $roll_no): Failed to insert - " . $errorInfo[2];
                    echo "Error importing $roll_no: " . $errorInfo[2] . "<br>";
                }
                
            } catch (Exception $e) {
                $error_count++;
                $errors[] = "Row $row: " . $e->getMessage();
                echo "Exception at row $row: " . $e->getMessage() . "<br>";
            }
        }
        
        $message = "Import completed. Success: $success_count, Errors: $error_count";
        if (!empty($errors)) {
            $message .= "<br><br>Errors:<br>" . implode('<br>', array_slice($errors, 0, 10));
            if (count($errors) > 10) {
                $message .= "<br>... and " . (count($errors) - 10) . " more errors";
            }
        }
        
        // Save error log
        if (!empty($errors)) {
            file_put_contents('import_errors.log', date('Y-m-d H:i:s') . "\n" . implode("\n", $errors) . "\n\n", FILE_APPEND);
        }
        
        echo "<h3>$message</h3>";
        echo '<br><a href="upload_form.html">Go Back</a>';
        
    } catch (Exception $e) {
        echo "<h3>Error processing file: " . $e->getMessage() . "</h3>";
        echo '<br><a href="upload_form.html">Go Back</a>';
    }
} else {
    redirectWithMessage("Invalid request method.", "error");
}

// Helper functions
function getCellValue($worksheet, $row, $columnLetter) {
    if (empty($columnLetter)) {
        return null;
    }
    
    $cellValue = $worksheet->getCell($columnLetter . $row)->getValue();
    
    // Handle formula cells
    if ($cellValue instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
        $cellValue = $cellValue->getPlainText();
    }
    
    return $cellValue;
}

function convertExcelDate($excelDate) {
    if (empty($excelDate)) {
        return null;
    }
    
    // If it's already a string date (like "1998-01-17 00:00:00")
    if (is_string($excelDate)) {
        // Try to parse the date string
        $formats = [
            'Y-m-d H:i:s',
            'Y-m-d',
            'd/m/Y H:i:s',
            'd/m/Y',
            'm/d/Y H:i:s',
            'm/d/Y',
            'd-M-Y',
            'd M Y'
        ];
        
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $excelDate);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }
        
        // Try strtotime as fallback
        $timestamp = strtotime($excelDate);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }
    }
    
    // If it's an Excel serial date
    if (is_numeric($excelDate)) {
        try {
            $date = Date::excelToDateTimeObject($excelDate);
            return $date->format('Y-m-d');
        } catch (Exception $e) {
            // If conversion fails, try as Unix timestamp
            if ($excelDate > 25569) { // Excel date (days since 1900)
                $unixTimestamp = ($excelDate - 25569) * 86400;
                return date('Y-m-d', $unixTimestamp);
            }
        }
    }
    
    return null;
}

function getNumericResult($value) {
    if (empty($value)) {
        return null;
    }
    
    // Extract numeric value from string like "5" or "3.5" or "5 (Out of 5)"
    if (preg_match('/(\d+(\.\d+)?)/', $value, $matches)) {
        return floatval($matches[1]);
    }
    
    return floatval($value);
}

function redirectWithMessage($message, $type = "info") {
    $encoded_message = urlencode($message);
    $encoded_type = urlencode($type);
    header("Location: upload_form.html?message=$encoded_message&type=$encoded_type");
    exit();
}
?>