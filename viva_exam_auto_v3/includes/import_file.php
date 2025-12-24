<?php
// import.php
require_once 'db_connection.php';
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
        redirectWithMessage("Please select a valid Excel file.", "error");
    }
    
    $committe_name = $_POST['committe_name'] ?? 'Com-1';
    $default_viva = floatval($_POST['default_viva'] ?? 20);
    $default_written = floatval($_POST['default_written'] ?? 0);
    
    $file_tmp_path = $_FILES['excel_file']['tmp_name'];
    $file_name = $_FILES['excel_file']['name'];
    
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
        
        // Get all Excel headers with their column letters
        $excel_headers = [];
        for ($col = 'A'; $col <= $highestColumn; $col++) {
            $cellValue = $worksheet->getCell($col . '1')->getValue();
            $excel_headers[$col] = trim($cellValue);
        }
        
        // Get database table columns (excluding auto-increment and timestamp columns)
        $db_columns = getTableColumns();
        
        // Display available columns for mapping
        echo "<h3>Step 1: Column Mapping</h3>";
        echo "<p><strong>Database Columns:</strong> " . implode(', ', array_keys($db_columns)) . "</p>";
        echo "<p><strong>Excel Columns Found:</strong></p>";
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        echo "<tr><th>Excel Column</th><th>Header Name</th></tr>";
        foreach ($excel_headers as $col => $header) {
            echo "<tr><td>$col</td><td>$header</td></tr>";
        }
        echo "</table>";
        
        // Auto-map columns based on name similarity
        $auto_mapping = autoMapColumns($excel_headers, array_keys($db_columns));
        
        echo "<h3>Step 2: Auto-Detected Column Mapping</h3>";
        echo "<form action='process_import.php' method='POST' id='mappingForm'>";
        echo "<input type='hidden' name='committe_name' value='$committe_name'>";
        echo "<input type='hidden' name='default_viva' value='$default_viva'>";
        echo "<input type='hidden' name='default_written' value='$default_written'>";
        echo "<input type='hidden' name='file_path' value='$file_tmp_path'>";
        
        echo "<table border='1' cellpadding='5' style='border-collapse: collapse; margin: 20px 0;'>";
        echo "<tr><th>Database Column</th><th>Map to Excel Column</th><th>Excel Header</th></tr>";
        
        foreach ($db_columns as $db_col => $db_type) {
            $selected = $auto_mapping[$db_col] ?? '';
            echo "<tr>";
            echo "<td><strong>$db_col</strong><br><small>$db_type</small></td>";
            echo "<td>";
            echo "<select name='mapping[$db_col]'>";
            echo "<option value=''>-- Not Imported --</option>";
            foreach ($excel_headers as $excel_col => $excel_header) {
                $isSelected = ($selected === $excel_col) ? 'selected' : '';
                echo "<option value='$excel_col' $isSelected>$excel_col</option>";
            }
            echo "</select>";
            echo "</td>";
            echo "<td>";
            if (!empty($selected) && isset($excel_headers[$selected])) {
                echo $excel_headers[$selected];
            } else {
                echo "-";
            }
            echo "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        
        // Required fields validation
        echo "<h3>Step 3: Import Settings</h3>";
        echo "<div style='margin: 20px 0;'>";
        echo "<label><input type='checkbox' name='skip_empty_rows' checked> Skip rows with empty roll_no</label><br>";
        echo "<label><input type='checkbox' name='update_existing' checked> Update existing records (by roll_no)</label>";
        echo "</div>";
        
        echo "<button type='submit' style='padding: 10px 20px; background: #4CAF50; color: white; border: none; cursor: pointer;'>Start Import</button>";
        echo "</form>";
        
        echo "<script>
            document.getElementById('mappingForm').addEventListener('submit', function(e) {
                var rollNoMapping = document.querySelector('select[name=\"mapping[roll_no]\"]').value;
                var nameMapping = document.querySelector('select[name=\"mapping[name]\"]').value;
                
                if (!rollNoMapping || !nameMapping) {
                    alert('Error: You must map both roll_no and name columns!');
                    e.preventDefault();
                }
            });
        </script>";
        
    } catch (Exception $e) {
        redirectWithMessage("Error processing file: " . $e->getMessage(), "error");
    }
} else {
    redirectWithMessage("Invalid request method.", "error");
}

// Function to get database table columns
function getTableColumns() {
    global $pdo;
    
    $columns = [];
    
    // Get columns from your candidates_tbl_new
    $stmt = $pdo->query("DESCRIBE candidates_tbl_new");
    $tableInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($tableInfo as $column) {
        $colName = $column['Field'];
        $colType = $column['Type'];
        
        // Skip auto-increment and timestamp columns
        if ($colName === 'id' || $colName === 'created_at' || $colName === 'updated_at') {
            continue;
        }
        
        $columns[$colName] = $colType;
    }
    
    return $columns;
}

// Function to auto-map columns based on name similarity
function autoMapColumns($excel_headers, $db_columns) {
    $mapping = [];
    
    foreach ($db_columns as $db_col) {
        $db_col_lower = strtolower($db_col);
        $best_match = '';
        $best_score = 0;
        
        foreach ($excel_headers as $excel_col => $excel_header) {
            if (empty($excel_header)) continue;
            
            $excel_header_lower = strtolower($excel_header);
            
            // Calculate similarity score
            $score = 0;
            
            // Exact match
            if ($db_col_lower === $excel_header_lower) {
                $score = 100;
            }
            // Contains match
            elseif (strpos($excel_header_lower, $db_col_lower) !== false || 
                    strpos($db_col_lower, $excel_header_lower) !== false) {
                $score = 80;
            }
            // Partial match (common variations)
            else {
                $similar_words = [
                    'roll_no' => ['roll', 'id', 'rollno','roll_no'],
                    'post_name' => ['post', 'position', 'job','post_name'],
                    'name' => ['name', 'candidate', 'applicant','name'],
                    'father' => ['father', 'parent','father'],
                    'mother' => ['mother', 'parent','mother'],
                    'dob' => ['date of birth', 'birthdate', 'birth date','dob'],
                    'gender' => ['sex','gender'],
                    'religion' => ['faith','religion'],
                    'quota' => ['category','quota'],
                    'home_district' => ['district', 'home', 'permanent district','home_district'],
                    'ssc_result' => ['ssc', 'secondary', '10th','ssc_result'],
                    'hsc_result' => ['hsc', 'higher', '12th','hsc_result'],
                    'gra_result' => ['graduation', 'bachelor', 'degree','gra_result'],
                    'mas_result' => ['masters', 'postgraduate','mas_result'],
                    'written_marks' => ['written', 'marks', 'score','written_marks'],
                    'viva_marks' => ['viva', 'interview','viva_marks'],
                    'committe_name' => ['committee', 'board','committe_name']
                ];
                
                if (isset($similar_words[$db_col])) {
                    foreach ($similar_words[$db_col] as $similar_word) {
                        if (strpos($excel_header_lower, $similar_word) !== false) {
                            $score = 70;
                            break;
                        }
                    }
                }
            }
            
            if ($score > $best_score) {
                $best_score = $score;
                $best_match = $excel_col;
            }
        }
        
        if ($best_score > 50) {
            $mapping[$db_col] = $best_match;
        }
    }
    
    return $mapping;
}

function redirectWithMessage($message, $type = "info") {
    $encoded_message = urlencode($message);
    $encoded_type = urlencode($type);
    header("Location: upload_form_file.html?message=$encoded_message&type=$encoded_type");
    exit();
}
?>