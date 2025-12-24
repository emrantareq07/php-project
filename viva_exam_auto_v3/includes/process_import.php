<?php
// process_import.php
require_once 'db_connection.php';
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $committe_name = $_POST['committe_name'];
    $default_viva = floatval($_POST['default_viva']);
    $default_written = floatval($_POST['default_written']);
    $mapping = $_POST['mapping'];
    $file_path = $_POST['file_path'];
    $skip_empty_rows = isset($_POST['skip_empty_rows']);
    $update_existing = isset($_POST['update_existing']);
    
    try {
        // Reload the Excel file
        $spreadsheet = IOFactory::load($file_path);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        
        // Get database columns
        $db_columns = getTableColumns();
        
        // Prepare SQL for INSERT or UPDATE
        $success_count = 0;
        $error_count = 0;
        $updated_count = 0;
        $skipped_count = 0;
        $errors = [];
        
        // Process each row
        for ($row = 2; $row <= $highestRow; $row++) {
            try {
                // Extract data based on mapping
                $row_data = [];
                foreach ($mapping as $db_col => $excel_col) {
                    if (empty($excel_col)) {
                        // Use default values for unmapped required fields
                        if ($db_col === 'viva_marks') {
                            $row_data[$db_col] = $default_viva;
                        } elseif ($db_col === 'written_marks') {
                            $row_data[$db_col] = $default_written;
                        } elseif ($db_col === 'committe_name') {
                            $row_data[$db_col] = $committe_name;
                        } else {
                            $row_data[$db_col] = null;
                        }
                    } else {
                        $value = getCellValue($worksheet, $row, $excel_col);
                        
                        // Process special data types
                        switch ($db_col) {
                            case 'dob':
                                $row_data[$db_col] = convertExcelDate($value);
                                break;
                            case 'ssc_result':
                            case 'hsc_result':
                            case 'gra_result':
                            case 'mas_result':
                                $row_data[$db_col] = getNumericResult($value);
                                break;
                            case 'written_marks':
                            case 'viva_marks':
                                $row_data[$db_col] = is_numeric($value) ? floatval($value) : 
                                    ($db_col === 'viva_marks' ? $default_viva : $default_written);
                                break;
                            case 'committe_name':
                                $row_data[$db_col] = !empty($value) ? trim($value) : $committe_name;
                                break;
                            default:
                                $row_data[$db_col] = trim($value);
                        }
                    }
                }
                
                // Check if essential fields are present
                $roll_no = $row_data['roll_no'] ?? '';
                $name = $row_data['name'] ?? '';
                
                if ($skip_empty_rows && (empty($roll_no) || empty($name))) {
                    $skipped_count++;
                    continue;
                }
                
                if (empty($roll_no) || empty($name)) {
                    $error_count++;
                    $errors[] = "Row $row: Missing roll_no or name";
                    continue;
                }
                
                // Check if record already exists
                $existing_id = null;
                if ($update_existing) {
                    $check_stmt = $pdo->prepare("SELECT id FROM candidates_tbl_new WHERE roll_no = ?");
                    $check_stmt->execute([$roll_no]);
                    $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);
                    $existing_id = $existing['id'] ?? null;
                }
                
                if ($existing_id) {
                    // UPDATE existing record
                    $update_fields = [];
                    $update_values = [];
                    
                    foreach ($row_data as $field => $value) {
                        if ($field !== 'roll_no') { // Don't update roll_no
                            $update_fields[] = "$field = ?";
                            $update_values[] = $value;
                        }
                    }
                    
                    $update_values[] = $existing_id;
                    
                    $update_sql = "UPDATE candidates_tbl_new SET " . implode(', ', $update_fields) . 
                                 ", updated_at = NOW() WHERE id = ?";
                    
                    $update_stmt = $pdo->prepare($update_sql);
                    if ($update_stmt->execute($update_values)) {
                        $updated_count++;
                        echo "Updated: $roll_no - $name<br>";
                    } else {
                        $error_count++;
                        $errors[] = "Row $row (Roll: $roll_no): Failed to update";
                    }
                } else {
                    // INSERT new record
                    $fields = array_keys($row_data);
                    $placeholders = array_fill(0, count($fields), '?');
                    $values = array_values($row_data);
                    
                    // Add timestamps
                    $fields[] = 'created_at';
                    $fields[] = 'updated_at';
                    $placeholders[] = 'NOW()';
                    $placeholders[] = 'NOW()';
                    
                    $insert_sql = "INSERT INTO candidates_tbl_new (" . implode(', ', $fields) . 
                                 ") VALUES (" . implode(', ', $placeholders) . ")";
                    
                    $insert_stmt = $pdo->prepare($insert_sql);
                    if ($insert_stmt->execute($values)) {
                        $success_count++;
                        echo "Imported: $roll_no - $name<br>";
                    } else {
                        $error_count++;
                        $errors[] = "Row $row (Roll: $roll_no): Failed to insert";
                    }
                }
                
            } catch (Exception $e) {
                $error_count++;
                $errors[] = "Row $row: " . $e->getMessage();
            }
        }
        
        // Display results
        echo "<h3>Import Results</h3>";
        echo "<div style='padding: 20px; background: #f0f0f0; border-radius: 5px;'>";
        echo "<p><strong>Successfully Imported:</strong> $success_count records</p>";
        echo "<p><strong>Updated:</strong> $updated_count records</p>";
        echo "<p><strong>Skipped (empty rows):</strong> $skipped_count rows</p>";
        echo "<p><strong>Errors:</strong> $error_count records</p>";
        echo "</div>";
        
        if ($error_count > 0) {
            echo "<h4>Error Details (first 10):</h4>";
            echo "<div style='max-height: 200px; overflow-y: auto; border: 1px solid #ccc; padding: 10px;'>";
            echo "<ul>";
            foreach (array_slice($errors, 0, 10) as $error) {
                echo "<li>$error</li>";
            }
            echo "</ul>";
            if (count($errors) > 10) {
                echo "<p>... and " . (count($errors) - 10) . " more errors</p>";
            }
            echo "</div>";
            
            // Log errors
            file_put_contents('import_errors.log', date('Y-m-d H:i:s') . "\n" . 
                implode("\n", $errors) . "\n\n", FILE_APPEND);
        }
        
        echo "<br><a href='upload_form_file.html' style='display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none;'>Import Another File</a>";
        echo "&nbsp;&nbsp;";
        echo "<a href='view_candidates.php' style='display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none;'>View Candidates</a>";
        
    } catch (Exception $e) {
        echo "<h3 style='color: red;'>Error: " . $e->getMessage() . "</h3>";
        echo "<a href='upload_form_file.html'>Go Back</a>";
    }
}

// Helper functions (same as before)
function getTableColumns() {
    global $pdo;
    $columns = [];
    $stmt = $pdo->query("DESCRIBE candidates_tbl_new");
    $tableInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($tableInfo as $column) {
        $colName = $column['Field'];
        if ($colName !== 'id' && $colName !== 'created_at' && $colName !== 'updated_at') {
            $columns[$colName] = $column['Type'];
        }
    }
    return $columns;
}

function getCellValue($worksheet, $row, $columnLetter) {
    if (empty($columnLetter)) return null;
    $cellValue = $worksheet->getCell($columnLetter . $row)->getValue();
    if ($cellValue instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
        $cellValue = $cellValue->getPlainText();
    }
    return $cellValue;
}

function convertExcelDate($excelDate) {
    if (empty($excelDate)) return null;
    
    if (is_string($excelDate)) {
        $formats = ['Y-m-d H:i:s', 'Y-m-d', 'd/m/Y H:i:s', 'd/m/Y', 'm/d/Y H:i:s', 'm/d/Y'];
        foreach ($formats as $format) {
            $date = DateTime::createFromFormat($format, $excelDate);
            if ($date !== false) return $date->format('Y-m-d');
        }
        $timestamp = strtotime($excelDate);
        if ($timestamp !== false) return date('Y-m-d', $timestamp);
    }
    
    if (is_numeric($excelDate)) {
        try {
            $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($excelDate);
            return $date->format('Y-m-d');
        } catch (Exception $e) {
            if ($excelDate > 25569) {
                $unixTimestamp = ($excelDate - 25569) * 86400;
                return date('Y-m-d', $unixTimestamp);
            }
        }
    }
    
    return null;
}

function getNumericResult($value) {
    if (empty($value)) return null;
    if (preg_match('/(\d+(\.\d+)?)/', $value, $matches)) {
        return floatval($matches[1]);
    }
    return floatval($value);
}
?>