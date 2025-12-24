<?php
// import.php - Complete dynamic import system with side-by-side mapping
session_start();
require_once 'db_connection.php';

// Check if PhpSpreadsheet is available
$phpspreadsheet_available = false;
try {
    if (file_exists('vendor/autoload.php')) {
        require_once 'vendor/autoload.php';
        $phpspreadsheet_available = true;
    }
} catch (Exception $e) {
    die("<div style='color: red; padding: 20px;'>Error: PhpSpreadsheet not found. Please install via: composer require phpoffice/phpspreadsheet</div>");
}

use PhpOffice\PhpSpreadsheet\IOFactory;

// Helper functions
function getTableColumns() {
    global $pdo;
    $columns = [];
    try {
        $stmt = $pdo->query("DESCRIBE candidates_tbl_new");
        $tableInfo = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($tableInfo as $column) {
            $colName = $column['Field'];
            if ($colName !== 'id' && $colName !== 'created_at' && $colName !== 'updated_at') {
                $columns[$colName] = [
                    'type' => $column['Type'],
                    'null' => $column['Null'],
                    'key' => $column['Key'],
                    'default' => $column['Default'],
                    'extra' => $column['Extra']
                ];
            }
        }
    } catch (Exception $e) {
        die("<div style='color: red; padding: 20px;'>Database Error: " . htmlspecialchars($e->getMessage()) . "</div>");
    }
    return $columns;
}

function cleanHeaderName($name) {
    if (empty($name)) return '';
    $name = trim($name);
    $name = preg_replace('/[^\x20-\x7E]/', '', $name); // Remove non-ASCII
    $name = str_replace(['(', ')', '[', ']', '{', '}', '<', '>'], '', $name);
    return $name;
}

function extractExcelHeaders($file_path) {
    try {
        $spreadsheet = IOFactory::load($file_path);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestColumn = $worksheet->getHighestColumn();
        
        $headers = [];
        for ($col = 'A'; $col <= $highestColumn; $col++) {
            $cellValue = $worksheet->getCell($col . '1')->getValue();
            $headerName = cleanHeaderName($cellValue);
            
            if (empty($headerName)) {
                $headerName = "Column_$col";
            }
            
            $headers[$col] = [
                'name' => $headerName,
                'letter' => $col,
                'sample' => []
            ];
            
            // Get sample data from first few rows
            for ($row = 2; $row <= min(5, $worksheet->getHighestRow()); $row++) {
                $cellValue = $worksheet->getCell($col . $row)->getValue();
                if ($cellValue !== null && trim($cellValue) !== '') {
                    $headers[$col]['sample'][] = substr(trim($cellValue), 0, 50);
                    if (count($headers[$col]['sample']) >= 3) break;
                }
            }
        }
        
        return [
            'headers' => $headers,
            'total_rows' => $worksheet->getHighestRow() - 1,
            'spreadsheet' => $spreadsheet
        ];
        
    } catch (Exception $e) {
        throw new Exception("Error reading Excel file: " . $e->getMessage());
    }
}

function getCellValue($worksheet, $row, $columnLetter) {
    if (empty($columnLetter)) return null;
    try {
        $cellValue = $worksheet->getCell($columnLetter . $row)->getValue();
        if ($cellValue instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText) {
            $cellValue = $cellValue->getPlainText();
        }
        return $cellValue;
    } catch (Exception $e) {
        return null;
    }
}

function convertExcelDate($excelDate) {
    if (empty($excelDate)) return null;
    
    if (is_string($excelDate)) {
        // Try common date formats
        $formats = [
            'Y-m-d H:i:s', 'Y-m-d H:i:s.u', 'Y-m-d', 
            'd/m/Y H:i:s', 'd/m/Y', 'm/d/Y H:i:s', 'm/d/Y',
            'd-M-Y', 'd M Y', 'd F Y', 'j/n/Y'
        ];
        
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
            // If conversion fails, try as Unix timestamp
            if ($excelDate > 25569) { // Excel date (days since 1900)
                $unixTimestamp = ($excelDate - 25569) * 86400;
                return date('Y-m-d', $unixTimestamp);
            }
        }
    }
    
    return null;
}

function getNumericValue($value) {
    if (empty($value)) return null;
    
    // Extract first number from string
    if (preg_match('/[-+]?[0-9]*\.?[0-9]+/', $value, $matches)) {
        return floatval($matches[0]);
    }
    
    return floatval($value);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['process_import']) && isset($_FILES['excel_file'])) {
        handleFileUpload();
    } elseif (isset($_POST['execute_import'])) {
        executeImport();
    } else {
        showUploadForm();
    }
} else {
    showUploadForm();
}

// Function to show upload form
function showUploadForm() {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Excel to Database Import System</title>
        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }
            
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                min-height: 100vh;
                padding: 20px;
            }
            
            .container {
                max-width: 1000px;
                margin: 0 auto;
                background: white;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            
            .header {
                background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
                color: white;
                padding: 40px;
                text-align: center;
            }
            
            .header h1 {
                font-size: 32px;
                margin-bottom: 10px;
                font-weight: 600;
            }
            
            .header p {
                opacity: 0.9;
                font-size: 16px;
                max-width: 600px;
                margin: 0 auto;
                line-height: 1.6;
            }
            
            .content {
                padding: 40px;
            }
            
            .upload-box {
                border: 3px dashed #ddd;
                border-radius: 10px;
                padding: 40px;
                text-align: center;
                margin-bottom: 30px;
                background: #fafafa;
                transition: all 0.3s ease;
            }
            
            .upload-box:hover {
                border-color: #6a11cb;
                background: #f8f9ff;
            }
            
            .upload-box i {
                font-size: 48px;
                color: #6a11cb;
                margin-bottom: 20px;
            }
            
            .upload-box h3 {
                color: #333;
                margin-bottom: 10px;
                font-size: 22px;
            }
            
            .upload-box p {
                color: #666;
                margin-bottom: 20px;
                font-size: 14px;
            }
            
            .file-input {
                display: none;
            }
            
            .file-label {
                display: inline-block;
                padding: 12px 24px;
                background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
                color: white;
                border-radius: 6px;
                cursor: pointer;
                font-weight: 600;
                transition: all 0.3s ease;
            }
            
            .file-label:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(106, 17, 203, 0.3);
            }
            
            .selected-file {
                margin-top: 15px;
                color: #28a745;
                font-weight: 600;
            }
            
            .form-row {
                display: flex;
                flex-wrap: wrap;
                gap: 20px;
                margin-bottom: 25px;
            }
            
            .form-group {
                flex: 1;
                min-width: 200px;
            }
            
            .form-group label {
                display: block;
                margin-bottom: 8px;
                font-weight: 600;
                color: #555;
                font-size: 14px;
            }
            
            .form-group input {
                width: 100%;
                padding: 12px 15px;
                border: 2px solid #e1e5e9;
                border-radius: 6px;
                font-size: 14px;
                transition: all 0.3s ease;
            }
            
            .form-group input:focus {
                outline: none;
                border-color: #6a11cb;
                box-shadow: 0 0 0 3px rgba(106, 17, 203, 0.1);
            }
            
            .submit-btn {
                display: block;
                width: 100%;
                padding: 16px;
                background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
                color: white;
                border: none;
                border-radius: 8px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                margin-top: 20px;
            }
            
            .submit-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 20px rgba(106, 17, 203, 0.2);
            }
            
            .database-info {
                background: #f8f9fa;
                border-radius: 8px;
                padding: 20px;
                margin-top: 30px;
            }
            
            .database-info h3 {
                color: #333;
                margin-bottom: 15px;
                font-size: 18px;
            }
            
            .database-columns {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }
            
            .column-tag {
                background: #e9ecef;
                padding: 6px 12px;
                border-radius: 4px;
                font-size: 13px;
                color: #495057;
                border: 1px solid #dee2e6;
            }
            
            .message {
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 6px;
                font-size: 14px;
                text-align: center;
            }
            
            .error {
                background: #f8d7da;
                color: #721c24;
                border: 1px solid #f5c6cb;
            }
            
            @media (max-width: 768px) {
                .content {
                    padding: 20px;
                }
                
                .header {
                    padding: 30px 20px;
                }
                
                .upload-box {
                    padding: 30px 20px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>📊 Excel to Database Import</h1>
                <p>Upload your Excel file and map columns to database fields</p>
            </div>
            
            <div class="content">
                <?php if(isset($_GET['error'])): ?>
                    <div class="message error">
                        <?php echo htmlspecialchars(urldecode($_GET['error'])); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data" id="uploadForm">
                    <div class="upload-box">
                        <div style="font-size: 48px; margin-bottom: 20px;">📁</div>
                        <h3>Drag & Drop or Click to Upload</h3>
                        <p>Supports .xlsx and .xls files (Max 10MB)</p>
                        <input type="file" name="excel_file" id="excel_file" class="file-input" accept=".xlsx,.xls" required>
                        <label for="excel_file" class="file-label">Choose Excel File</label>
                        <div id="fileName" class="selected-file"></div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="committe_name">👥 Committee Name</label>
                            <input type="text" name="committe_name" id="committe_name" value="Com-1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="default_viva">🎤 Default Viva Marks</label>
                            <input type="number" name="default_viva" id="default_viva" value="20" step="0.01" min="0" max="100" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="default_written">✍️ Default Written Marks</label>
                            <input type="number" name="default_written" id="default_written" value="0" step="0.01" min="0" max="100">
                        </div>
                    </div>
                    
                    <button type="submit" name="process_import" class="submit-btn">
                        🚀 Continue to Column Mapping
                    </button>
                </form>
                
                <div class="database-info">
                    <h3>📋 Your Database Structure (candidates_tbl_new)</h3>
                    <div class="database-columns">
                        <?php
                        $db_columns = getTableColumns();
                        foreach ($db_columns as $col_name => $col_info) {
                            echo '<span class="column-tag" title="' . htmlspecialchars($col_info['type']) . '">' . htmlspecialchars($col_name) . '</span>';
                        }
                        ?>
                    </div>
                    <p style="margin-top: 15px; font-size: 14px; color: #666;">
                        Total fields: <?php echo count($db_columns); ?> | Required fields: roll_no, name, post_name
                    </p>
                </div>
            </div>
        </div>
        
        <script>
            document.getElementById('excel_file').addEventListener('change', function(e) {
                const fileName = this.files[0]?.name;
                const fileElement = document.getElementById('fileName');
                
                if (fileName) {
                    const fileExt = fileName.split('.').pop().toLowerCase();
                    if (fileExt === 'xlsx' || fileExt === 'xls') {
                        fileElement.textContent = 'Selected: ' + fileName;
                        fileElement.style.color = '#28a745';
                    } else {
                        fileElement.textContent = 'Invalid file type. Please select .xlsx or .xls';
                        fileElement.style.color = '#dc3545';
                        this.value = '';
                    }
                }
            });
            
            document.getElementById('uploadForm').addEventListener('submit', function(e) {
                const fileInput = document.getElementById('excel_file');
                if (!fileInput.value) {
                    e.preventDefault();
                    alert('Please select an Excel file first.');
                    return;
                }
            });
        </script>
    </body>
    </html>
    <?php
}

// Function to handle file upload and show mapping
function handleFileUpload() {
    global $pdo;
    
    if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
        header('Location: import_excel_file.php?error=' . urlencode('Please select a valid Excel file.'));
        exit;
    }
    
    $committe_name = $_POST['committe_name'] ?? 'Com-1';
    $default_viva = floatval($_POST['default_viva'] ?? 20);
    $default_written = floatval($_POST['default_written'] ?? 0);
    
    $file_tmp_path = $_FILES['excel_file']['tmp_name'];
    $file_name = $_FILES['excel_file']['name'];
    
    // Save file temporarily
    $temp_dir = sys_get_temp_dir();
    $unique_filename = 'import_' . uniqid() . '_' . preg_replace('/[^a-zA-Z0-9.-]/', '_', $file_name);
    $temp_file_path = $temp_dir . DIRECTORY_SEPARATOR . $unique_filename;
    
    if (!move_uploaded_file($file_tmp_path, $temp_file_path)) {
        header('Location: import_excel_file.php?error=' . urlencode('Failed to save uploaded file.'));
        exit;
    }
    
    try {
        // Extract Excel headers
        $excel_data = extractExcelHeaders($temp_file_path);
        
        // Get database columns
        $db_columns = getTableColumns();
        
        // Store in session
        $_SESSION['import_data'] = [
            'temp_file' => $temp_file_path,
            'committe_name' => $committe_name,
            'default_viva' => $default_viva,
            'default_written' => $default_written,
            'excel_headers' => $excel_data['headers'],
            'db_columns' => $db_columns,
            'total_rows' => $excel_data['total_rows'],
            'spreadsheet' => null // Don't store object in session
        ];
        
        // Show mapping interface
        showMappingInterface($excel_data['headers'], $db_columns, $committe_name, $default_viva, $default_written);
        
    } catch (Exception $e) {
        if (file_exists($temp_file_path)) unlink($temp_file_path);
        header('Location: import_excel_file.php?error=' . urlencode($e->getMessage()));
        exit;
    }
}

// Function to show mapping interface
function showMappingInterface($excel_headers, $db_columns, $committe_name, $default_viva, $default_written) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Map Columns - Excel Import</title>
        <style>
            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }
            
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background: #f5f7fa;
                color: #333;
                min-height: 100vh;
            }
            
            .container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 20px;
            }
            
            .header {
                text-align: center;
                margin-bottom: 30px;
                padding: 20px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            }
            
            .header h1 {
                color: #2c3e50;
                margin-bottom: 10px;
                font-size: 28px;
            }
            
            .header p {
                color: #7f8c8d;
                font-size: 16px;
            }
            
            .mapping-container {
                display: flex;
                gap: 20px;
                margin-bottom: 30px;
            }
            
            .panel {
                flex: 1;
                background: white;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
                overflow: hidden;
            }
            
            .panel-header {
                background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                color: white;
                padding: 20px;
                font-size: 18px;
                font-weight: 600;
            }
            
            .panel-header.database {
                background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            }
            
            .panel-header.excel {
                background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            }
            
            .panel-content {
                padding: 20px;
                max-height: 600px;
                overflow-y: auto;
            }
            
            .column-list {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }
            
            .column-item {
                padding: 15px;
                border: 2px solid #e1e5e9;
                border-radius: 8px;
                cursor: move;
                transition: all 0.3s ease;
                position: relative;
            }
            
            .column-item:hover {
                border-color: #3498db;
                background: #f8f9fa;
            }
            
            .column-item.database {
                border-left: 4px solid #2ecc71;
            }
            
            .column-item.excel {
                border-left: 4px solid #e74c3c;
            }
            
            .column-header {
                font-weight: 600;
                margin-bottom: 5px;
                color: #2c3e50;
            }
            
            .column-meta {
                font-size: 12px;
                color: #7f8c8d;
                margin-bottom: 8px;
            }
            
            .column-samples {
                font-size: 11px;
                color: #95a5a6;
                font-style: italic;
                margin-top: 5px;
            }
            
            .required {
                color: #e74c3c;
                font-weight: bold;
            }
            
            .mapping-area {
                background: #f8f9fa;
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 30px;
                min-height: 300px;
                border: 2px dashed #bdc3c7;
            }
            
            .mapping-pair {
                display: flex;
                align-items: center;
                gap: 15px;
                margin-bottom: 15px;
                padding: 15px;
                background: white;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            }
            
            .mapping-from, .mapping-to {
                flex: 1;
                padding: 10px;
                background: #f8f9fa;
                border-radius: 6px;
                min-height: 60px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
            }
            
            .mapping-arrow {
                font-size: 24px;
                color: #3498db;
                font-weight: bold;
            }
            
            .remove-btn {
                background: #e74c3c;
                color: white;
                border: none;
                border-radius: 4px;
                padding: 5px 10px;
                cursor: pointer;
                font-size: 12px;
            }
            
            .controls {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 30px;
                padding: 20px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            }
            
            .btn {
                padding: 12px 24px;
                border: none;
                border-radius: 6px;
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }
            
            .btn-primary {
                background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
                color: white;
            }
            
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(52, 152, 219, 0.3);
            }
            
            .btn-secondary {
                background: #95a5a6;
                color: white;
            }
            
            .btn-secondary:hover {
                background: #7f8c8d;
            }
            
            .btn-success {
                background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
                color: white;
            }
            
            .btn-success:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(46, 204, 113, 0.3);
            }
            
            .settings {
                background: white;
                border-radius: 10px;
                padding: 20px;
                margin-bottom: 20px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            }
            
            .settings h3 {
                margin-bottom: 15px;
                color: #2c3e50;
            }
            
            .settings-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 15px;
            }
            
            .setting-item {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            
            .setting-item label {
                cursor: pointer;
                color: #34495e;
            }
            
            .instructions {
                background: #fff8e1;
                border-left: 4px solid #f39c12;
                padding: 15px;
                margin: 20px 0;
                border-radius: 0 6px 6px 0;
            }
            
            .instructions h4 {
                color: #d35400;
                margin-bottom: 10px;
            }
            
            .instructions ul {
                padding-left: 20px;
                color: #7f8c8d;
            }
            
            .instructions li {
                margin-bottom: 5px;
            }
            
            @media (max-width: 768px) {
                .mapping-container {
                    flex-direction: column;
                }
                
                .container {
                    padding: 10px;
                }
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="header">
                <h1>🔗 Map Excel Columns to Database Fields</h1>
                <p>Drag database fields to Excel columns to create mappings</p>
            </div>
            
            <div class="instructions">
                <h4>📋 How to Map:</h4>
                <ul>
                    <li><strong>Required Fields:</strong> You must map <span class="required">roll_no</span>, <span class="required">name</span>, and <span class="required">post_name</span></li>
                    <li><strong>Drag & Drop:</strong> Drag database fields from left panel to Excel columns in right panel</li>
                    <li><strong>Auto-suggest:</strong> System suggests matches based on column names</li>
                    <li><strong>Remove:</strong> Click × to remove a mapping</li>
                </ul>
            </div>
            
            <form method="POST" id="mappingForm">
                <input type="hidden" name="committe_name" value="<?php echo htmlspecialchars($committe_name); ?>">
                <input type="hidden" name="default_viva" value="<?php echo htmlspecialchars($default_viva); ?>">
                <input type="hidden" name="default_written" value="<?php echo htmlspecialchars($default_written); ?>">
                
                <div class="mapping-container">
                    <!-- Database Columns Panel -->
                    <div class="panel">
                        <div class="panel-header database">
                            <span style="font-size: 20px; margin-right: 10px;">🗄️</span>
                            DATABASE FIELDS (<?php echo count($db_columns); ?> fields)
                        </div>
                        <div class="panel-content">
                            <div class="column-list" id="databaseColumns">
                                <?php
                                $required_fields = ['roll_no', 'name', 'post_name'];
                                foreach ($db_columns as $db_col => $col_info) {
                                    $is_required = in_array($db_col, $required_fields);
                                    echo '<div class="column-item database" data-column="' . htmlspecialchars($db_col) . '" draggable="true">';
                                    echo '<div class="column-header">';
                                    echo htmlspecialchars($db_col);
                                    if ($is_required) {
                                        echo ' <span class="required">*</span>';
                                    }
                                    echo '</div>';
                                    echo '<div class="column-meta">';
                                    echo htmlspecialchars($col_info['type']);
                                    if ($col_info['null'] === 'NO') {
                                        echo ' | NOT NULL';
                                    }
                                    echo '</div>';
                                    if ($db_col === 'written_marks' || $db_col === 'viva_marks' || $db_col === 'committe_name') {
                                        echo '<div class="column-samples">';
                                        if ($db_col === 'written_marks') echo 'Default: ' . $default_written;
                                        elseif ($db_col === 'viva_marks') echo 'Default: ' . $default_viva;
                                        elseif ($db_col === 'committe_name') echo 'Default: ' . $committe_name;
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Excel Columns Panel -->
                    <div class="panel">
                        <div class="panel-header excel">
                            <span style="font-size: 20px; margin-right: 10px;">📊</span>
                            EXCEL COLUMNS (<?php echo count($excel_headers); ?> columns)
                        </div>
                        <div class="panel-content">
                            <div class="column-list" id="excelColumns">
                                <?php foreach ($excel_headers as $col_letter => $col_info): ?>
                                    <div class="column-item excel" data-column="<?php echo htmlspecialchars($col_letter); ?>" data-header="<?php echo htmlspecialchars($col_info['name']); ?>" draggable="true">
                                        <div class="column-header">
                                            <?php echo htmlspecialchars($col_letter . '. ' . $col_info['name']); ?>
                                        </div>
                                        <div class="column-meta">
                                            Column: <?php echo htmlspecialchars($col_letter); ?>
                                        </div>
                                        <?php if (!empty($col_info['sample'])): ?>
                                            <div class="column-samples">
                                                Sample: <?php echo htmlspecialchars(implode(', ', $col_info['sample'])); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Mapping Area -->
                <div class="mapping-area" id="mappingArea">
                    <div style="text-align: center; color: #7f8c8d; margin-bottom: 20px;">
                        <span style="font-size: 24px; display: block; margin-bottom: 10px;">👇</span>
                        Drag database fields here to map them to Excel columns
                    </div>
                    <div id="mappingPairs"></div>
                </div>
                
                <!-- Settings -->
                <div class="settings">
                    <h3>⚙️ Import Settings</h3>
                    <div class="settings-grid">
                        <div class="setting-item">
                            <input type="checkbox" name="skip_empty_rows" id="skip_empty_rows" checked>
                            <label for="skip_empty_rows">Skip rows with empty roll_no</label>
                        </div>
                        <div class="setting-item">
                            <input type="checkbox" name="update_existing" id="update_existing" checked>
                            <label for="update_existing">Update existing records (by roll_no)</label>
                        </div>
                        <div class="setting-item">
                            <input type="checkbox" name="dry_run" id="dry_run" value="1">
                            <label for="dry_run">Dry run (test without importing)</label>
                        </div>
                    </div>
                </div>
                
                <!-- Hidden mapping inputs -->
                <div id="hiddenMappings"></div>
                
                <!-- Controls -->
                <div class="controls">
                    <a href="import_excel_file.php" class="btn btn-secondary">
                        ← Back to Upload
                    </a>
                    
                    <div>
                        <button type="button" class="btn btn-secondary" onclick="clearAllMappings()">
                            🗑️ Clear All Mappings
                        </button>
                        <button type="submit" name="execute_import" class="btn btn-success" onclick="return validateMappings()">
                            🚀 Start Import
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <script>
            let mappings = {};
            const requiredFields = ['roll_no', 'name', 'post_name'];
            
            // Initialize drag and drop
            document.addEventListener('DOMContentLoaded', function() {
                const databaseItems = document.querySelectorAll('#databaseColumns .column-item');
                const excelItems = document.querySelectorAll('#excelColumns .column-item');
                const mappingArea = document.getElementById('mappingArea');
                
                // Add drag event listeners to database items
                databaseItems.forEach(item => {
                    item.addEventListener('dragstart', handleDragStart);
                });
                
                // Add drag event listeners to excel items
                excelItems.forEach(item => {
                    item.addEventListener('dragstart', handleDragStart);
                });
                
                // Allow drops in mapping area
                mappingArea.addEventListener('dragover', handleDragOver);
                mappingArea.addEventListener('drop', handleDrop);
                
                // Auto-suggest mappings based on name similarity
                autoSuggestMappings();
            });
            
            function handleDragStart(e) {
                const item = e.target.closest('.column-item');
                if (item.classList.contains('database')) {
                    e.dataTransfer.setData('type', 'database');
                    e.dataTransfer.setData('column', item.dataset.column);
                } else if (item.classList.contains('excel')) {
                    e.dataTransfer.setData('type', 'excel');
                    e.dataTransfer.setData('column', item.dataset.column);
                    e.dataTransfer.setData('header', item.dataset.header);
                }
                e.dataTransfer.effectAllowed = 'move';
            }
            
            function handleDragOver(e) {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
            }
            
            function handleDrop(e) {
                e.preventDefault();
                
                const type = e.dataTransfer.getData('type');
                const column = e.dataTransfer.getData('column');
                const header = e.dataTransfer.getData('header');
                
                if (type === 'database') {
                    // Database field dropped, show selection modal
                    showExcelColumnSelection(column);
                } else if (type === 'excel') {
                    // Excel column dropped, show database field selection
                    showDatabaseFieldSelection(column, header);
                }
            }
            
            function showExcelColumnSelection(dbField) {
                const excelColumns = document.querySelectorAll('#excelColumns .column-item');
                let optionsHtml = '<option value="">-- Select Excel Column --</option>';
                
                excelColumns.forEach(col => {
                    const colLetter = col.dataset.column;
                    const colHeader = col.dataset.header;
                    const isMapped = Object.values(mappings).includes(colLetter);
                    const disabled = isMapped ? 'disabled' : '';
                    const mappedInfo = isMapped ? ' (Already mapped)' : '';
                    
                    optionsHtml += `<option value="${colLetter}" ${disabled}>${colLetter}. ${colHeader}${mappedInfo}</option>`;
                });
                
                const selected = prompt(
                    `Map database field "${dbField}" to which Excel column?\n\n` +
                    `Available columns:\n` +
                    `(Copy and paste the column letter below)\n` +
                    Array.from(excelColumns).map(col => 
                        `${col.dataset.column}. ${col.dataset.header}`
                    ).join('\n'),
                    Object.keys(mappings).find(key => mappings[key] === dbField) || ''
                );
                
                if (selected !== null && selected.trim() !== '') {
                    addMapping(selected.trim().toUpperCase(), dbField);
                }
            }
            
            function showDatabaseFieldSelection(excelCol, excelHeader) {
                const dbFields = document.querySelectorAll('#databaseColumns .column-item');
                let optionsHtml = '<option value="">-- Select Database Field --</option>';
                
                dbFields.forEach(field => {
                    const dbField = field.dataset.column;
                    const isRequired = requiredFields.includes(dbField);
                    const isMapped = mappings[excelCol];
                    const disabled = isMapped ? 'disabled' : '';
                    const requiredMark = isRequired ? ' *' : '';
                    
                    optionsHtml += `<option value="${dbField}" ${disabled}>${dbField}${requiredMark}${disabled ? ' (Already mapped)' : ''}</option>`;
                });
                
                const selected = prompt(
                    `Map Excel column "${excelCol}. ${excelHeader}" to which database field?\n\n` +
                    `Available fields:\n` +
                    `(Copy and paste the field name below)\n` +
                    Array.from(dbFields).map(field => {
                        const isRequired = requiredFields.includes(field.dataset.column);
                        return `${field.dataset.column}${isRequired ? ' *' : ''}`;
                    }).join('\n'),
                    mappings[excelCol] || ''
                );
                
                if (selected !== null && selected.trim() !== '') {
                    addMapping(excelCol, selected.trim());
                }
            }
            
            function addMapping(excelCol, dbField) {
                // Remove existing mapping for this excel column
                if (mappings[excelCol]) {
                    removeMapping(excelCol);
                }
                
                // Remove existing mapping for this db field
                const existingExcelCol = Object.keys(mappings).find(key => mappings[key] === dbField);
                if (existingExcelCol) {
                    removeMapping(existingExcelCol);
                }
                
                // Add new mapping
                mappings[excelCol] = dbField;
                updateMappingDisplay();
                updateHiddenInputs();
            }
            
            function removeMapping(excelCol) {
                delete mappings[excelCol];
                updateMappingDisplay();
                updateHiddenInputs();
            }
            
            function updateMappingDisplay() {
                const mappingPairs = document.getElementById('mappingPairs');
                mappingPairs.innerHTML = '';
                
                for (const [excelCol, dbField] of Object.entries(mappings)) {
                    const excelItem = document.querySelector(`[data-column="${excelCol}"]`);
                    const dbItem = document.querySelector(`[data-column="${dbField}"]`);
                    
                    if (excelItem && dbItem) {
                        const excelHeader = excelItem.dataset.header;
                        const isRequired = requiredFields.includes(dbField);
                        
                        const pairDiv = document.createElement('div');
                        pairDiv.className = 'mapping-pair';
                        pairDiv.innerHTML = `
                            <div class="mapping-from">
                                <strong>${dbField}</strong>${isRequired ? ' <span class="required">*</span>' : ''}<br>
                                <small>Database Field</small>
                            </div>
                            <div class="mapping-arrow">→</div>
                            <div class="mapping-to">
                                <strong>${excelCol}. ${excelHeader}</strong><br>
                                <small>Excel Column</small>
                            </div>
                            <button type="button" class="remove-btn" onclick="removeMapping('${excelCol}')">×</button>
                        `;
                        mappingPairs.appendChild(pairDiv);
                    }
                }
                
                // Update instructions
                const mappingArea = document.getElementById('mappingArea');
                if (Object.keys(mappings).length > 0) {
                    mappingArea.querySelector('div').style.display = 'none';
                } else {
                    mappingArea.querySelector('div').style.display = 'block';
                }
            }
            
            function updateHiddenInputs() {
                const hiddenDiv = document.getElementById('hiddenMappings');
                hiddenDiv.innerHTML = '';
                
                for (const [excelCol, dbField] of Object.entries(mappings)) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `mapping[${dbField}]`;
                    input.value = excelCol;
                    hiddenDiv.appendChild(input);
                }
            }
            
            function clearAllMappings() {
                if (confirm('Are you sure you want to clear all mappings?')) {
                    mappings = {};
                    updateMappingDisplay();
                    updateHiddenInputs();
                }
            }
            
            function autoSuggestMappings() {
                const dbFields = document.querySelectorAll('#databaseColumns .column-item');
                const excelItems = document.querySelectorAll('#excelColumns .column-item');
                
                // Simple name matching
                dbFields.forEach(dbItem => {
                    const dbField = dbItem.dataset.column.toLowerCase();
                    
                    excelItems.forEach(excelItem => {
                        const excelHeader = excelItem.dataset.header.toLowerCase();
                        const excelCol = excelItem.dataset.column;
                        
                        // Check for exact or partial matches
                        if (excelHeader.includes(dbField) || dbField.includes(excelHeader)) {
                            // Auto-map if not already mapped
                            if (!mappings[excelCol] && !Object.values(mappings).includes(dbField)) {
                                addMapping(excelCol, dbItem.dataset.column);
                            }
                        }
                    });
                });
            }
            
            function validateMappings() {
                const missingRequired = requiredFields.filter(field => 
                    !Object.values(mappings).includes(field)
                );
                
                if (missingRequired.length > 0) {
                    alert('Error: The following required fields are not mapped:\n' + 
                          missingRequired.join(', ') + '\n\nPlease map these fields before proceeding.');
                    return false;
                }
                
                if (Object.keys(mappings).length === 0) {
                    alert('Error: No columns are mapped. Please map at least the required fields.');
                    return false;
                }
                
                return true;
            }
        </script>
    </body>
    </html>
    <?php
}

// Function to execute the import
function executeImport() {
    global $pdo;
    
    if (!isset($_SESSION['import_data'])) {
        echo '<div style="padding: 20px; color: red;">Session expired. Please upload the file again.</div>';
        echo '<a href="import_excel_file.php">← Back to Upload</a>';
        return;
    }
    
    $import_data = $_SESSION['import_data'];
    $mapping = $_POST['mapping'] ?? [];
    $committe_name = $_POST['committe_name'] ?? $import_data['committe_name'];
    $default_viva = floatval($_POST['default_viva'] ?? $import_data['default_viva']);
    $default_written = floatval($_POST['default_written'] ?? $import_data['default_written']);
    $skip_empty_rows = isset($_POST['skip_empty_rows']);
    $update_existing = isset($_POST['update_existing']);
    $dry_run = isset($_POST['dry_run']);
    
    $temp_file_path = $import_data['temp_file'];
    
    if (!file_exists($temp_file_path)) {
        echo '<div style="padding: 20px; color: red;">Temporary file not found. Please upload again.</div>';
        unset($_SESSION['import_data']);
        echo '<a href="import_excel_file.php">← Back to Upload</a>';
        return;
    }
    
    // Check required mappings
    $required_fields = ['roll_no', 'name', 'post_name'];
    $missing_required = [];
    foreach ($required_fields as $field) {
        if (empty($mapping[$field])) {
            $missing_required[] = $field;
        }
    }
    
    if (!empty($missing_required)) {
        echo '<div style="padding: 20px; color: red;">Error: The following required fields are not mapped: ' . 
             implode(', ', $missing_required) . '</div>';
        showMappingInterface($import_data['excel_headers'], $import_data['db_columns'], 
                          $committe_name, $default_viva, $default_written);
        return;
    }
    
    try {
        // Load Excel file again
        $spreadsheet = IOFactory::load($temp_file_path);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        
        // Start import
        echo '<!DOCTYPE html><html><head><title>Import Results</title><style>
            body { font-family: Arial; padding: 20px; background: #f5f7fa; }
            .container { max-width: 1200px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            h1 { color: #2c3e50; margin-bottom: 20px; }
            .result-box { background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0; }
            .progress { height: 20px; background: #e9ecef; border-radius: 10px; margin: 20px 0; overflow: hidden; }
            .progress-bar { height: 100%; background: linear-gradient(90deg, #3498db, #2ecc71); transition: width 0.3s; }
            .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin: 20px 0; }
            .stat-item { background: white; padding: 15px; border-radius: 6px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
            .stat-value { font-size: 24px; font-weight: bold; margin: 5px 0; }
            .success { color: #27ae60; }
            .error { color: #e74c3c; }
            .warning { color: #f39c12; }
            .btn { display: inline-block; padding: 10px 20px; background: #3498db; color: white; text-decoration: none; border-radius: 5px; margin: 10px 5px; }
            .btn:hover { background: #2980b9; }
        </style></head><body>';
        
        echo '<div class="container">';
        echo '<h1>📊 Import Results</h1>';
        
        if ($dry_run) {
            echo '<div class="result-box" style="background: #fff8e1; border-left: 4px solid #f39c12;">';
            echo '<h3 style="color: #f39c12;">⚠️ DRY RUN MODE - No data will be actually imported</h3>';
            echo '</div>';
        }
        
        echo '<div class="progress"><div id="progressBar" class="progress-bar" style="width: 0%"></div></div>';
        
        flush();
        ob_flush();
        
        // Statistics
        $success_count = 0;
        $error_count = 0;
        $updated_count = 0;
        $skipped_count = 0;
        $errors = [];
        
        // Process each row
        $total_rows = $import_data['total_rows'];
        
        for ($row = 2; $row <= $highestRow; $row++) {
            // Update progress
            $progress = min(100, round(($row - 1) / $total_rows * 100));
            echo '<script>document.getElementById("progressBar").style.width = "' . $progress . '%";</script>';
            flush();
            ob_flush();
            
            try {
                // Extract data based on mapping
                $row_data = [];
                foreach ($mapping as $db_col => $excel_col) {
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
                            $row_data[$db_col] = getNumericValue($value);
                            break;
                        case 'written_marks':
                            $row_data[$db_col] = getNumericValue($value) ?? $default_written;
                            break;
                        case 'viva_marks':
                            $row_data[$db_col] = getNumericValue($value) ?? $default_viva;
                            break;
                        case 'committe_name':
                            $row_data[$db_col] = !empty($value) ? trim($value) : $committe_name;
                            break;
                        default:
                            $row_data[$db_col] = !empty($value) ? trim($value) : null;
                    }
                }
                
                // Fill unmapped fields with defaults
                $row_data['written_marks'] = $row_data['written_marks'] ?? $default_written;
                $row_data['viva_marks'] = $row_data['viva_marks'] ?? $default_viva;
                $row_data['committe_name'] = $row_data['committe_name'] ?? $committe_name;
                
                // Check essential fields
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
                
                if (!$dry_run) {
                    // Check if record exists
                    $existing_id = null;
                    if ($update_existing) {
                        $check_stmt = $pdo->prepare("SELECT id FROM candidates_tbl_new WHERE roll_no = ?");
                        $check_stmt->execute([$roll_no]);
                        $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);
                        $existing_id = $existing['id'] ?? null;
                    }
                    
                    if ($existing_id) {
                        // Update existing record
                        $update_fields = [];
                        $update_values = [];
                        
                        foreach ($row_data as $field => $value) {
                            if ($field !== 'roll_no') {
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
                        } else {
                            $error_count++;
                            $errors[] = "Row $row (Roll: $roll_no): Failed to update";
                        }
                    } else {
                        // Insert new record
                        $fields = array_keys($row_data);
                        $placeholders = array_fill(0, count($fields), '?');
                        $values = array_values($row_data);
                        
                        $fields[] = 'created_at';
                        $fields[] = 'updated_at';
                        $placeholders[] = 'NOW()';
                        $placeholders[] = 'NOW()';
                        
                        $insert_sql = "INSERT INTO candidates_tbl_new (" . implode(', ', $fields) . 
                                     ") VALUES (" . implode(', ', $placeholders) . ")";
                        
                        $insert_stmt = $pdo->prepare($insert_sql);
                        if ($insert_stmt->execute($values)) {
                            $success_count++;
                        } else {
                            $error_count++;
                            $errors[] = "Row $row (Roll: $roll_no): Failed to insert";
                        }
                    }
                } else {
                    // Dry run
                    $success_count++;
                }
                
            } catch (Exception $e) {
                $error_count++;
                $errors[] = "Row $row: " . $e->getMessage();
            }
        }
        
        // Complete progress bar
        echo '<script>document.getElementById("progressBar").style.width = "100%";</script>';
        
        // Show statistics
        echo '<div class="stats">';
        echo '<div class="stat-item"><div>Total Rows</div><div class="stat-value">' . $total_rows . '</div></div>';
        echo '<div class="stat-item"><div>Success</div><div class="stat-value success">' . $success_count . '</div></div>';
        
        if (!$dry_run) {
            echo '<div class="stat-item"><div>Updated</div><div class="stat-value warning">' . $updated_count . '</div></div>';
        }
        
        echo '<div class="stat-item"><div>Skipped</div><div class="stat-value warning">' . $skipped_count . '</div></div>';
        echo '<div class="stat-item"><div>Errors</div><div class="stat-value error">' . $error_count . '</div></div>';
        echo '</div>';
        
        // Show errors if any
        if ($error_count > 0) {
            echo '<div class="result-box" style="background: #f8d7da; border-left: 4px solid #e74c3c;">';
            echo '<h3 style="color: #e74c3c;">Error Details (first 20):</h3>';
            echo '<div style="max-height: 300px; overflow-y: auto; font-size: 12px;">';
            $display_errors = array_slice($errors, 0, 20);
            foreach ($display_errors as $error) {
                echo '<div style="padding: 5px 0; border-bottom: 1px solid #f5c6cb;">' . htmlspecialchars($error) . '</div>';
            }
            if (count($errors) > 20) {
                echo '<div style="color: #666; padding: 10px; text-align: center;">... and ' . (count($errors) - 20) . ' more errors</div>';
            }
            echo '</div>';
            
            // Log errors
            $log_file = 'import_errors_' . date('Y-m-d_His') . '.log';
            file_put_contents($log_file, "Import Date: " . date('Y-m-d H:i:s') . "\n\n" . implode("\n", $errors));
            echo '<p>Errors logged to: <code>' . htmlspecialchars($log_file) . '</code></p>';
            echo '</div>';
        }
        
        // Clean up
        if (file_exists($temp_file_path)) {
            unlink($temp_file_path);
        }
        unset($_SESSION['import_data']);
        
        // Navigation buttons
        echo '<div style="margin-top: 30px; text-align: center;">';
        echo '<a href="import_excel_file.php" class="btn">📁 Import Another File</a>';
        echo '<a href="view_candidates.php" class="btn" style="background: #27ae60;">👥 View Candidates</a>';
        echo '</div>';
        
        echo '</div></body></html>';
        
    } catch (Exception $e) {
        echo '<div class="container" style="color: red; padding: 20px;">';
        echo '<h2>Import Error</h2>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<a href="import_excel_file.php" class="btn">← Try Again</a>';
        echo '</div>';
        
        // Clean up on error
        if (isset($temp_file_path) && file_exists($temp_file_path)) {
            unlink($temp_file_path);
        }
        unset($_SESSION['import_data']);
    }
}
?>